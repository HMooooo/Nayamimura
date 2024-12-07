<?php
// error_reporting(-1);
// ini_set('display_errors', 'On');
// $userId = $_POST['userId'];
$email = $_POST['email'];
$password = $_POST['password'];
// ここでデータベースや他の認証システムとの照合を行う
// 例: if (validateUser($userId, $password)) { ... }
//require("../dbconnect.php");
try {
    $db = new PDO('mysql:dbname=LAA1571836-nayami;host=mysql220.phy.lolipop.lan;charset=utf8', 'LAA1571836', 'it222209');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // 接続できなかったらエラー表示
    echo 'DB接続エラー!:' . $e->getMessage();
}
// データベース内のユーザーテーブルでユーザーIDとパスワードを検証
$sql = "SELECT COUNT(*) FROM users WHERE email = '$email'";
$result = $db->query($sql);
if ($result) {
    //ユーザーidを取得
    $sql = "SELECT id,password FROM users WHERE email = '$email'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $userData = $stmt->fetch();
    if(password_verify($_POST['password'], $userData['password'])){
    //loginFlag 1:ログイン済み 　その他：ログインしていない
    $_SESSION['loginFlag'] = 1;
    $_SESSION['userId'] = $userData['id'];
    header("location:../ichiran/ichiran2.php?currentpage=1");
    exit;
    }
} else {
    echo "Login Failed";
}