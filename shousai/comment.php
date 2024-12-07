<?php
$target = $_POST['target_id'];
header("Location: syousai.php?postid=$target");
require('../dbconnect.php');

$comment = $_POST['comment'];
$userId = $_SESSION['userId'];

//ユーザID周りの仕様が確定したらユーザidも合わせて格納する とりあえず9999でテスト
$sql  = 'INSERT INTO comments SET target_id=?, user_id=?, text=?, time=NOW()';;
$stmt = $db->prepare($sql);


$stmt->bindParam(1, $target, PDO::PARAM_INT);
$stmt->bindParam(2, $userId, PDO::PARAM_INT);
$stmt->bindParam(3, $comment, PDO::PARAM_STR);
$stmt->execute();



exit();
