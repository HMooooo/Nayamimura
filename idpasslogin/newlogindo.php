<?php
error_reporting(-1);
ini_set('display_errors','On');
header("location:https://higashi.fool.jp/nayami/ichiran/ichiran2.php");
// $userid =  $_POST['userId'];
$password = $_POST['password'];
$hashedPass = password_hash($password, PASSWORD_DEFAULT);
// a
$email = $_POST['email'];
require("../dbconnect.php");
$sql = "INSERT INTO `users` (`id`, `password`, `email`, `icon`, `name` ) VALUES (NULL, '$hashedPass' , '$email', './icon1.png', '名無し' )";
$stmt = $db->query($sql);
//ユーザーidを取得
$sql = "SELECT id FROM users WHERE email = '$email'";
$stmt = $db->prepare($sql);
$stmt->execute();
$id = $stmt->fetchColumn();
//loginFlag 1:ログイン済み 　その他：ログインしていない
$_SESSION['loginFlag'] = 1;
$_SESSION['userId'] = $id;

// echo '<script src="ichirango.js"></script>';
