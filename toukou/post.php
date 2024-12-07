<?php
// 投稿一覧画面に遷移する
header("Location: [URL]/ichiran/ichiran2.php?currentpage=1");
require('../dbconnect.php');
$userid = $_SESSION['userId'];


//保存
//カテゴリはpostテーブルに保持しない
$sql  = 'INSERT INTO post set title=?, text=?,userid=?,time=NOW() ';
$stmt = $db->prepare($sql);
$stmt->execute(array($_POST['title'], $_POST['text'], $_SESSION['userId']));
// echo '登録されました';

//最後に追加したレコードの主キー
$lastInsertedPrimaryKey = $db->lastInsertId();

//カテゴリ保存
$sql  = 'INSERT INTO post_category set post_id=? ,category_id=?';

$stmt = $db->prepare($sql);
$checkedCategory = $_POST['category'];
foreach ((array)$checkedCategory as $postCategory) {
    $stmt->execute(array($lastInsertedPrimaryKey, $postCategory));
}


//タグ保存 
$originalTagData = (array)$_POST['tagData'];
$inputTags = array_unique($originalTagData);




foreach ($inputTags as $inputTag) {
    //ユーザが指定したタグが存在するか確認
    $searchTagSQL = "SELECT id FROM tags WHERE name=" . "'" . "$inputTag" . "'";
    // var_dump($searchTagSQL);
    $searchStmt = $db->prepare($searchTagSQL);
    // $searchStmt->bindValue(1, $inputTag);
    $result = $searchStmt->execute();
    $fetched = $searchStmt->fetch();

    // print($fetched['id']);
    // print("<br>");
    // print("\n");
    // var_dump($result);
    $rowCount = $searchStmt->rowCount();
    // var_dump($rowCount);

    // //結果の行数を取得
    // $num_rows = mysqli_num_rows($tagID);

    //タグリストにそのタグが存在しないなら
    if ($result == true && $rowCount == 0) {
        //新しいタグidを設定
        $tagSQL = 'INSERT INTO tags set name=?';
        $tagSTMT = $db->prepare($tagSQL);
        $tagSTMT->execute(array($inputTag));
        // 設定したタグidとpost_idを結びつける
        $lastInsertedTagID = $db->lastInsertId();
        $post_tag_sql  = 'INSERT INTO post_tags set post_id=?,tag_id=? ';
        $post_tag_stmt = $db->prepare($post_tag_sql);
        $post_tag_stmt->execute(array($lastInsertedPrimaryKey, $lastInsertedTagID));

        //ユーザidとタグidの結びつけ
        $userID = $_SESSION['userId']; //ここにユーザID取得処理を記述(結合)
        $user_tag_sql = 'INSERT INTO user_tags set user_id=?, tag_id=?';
        $user_tag_stmt = $db->prepare($user_tag_sql);
        $user_tag_stmt->execute(array($userID, $lastInsertedTagID));
    } else {
        //すでに同じタグがある場合
        // $searchExistTagSQL = "SELECT id FROM tags WHERE name=";
        $tagID = $fetched['id'];

        var_dump($tagID);
        // foreach ($searchStmt as $tagRow) {
        //     $tagRow = $tagID;
        // }

        $post_tag_sql  = 'INSERT INTO post_tags set post_id=?,tag_id=? ';
        $post_tag_stmt = $db->prepare($post_tag_sql);
        $post_tag_stmt->execute(array($lastInsertedPrimaryKey, $tagID));

        //ユーザidとタグidの結びつけ
        $userID = $userid; //ここにユーザID取得処理を記述(結合)
        $user_tag_sql = 'INSERT INTO user_tags set user_id=?, tag_id=?';
        $user_tag_stmt = $db->prepare($user_tag_sql);
        $user_tag_stmt->execute(array($userID, $tagID));
    }
}
// 投稿一覧画面に遷移する
exit();
