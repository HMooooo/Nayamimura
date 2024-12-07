<?php
ini_set('display_errors', 1);
// ボタンが押されたかチェック
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // セッション破棄後の処理（例：リダイレクト）
    header("Location: [URL]/idpasslogin/index.html");
    // セッション変数を全て削除
    $_SESSION = array();

    // セッションを破棄
    session_destroy();


    exit;
}
?>

<?php
// $loginFlag
// 1:ログイン済み
// 0:ログインしていない
if (isset($_SESSION['loginFlag']) && isset($_SESSION['userId'])) {
    $loginFlag = $_SESSION['loginFlag'];
    $userId = $_SESSION['userId'];
} else {
    $loginFlag = 0;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex" />
    <title>Mypage</title>
    <!-- <link rel="stylesheet" href="reset.css"> -->
    <link rel="stylesheet" href="./../stylesheet/ichiran.css">
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <?php

    try {
        $db = new PDO('mysql:dbname=LAA1571836-nayami;host=mysql220.phy.lolipop.lan;charset=utf8', 'LAA1571836', 'it222209');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        // 接続できなかったらエラー表示
        echo 'DB接続エラー!:' . $e->getMessage();
    }


    $records = $db->query('SELECT * FROM users WHERE id = "' . $userId . '"');

    $record = $records->fetch();
    $name = $record['name'];
    $icon = $record['icon'];


    ?>






    <main>
        <header>
        <button class="icon_newpost" onclick="location.href='../toukou/index2.php?currentpage=1'"><strong style="font-size: 30px;">edit</strong><br>投稿する</button>
            <?php
            if ($loginFlag == 1) : ?>
                <form method="POST">
                    <input type="submit"  class="login" value="ログアウト">
                </form>
                <!-- <a class="login" href="http://higashi.fool.jp/nayami/mypage/mypage.php/">マイページ</a> -->
            <?php else : ?>
                <a class="login" href="http://higashi.fool.jp/nayami/idpasslogin/">ログイン</a>
            <?php endif; ?>
            <a class="appName" href="http://higashi.fool.jp/nayami/ichiran/ichiran2.php">
                <h1>なやみむら</h1>
            </a>
            <a class="newpost" href="http://higashi.fool.jp/nayami/toukou/">新規投稿</a>

            <div class="nav">
    
        <!-- ハンバーガーメニューの表示・非表示を切り替えるチェックボックス -->
        <input id="drawer_input" class="drawer_hidden" type="checkbox">
    
        <!-- ハンバーガーアイコン -->
        <label for="drawer_input" class="drawer_open"><span></span></label>
    
        <!-- ハンバーガーメニュー -->
        <nav class="nav_content">
            
            <div class="nav_title" style="margin-top: 60px;">カテゴリー
          <ul class="nav_list">
                <li class="nav_item"><a href="../ichiran/ichiran2.php?currentpage=<?php echo $currentpage; ?>">すべて</a></li>
                <li class="nav_item" style="../ichiran/background-color:#f3f2f2;"><a href='ichiran2.php?category=1&currentpage=<?php echo $currentpage; ?>'>人間関係</a></li>
                <li class="nav_item"><a href='../ichiran/ichiran2.php?category=2&currentpage=<?php echo $currentpage; ?>'>お金</a></li>
                <li class="nav_item" style="../ichiran/background-color:#f3f2f2;"><a href='ichiran2.php?category=3&currentpage=<?php echo $currentpage; ?>'>健康</a></li>
          </ul>
          </div>
          <?php
            if ($loginFlag == 1) : ?>
            <div class="nav_title" style="margin-top: 0;">ログアウト</div>
                <!-- <li><a href="../mypage/mypage.php">マイページ</a></li> -->
            <?php
            else : ?>
             <div class="nav_title" onclick="location.href='../idpasslogin/index.html'">ログイン</div>
                <!-- <li><a href="../idpasslogin/index.html">ログイン</a></li> -->
            <?php
            endif;
            ?> 
          
          
        </nav>
        </header>

        <div class="contents">
            <!-- モーダルウィンドウ -->
            <!-- アイコン　名前の編集画面 -->
            <div id="myModal" class="modal">
                <form action="updateUser.php" method="POST" class="modal-content">
                    <span class="close-btn" onclick="closeModal()">&times;</span>
                    <div class="form-group">
                        <label for="name">アイコン</label>

                        <?php
                        for ($i = 1; $i <= 4; $i++) : ?>
                            <?php
                            if (strcmp($icon, "icon" . $i . ".png") == 0) : ?>
                                <label>
                                    <input type="radio" name="icon" value="icon<?php echo $i; ?>.png" checked>
                                    <img src="../icon/icon<?php echo $i; ?>.png" alt="" class="modal-img">
                                </label>

                            <?php else : ?>
                                <label>
                                    <input type="radio" name="icon" value="icon<?php echo $i; ?>.png">
                                    <img src="../icon/icon<?php echo $i; ?>.png" alt="" class="modal-img">
                                </label>

                        <?php
                            endif;
                        endfor;
                        ?>

                    </div>
                    <div class="form-group">
                        <label>
                            <span class="textbox-003-label">名前</span>
                            <input type="text" class="textbox-003" name="name" placeholder="名前を入力" />
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="button-002" value="保存">
                        <input type="button" class="button-003" onclick="closeModal()" value="キャンセル">
                    </div>
                </form>
            </div>
            <div class="main__head">
                <div class="user__info">
                    <div class="user__icon">
                        <img class="user__icon-img" src="../icon/<?php echo $icon; ?>" alt="">
                    </div>
                    <div class="user__name">
                        <p><?php echo $name; ?></p>
                    </div>

                </div>
                <div class="user__btn">
                    <button onclick="openModal()" class="edit">編集</button>
                </div>
            </div>
            <div class="mypost">

            <div class="category_title">過去の投稿</div>
                <div id="toukou_spase">
                    <?php
                    $contents = $db->query("SELECT * FROM post WHERE userid = '$userId'");
                    $allpost = 0;

                    while ($content = $contents->fetch()) : ?>
                        <?php
                        $postid = $content['id'];
                        $title = $content['title'];
                        $date = $content['time'];
                        $text = $content['text'];

                        ?>

                        <article class="toukou">
                            <a href='../shousai/syousai.php?postid=<?php echo $record['id'];  ?>'>
                                    <h3 class="title"><?php echo $title; ?></h3>
                                    <img src="../icon/<?php echo $record['icon']; ?>" class="icon" alt="">
                                    <p class="username"><?php echo $date; ?></p>
                                <div class="main">
                                <?php 
                                $cut_text = $content['text'];
                                if(mb_strlen($cut_text) > 20){
                                    $cut_text = mb_substr($cut_text, 0, 50)."…";
                                }
                                 echo $cut_text; ?></div>
                            </a>
                            
                            <div class="tagspase" id="tagspase">

                                <?php
                                $tagarr = [];
                                $tags = $db->prepare('SELECT tags.name FROM post, post_tags, tags WHERE post.id=post_tags.post_id AND post_tags.tag_id=tags.id AND post.id=?');
                                $tags->bindParam(1, $postid, PDO::PARAM_INT);
                                $tags->execute();

                                while ($tag = $tags->fetch()) : ?>
                                    <a href='../ichiran/ichiran2.php?serchText=<?php echo $tag['name']; ?> &page=1&status=checked'>
                                        <span class="tag">
                                            <?php echo "#".$tag['name']; ?>
                                        </span>
                                    </a>
                                <?php
                                $allpost = $allpost + 1;
                                endwhile;
                                ?>

                            </div>
                        </article>
                    <?php endwhile;
                    ?>
                </div>
            </div>
        </div>

        <div class="sub">
        <div style="padding:6px;background-color:white;">
            <div style="font-size:25px;text-align: center;color:#4aa067;">カテゴリーを選ぶ</div>
            <ul class="category">
                <li><a href="../ichiran/ichiran2.php">すべて</a></li>
                <?php
                $categories = $db->query('SELECT * FROM category');
                $category_num = 0;
                $i = 1;
 while ($category2 = $categories->fetch()) {
    echo "<li><a href='../ichiran/ichiran2.php?currentpage=1&category=".$i."'>".$category2['category_name']."</a></li>";
        $i =  $i + 1;
 }
                ?>

            </ul>
        </div>
    </div>

    </main>
    <script src="app.js"></script>
    <script>
    var totalpost = <?php echo $allpost ?>;
    

//検索結果が０件だった時に「見つかりませんでした」
if(totalpost === 0){
        $('#toukou_spase').children().remove();
        $('#toukou_spase').append('<p style="display: inline-block;margin-left: 5vw;">投稿がありません</p>');
    }


    //投稿にカーソルを合わせたら「もっと見る」を表示する
    $('.toukou').hover(function() {
        $('.mypost__texts-more').css('opacity', 1);
    }, function() {
        $('.mypost__texts-more').css('opacity', 0);
    });
    </script>
</body>

</html>