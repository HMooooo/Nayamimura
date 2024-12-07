<?php
header('Location: mypage.php');
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
    echo "ログインしていません";
}

$name = $_POST["name"];
$icon = $_POST["icon"];
try {
    $db = new PDO('mysql:dbname=LAA1571836-nayami;host=mysql220.phy.lolipop.lan;charset=utf8', 'LAA1571836', 'it222209');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // 接続できなかったらエラー表示
    echo 'DB接続エラー!:' . $e->getMessage();
}

try {
    $sql = 'UPDATE users SET name = "' . $name . '" , icon = "' . $icon . '" WHERE id = "' . $userId . '"';
    echo $sql;
    $stmt = $db->prepare($sql);

    $stmt->execute();
    exit();
} catch (PDOException $e) {
    // エラー発生
    echo $e->getMessage();
} finally {
    // DB接続を閉じる
    $pdo = null;
}
