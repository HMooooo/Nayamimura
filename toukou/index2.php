<?php 
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
    echo "ユーザーID:" . $userId;
} else {
    if (isset($_SESSION['loginFlag'])) {
        echo "loginflag:あり";
    } else if (isset($_SESSION['userId'])) {
        echo "userId:あり";
    }

    $loginFlag = 0;
  //  echo "ログインしていません";
}

require('../dbconnect.php'); ?>

<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../stylesheet/ichiran.css" type="text/css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規投稿</title>
</head>

<body>
<?php include('../header.php'); ?>
    <!-- 戻るボタン -->
    <input class="back" type="button" onclick="history.back()" value="arrow_back">
    
<main>
    <div class="contents">
    <h3 class="category_title" style="font-size: 25px;">投稿する</h3>
        <form id="form" action="./post.php" method="post">

            <h4 class="category_title_sub">タイトル</h4>
            <input type="text" id="title" name="title" placeholder="タイトルを入力" required style="display: block; width: 90%; font-size: 25px; margin: auto;">

            <h4 class="category_title_sub">カテゴリ</h4>
            <div class="categoryCheckbox">
                <?php require('input_category.php'); ?>
            </div>

            <h4 class="category_title_sub">本文</h4>
            <div class="honbun">
                <textarea id="text" name="text" rows="4" cols="40" placeholder="あなたの悩みをおしえて" required style="display: block; width: 90%; font-size: 25px; margin: auto;"></textarea>
            </div>

            <h4 class="category_title_sub">タグ</h4>
            <p class="tag" id="tag_list"></p>
            <br>
            <!-- タグ追加 -->
            <div style="width: 80%; font-size: 25px; margin: auto;">
                <p id="tagPrepare"></p>
                <input type="text" id="tags" name="tags" placeholder="タグを入力" style="display: inline;  font-size: 25px; width: 95%;">
                <input type="button" class="tagaddbtn" value="追加" onclick="addTag()" >
            </div>
            <!--クリック時に上にタグ表示  -->
            <script>
                let tagArray = [];
                //1回でもタグ追加ボタンを押しているか
                let addedBool = false;

                function addTag() {
                    // 入力したタグを取得
                    let inputTag = document.getElementById('tags').value;
                    // タグ表示欄を取得
                    let tagList = document.getElementById('tag_list');
                    // 表示
                    tagList.after("#" + inputTag + "  ");
                    // let str = '<input type="hidden" id="tagArray" name="tagArray[]" value="' + inputTag + '" />';

                    //配列にしてnullとか除去しよう
                    //配列に最新のタグを追加
                    tagArray.push(inputTag);
                    //nullなどを削除
                    for (let i = 0; i < tagArray.length; i++) {
                        if (tagArray[i] === null || tagArray[i] === undefined || tagArray[i] === "" || tagArray[i] === "") {
                            tagArray.splice(i, 1); // 削除
                            if (i > 0) i--;
                        }
                    }
                    console.log(tagArray);
                    let setTagArray = new Set(tagArray);
                    console.log(setTagArray);

                    var input = document.createElement("input");
                    input.type = "hidden"
                    input.name = 'tagData[]';
                    input.value = inputTag;
                    document.getElementById('tagPrepare').appendChild(input);

                    //入力欄をクリア
                    document.getElementById('tags').value = '';
                    //追加ボタンを押した判定をして入力漏れを防止
                    addedBool = true;
                }

                function isCheck() {
                    let arr_checkBoxes = document.getElementsByClassName("categoryCheckbox");
                    let count = 0;
                    for (let i = 0; i < arr_checkBoxes.length; i++) {
                        if (arr_checkBoxes[i].checked) {
                            count++;
                        }
                    }
                    if (addedBool === false) {
                        window.alert("タグを一つ以上追加してください");
                    } else if (count < 0) {
                        window.alert("カテゴリを1つ以上選択してください。");
                    } else {
                        document.getElementById("form").submit();
                    };

                }
            </script>
            <br>
        </form>
        <p><input type="submit" class="postbtn" value="投稿" onclick="isCheck()"></p>
    </div>
    <div class="sub">
        <div style="padding:6px;background-color:white;">
        <div style="font-size:25px;text-align: center;color:#4aa067;">カテゴリーを選ぶ</div>
        <ul class="category">
            <li><a href="../ichiran/ichiran2.php?currentpage=1">すべて</a></li>
            <?php
                $categories = $db->query('SELECT * FROM category');
                $category_num = 0;
                $i = 1;
 while ($category2 = $categories->fetch()) {
    echo "<li><a href='ichiran2.php?category=1&currentpage=".$i."'>".$category2['category_name']."</a></li>";
        $category_num =  $category_num + 1;
        
 }
                ?>
        </ul>
    </div>
            </main>
</body>

</html>