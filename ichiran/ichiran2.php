<?php
ini_set('display_errors', 1);

function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

// $loginFlag
// 1:ログイン済み
// 0:ログインしていない
if (isset($_SESSION['loginFlag']) && isset($_SESSION['userId'])) {
    $loginFlag = $_SESSION['loginFlag'];
    $userId = $_SESSION['userId'];
} else {
    $loginFlag = 0;
}

require('../dbconnect.php');
//取得する件数nextpageと、そのうち使わない件数passpage（nextpage - passpageを表示する）
if (isset($_GET['currentpage'])) {
    $currentpage = $_GET['currentpage'];
    $nextpage = $currentpage * 10;
    $passpage = ($currentpage - 1) * 10;
} else {
    $currentpage = 1;
    $nextpage = $currentpage * 10;
    $passpage = ($currentpage - 1) * 10;
}


if (isset($_GET['category'])) {
    //カテゴリー検索の場合
    $category = $_GET['category'];
    console_log("かてあり");
    $sql = 'SELECT post.id, post.title, post.text, post.time, users.name, users.icon 
  FROM post, users, post_category, category
  WHERE post.userid=users.id and post.id=post_category.post_id and post_category.category_id=category.category_id and category.category_id=' . $category .
        ' ORDER BY post.time 
  DESC LIMIT 1000';
    console_log("かてあり2");
    $records = $db->prepare($sql);
    console_log("かてあり3");
    $records->execute();
} else if (isset($_GET['serchtext'])) {
    // 検索の場合
    console_log($_GET['serchtext']);
    $serchWords = preg_split('/[\s　]+/', $_GET['serchtext'],  PREG_SPLIT_NO_EMPTY);
    $serch = "";

    if (strcmp($_GET['status'], "checked") == 0) {
        // タグ検索の場合
        foreach ($serchWords as $serchWord) {
            $serch = $serch . " AND tags.name = '" . $serchWord . "'";
        }
        //取得する件数nextpageと、そのうち使わない件数passpage（nextpage - passpageを表示する）

        $sql = 'SELECT post.id, post.title, post.text, post.time, users.name, tags.name AS tagname, users.icon FROM post, users, post_tags, tags
  WHERE post.userid=users.id AND post.id=post_tags.post_id AND post_tags.tag_id=tags.id' . $serch . '
  ORDER BY post.time DESC LIMIT 1000';
        //var_dump($sql);
        $records = $db->prepare($sql);
        $records->execute();
    } else {
        //タグ検索ではない場合
        foreach ($serchWords as $serchWord) {
            $serch = $serch . " AND (post.text LIKE '%" . $serchWord . "%' OR post.title LIKE '%" . $serchWord . "%' OR tags.name LIKE '%" . $serchWord . "%') ";
        }
        $sql = 'SELECT post.id, post.title, post.text, post.time, users.name, tags.name AS tagname, users.icon FROM post, users, post_tags, tags
  WHERE post.userid=users.id AND post.id=post_tags.post_id AND post_tags.tag_id=tags.id' . $serch . '
  ORDER BY post.time DESC LIMIT 1000';
        //var_dump($sql);
        $records = $db->prepare($sql);
        $records->execute();
    }
} else {
    //すべての投稿を表示
    //SQL、LIMIT OFFSETは参照するデータが多くなっていくため、投稿数が多くなるたびに処理が遅くなるかも。数値を入れるため、bindParamを使わないといけない？
    $records = $db->prepare('SELECT post.id, post.title, post.text, post.time, users.name, users.icon FROM post, users WHERE post.userid=users.id ORDER BY post.time DESC LIMIT 1000');
    $records->execute();

    $totalpost = $db->prepare('SELECT COUNT(id) FROM post');
    $totalpost->execute();
    $totalpostnum = $totalpost->fetch();
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex" />
    <title>悩み一覧</title>
    <script src="jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="./../stylesheet/ichiran.css">
</head>

<?php include('../header.php'); ?>

<main>
   
    <div class="contents">
        <div class="serch">
            <input id="serchtext" class="serch" type="serch" placeholder="検索">
            <button id="serchbutton" class="serchbutton">search</button>
        </div>
        <input id="checkbox" type="checkbox">
        <label for="checkbox"> タグ完全一致で検索する</label>

        <!-- 新規投稿か検索結果かどうか -->
        <div class="category_title">
            <?php
            if (isset($_GET['category'])) {
                $cate = $db->prepare('SELECT category_name FROM category WHERE category_id=?');
                $cate->execute(array($_GET['category']));
                $cat = $cate->fetch();
                echo $cat['category_name'];
            } else if (isset($_GET['serchtext'])) {
                echo "検索結果";
            } else {
                echo "新規投稿";
            }
            ?>

        </div>

        <div id="toukou_spase">
            <?php
            $allpost = 0;
            while ($record = $records->fetch()) : ?>
                <div class="toukou" id="toukou">


                    <a href='../shousai/syousai.php?postid=<?php echo $record['id'];  ?>'>
                        <div class="toukou__inner">
                            <h1 class="title"><?php echo $record['title'] ?></h1>
                            <img src="../icon/<?php echo $record['icon']; ?>" class="icon" alt="">
                            <p class="username"><?php echo $record['name']; ?></p>
                            <p class="main">
                                <?php
                                $cut_text = $record['text'];
                                if (mb_strlen($cut_text) > 20) {
                                    $cut_text = mb_substr($cut_text, 0, 50) . "…";
                                } ?>
                                <?php echo $cut_text; ?></p>
                        </div>
                    </a>
                    <div class="tagspase" id="tagspase">
                        <?php
                        $tagarr = [];
                        $tags = $db->prepare('SELECT tags.name FROM post, post_tags, tags WHERE post.id=post_tags.post_id AND post_tags.tag_id=tags.id AND post.id=?');
                        $tags->bindParam(1, $record['id'], PDO::PARAM_INT);
                        $tags->execute();

                        while ($tag = $tags->fetch()) : ?>
                            <!-- タグをクリックしたらタグ検索 -->
                            <a href='ichiran2.php?serchtext=<?php echo $tag['name']; ?>&currentpage=1&status=checked'>
                                <span class="tag">
                                    <?php echo "#".$tag['name']; ?>
                                </span>
                            </a>
                        <?php
                        endwhile;
                        ?>
                    </div>
                </div>


            <?php 
		$allpost = $allpost + 1;
		endwhile;
            ?>
        </div>


       

    </div>




    <div class="sub">
        <div style="padding:6px;background-color:white;">
            <div style="font-size:25px;text-align: center;color:#4aa067;">カテゴリーを選ぶ</div>
            <ul class="category">
                <li><a href="ichiran2.php?currentpage=<?php echo $currentpage; ?>">すべて</a></li>
                <?php
                $categories = $db->query('SELECT * FROM category');
                $category_num = 0;
                $i = 1;
 while ($category2 = $categories->fetch()) {
    echo "<li><a href='ichiran2.php?currentpage=1&category=".$i."'>".$category2['category_name']."</a></li>";
        $i =  $i + 1;
        
 }
                ?>

            </ul>
        </div>
    </div>

    <script>
    var totalpost = <?php echo $allpost ?>;
    

//検索結果が０件だった時に「見つかりませんでした」
if(totalpost === 0){
        $('#toukou_spase').children().remove();
        $('#toukou_spase').append('<p style="display: inline-block;margin-left: 5vw;">投稿がありません</p>');
    }

    //検索ボタンを押したときの処理
    $('#serchbutton').on('click', function() {
        var serchTextBox = document.getElementById('serchtext');
        var serchText = serchTextBox.value.replace("#", "").replace("＃", "");
        var checkbox = document.getElementById('checkbox');
        if (checkbox.checked) {
            //チェックボックスがチェックされていた場合
            window.location.href = '../ichiran/ichiran2.php?serchtext=' + serchText +
                '&currentpage=1&status=checked';
        } else {
            //チェックボックスがチェックされていない場合
            window.location.href = '../ichiran/ichiran2.php?serchtext=' + serchText +
                '&currentpage=1&status=notchecked';
        }
    });

    

    //投稿にカーソルを合わせたら「もっと見る」を表示する
    $('.toukou').hover(function() {
        $('.mypost__texts-more').css('opacity', 1);
    }, function() {
        $('.mypost__texts-more').css('opacity', 0);
    });
    </script>
</main>

</body>

</html>