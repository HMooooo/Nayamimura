<?php
$array = array();
$tagarr = array();
//データベースに接続
require('../dbconnect.php');


//取得する件数nextpageと、そのうち使わない件数passpage（nextpage - passpageを表示する）
$nextpage = $_POST['currentpage'] * 10;
$passpage = ($_POST['currentpage'] - 1) * 10;

//SQL、LIMIT OFFSETは参照するデータが多くなっていくため、投稿数が多くなるたびに処理が遅くなるかも。数値を入れるため、bindParamを使わないといけない？
$records = $db->prepare('SELECT post.id, post.title, post.main, post.time, user.id, user.name, user.icon FROM post, user WHERE post.userid=user.id ORDER BY post.time DESC LIMIT ? OFFSET ?');
$records->bindParam(1, $nextpage, PDO::PARAM_INT);
$records->bindParam(2, $passpage, PDO::PARAM_INT);
$records->execute();

$totalpost = $db->prepare('SELECT COUNT(id) FROM post');
$totalpost->execute();
$totalpostnum = $totalpost->fetch();

//取得したデータを連想配列info_arrに入れる
while ($record = $records->fetch()) {
  $tagarr = [];
  $tags = $db->prepare('SELECT tag.name FROM post, postusetag, tag WHERE post.id=postusetag.postid AND postusetag.tagid=tag.id AND post.id=?');
  $tags->bindParam(1, $record['id'], PDO::PARAM_INT);
  $tags->execute();
  while ($tag = $tags->fetch()) {
    array_push($tagarr, $tag['name']);
  }

  $info_arr = array(
    'toukouid' => $record['id'],
    'title' => $record['title'],
    'main' => $record['main'],
    'time' => $record['time'],
    // 'userid' => $record['userid'],
    'username' => $record['name'],
    'icon' => $record['icon'],
    'tag' => $tagarr,
    'totalpost' => $totalpostnum[0],
  );


  //連想配列info_arrを配列arrayの中にpush
  array_push($array, $info_arr);
}

//JavaScriptに配列arrayを返す
echo json_encode($array, JSON_UNESCAPED_UNICODE);
