<?php require('../dbconnect.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../stylesheet/ichiran.css" type="text/css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="robots" content="noindex" />
</head>

<body>
    <?php
    session_start();
    // $loginFlag
    // 1:ログイン済み
    // 0:ログインしていない
    if (isset($_SESSION['loginFlag']) && isset($_SESSION['userId'])) {
        $loginFlag = $_SESSION['loginFlag'];
        $userId = $_SESSION['userId'];
    } else {
        $loginFlag = 0;
    }


    $post = $db->prepare('SELECT * FROM post WHERE id=?');
    $post->execute(array($_GET['postid']));
    $toukou = $post->fetch();
    $post_user_id = $toukou['userid'];
    $users = $db->prepare('SELECT * FROM users WHERE id=?');

    $users->execute(array($post_user_id));
    // 投稿のユーザー情報
    $post_user = $users->fetch();
    $post_username = $post_user['name'];
    $post_usericon = $post_user['icon'];



    //既存のコメントを取得
    $comments = $db->prepare('SELECT user_id, text, time FROM comments WHERE target_id=?');
    $comments->bindParam(1, $_GET['postid']);
    $comments->execute();

    // tag
    $tags = $db->prepare('SELECT tag_id FROM post_tags WHERE post_id=?');
    $tags->bindParam(1, $_GET['postid']);
    $tags->execute();

    $tagsArray = $tags->fetchAll(PDO::FETCH_COLUMN);

    // 返信者投稿済みのたぐIDを取得
    $userId = $_SESSION['userId'];

    // ユーザidを取得し返信権利があるかどうか確認
    $postedTagIDStmt  = $db->prepare('SELECT tag_id FROM user_tags WHERE user_id=?');
    $postedTagIDStmt->bindParam(1, $userId);
    $postedTagIDStmt->execute();

    $postedTagsArray = $postedTagIDStmt->fetchAll(PDO::FETCH_COLUMN);


    $usernameStmt = $db->prepare('SELECT name FROM users WHERE id=?');
    $usernameStmt->bindParam(1, $userId);
    $usernameStmt->execute();
    $usernameResult = $usernameStmt->fetch();
    $username = $usernameResult['name'];




    //元投稿のID
    $target_id = $toukou['id'];
    //返信するユーザのID


    // $my_id=$返信するユーザのID
    $title = $toukou['title'];
    $time = $toukou['time'];
    // カテゴリidに対応したカテゴリ名を別テーブルから参照する処理を作る
    // $category = $toukou['category'];
    $text = $toukou['text'];






    ?>

    <?php include('../header.php'); ?>

    <button id="back" type="button" class="back" onclick="history.back()">arrow_back</button>
    <div class="shousai">

        <div class="moto_post">
            <h4 class="s_title">
                <?php print($title); ?>
            </h4>
            <img  class="s_icon" src="../icon/<?php echo $post_usericon; ?>">
            <!-- <img class="s_icon" src="./../stylesheet/icon1.jpg"> -->
            <div class="s_div">
                <p class="s_username">
                    <?php print($post_username); ?>
                </p><br>
                <p class="s_time">
                    <?php print($time); ?>
                </p>
            </div>

            <p class="s_main">
                <?php print($text); ?>
            </p>

            <p class="tags">
                <?php
                print('<p class="tagicon">local_offer</p>');

            foreach ($tagsArray as $tag) {
                $tagNames = $db->prepare('SELECT name FROM tags WHERE id=?');
                $tagNames->bindParam(1, $tag);
                $tagNames->execute();
                $tagName = $tagNames->fetch();
                print("  #".$tagName['name']);
            }
            ?>
            </p>
        </div>


        <form action="comment.php" method="post">

            <!-- 返信先IDを渡す -->
            <input type="hidden" name="target_id" value="<?php print($target_id); ?>">

            <!-- 返信元ユーザIDを渡す -->
            <input type="hidden" name="my_id" value="<?php print($my_id); ?>">


            <?php
        // 配列同士を比較し、差異がないか確認
        // if (empty(array_diff($tagsArray, $postedTagsArray)) && empty(array_diff($postedTagsArray, $tagsArray))) {
        if (count(array_intersect($postedTagsArray, $tagsArray)) == count($tagsArray)) {
            echo '<div class="comment-add">
            <textarea name="comment" id="comment" rows="4" cols="40" placeholder="コメントを記入" class="s_comment"></textarea>
        </div>';
            $submitButton = '<input type="submit" value="返信" class="replybtn">';
            echo $submitButton;
        } else {
            // 一部の値が異なる場合の処理
            $alert = "<script type='text/javascript'>alert('同じタグを投稿することで、返信が可能になります');</script>";
            echo $alert;
        } ?>
        </form>

        <p class="category_title_sub">コメント一覧</p>
        <div class="comment-list">
            
            <?php while ($comment = $comments->fetch()) : ?>
            <?php
            $usersStmt = $db->prepare('SELECT * FROM users WHERE id=?');
            $usersStmt->bindParam(1, $comment['user_id']);
            $usersStmt->execute();
            $userResult = $usersStmt->fetch();
            $comm_username = $userResult['name'];
            $comm_usericon = $userResult['icon']
            ?>
            <img src="../icon/<?php echo $comm_usericon; ?>" class="icon" alt="">
            <!-- <img class="icon" src="./../stylesheet/icon1.jpg"> -->
            <div class="s_div">
                <p class="username" style="margin-top: 0;margin-bottom: 0;">
                    <?php print($comm_username); ?>
                </p>
                <br>
                <p class="time">
                    <?php print($comment['time']); ?>
                </p>
                <br>
                <?php print($comment['text']); ?>


            </div>

            <hr>
            <?php endwhile; ?>


        </div>
    </div>

</body>

</html>