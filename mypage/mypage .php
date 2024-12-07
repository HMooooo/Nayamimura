<?php
session_start();
// $loginFlag
// 1:ログイン済み
// 0:ログインしていない
if (isset($_SESSION['loginFlag']) && isset($_SESSION['userId'])) {
    $loginFlag = $_SESSION['loginFlag'];
    $userId = $_SESSION['userId'];
    echo "ユーザーID:" . $userId;
} else {
    $loginFlag = 0;
    echo "ログインしていません";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mypage</title>
    <!-- <link rel="stylesheet" href="reset.css"> -->
    <link rel="stylesheet" href="./../stylesheet/ichiran.css">
    <link rel="stylesheet" href="style.css">


    <script src="app.js"></script>

</head>

<body>

    <?php //include('../header.php'); 
    ?>
    <?php

    try {
        $db = new PDO('mysql:dbname=LAA1571836-nayami;host=mysql220.phy.lolipop.lan;charset=utf8', 'LAA1571836', 'it222209');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        // 接続できなかったらエラー表示
        echo 'DB接続エラー!:' . $e->getMessage();
    }


    $records = $db->query('SELECT * FROM users WHERE id = "'. $userId .'"');

    $record = $records->fetch();
    $name = $record['name'];
    $icon = $record['icon'];


    ?>






    <main class="main">
        <header>
            <button class="login">ログイン</button>
            <h1 class="appName">アプリ名</h1>
            <button class="newpost">新規投稿</button>
        </header>

        <!-- <button class="header__btn" onclick="logout()">
            <p class="header__btn-text">ログアウト</p>
        </button> -->

        <div class="main__inner">
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
                                    <img src="icon<?php echo $i; ?>.png" alt="" class="modal-img">
                                </label>

                            <?php else : ?>
                                <label>
                                    <input type="radio" name="icon" value="icon<?php echo $i; ?>.png">
                                    <img src="icon<?php echo $i; ?>.png" alt="" class="modal-img">
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
                        <img class="user__icon-img" src="<?php echo $icon; ?>" alt="">
                    </div>
                    <div class="user__name">
                        <p><?php echo $name; ?></p>
                    </div>

                </div>
                <div class="user__btn">
                    <button onclick="openModal()">編集</button>
                </div>
            </div>
            <div class="mypost">
                <div class="mypost__inner">
                    <?php
                    $contents = $db->query("SELECT * FROM post WHERE userid = '$userId'");

                    while ($content = $contents->fetch()) : ?>
                        <?php
                        $date = $content['time'];
                        $text = $content['main'];
                        ?>
                        <article class="mypost__cards">
                            <div class="mypost__title">
                                <h3>タイトル</h3>
                            </div>
                            <time><?php echo $date; 
                                    ?></time>
                            <div class="mypost__texts"><?php echo $text; ?></div>
                            <div class="mypost__texts-more">
                                <p>もっと見る▼</p>
                            </div>
                            <hr class="mypost__hr">
                            <div class="mypost__tag">#タグ１　#タグ２</div>
                        </article>
                    <?php endwhile;
                    ?>




                </div>
            </div>
        </div>

    </main>

</body>

</html>