<!-- <?php
        try {
            $db = new PDO('mysql:dbname=nayamidb;host=127.0.0.1;charset=utf8', 'root', '');
        } catch (PDOException $e) {
            echo 'DB接続エラー :  ' . $e->getMessage();
        } ?>-->

<?php
try {
    $db = new PDO('mysql:dbname=LAA1571836-nayami;host=mysql220.phy.lolipop.lan;charset=utf8', 'LAA1571836', 'it222209');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // 接続できなかったらエラー表示
    echo 'DB接続エラー!:' . $e->getMessage();
} ?>