<?php require('../dbconnect.php'); ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php $post = $db->query('SELECT * FROM post') ?>

    <article>
        <?php while ($toukou = $post->fetch()) : ?>
            <p>
                <?php $id = $toukou['id']; ?>
                <a href="syousai.php?id=<?php print($id); ?>">
                    <?php print($toukou['title']); ?>
                </a>
            </p>
            <hr>
        <?php endwhile; ?>
    </article>
</body>

</html>