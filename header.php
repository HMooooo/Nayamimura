<?php
if (isset($_GET['currentpage'])) {
    $currentpage = $_GET['currentpage'];
} else {
    $currentpage = 1;
} ?>
<header>

           <?php
            if ($loginFlag == 1) : ?>
             <button class="mypage" onclick="location.href='../mypage/mypage.php?currentpage=1'">マイページ</button>
                <!-- <li><a href="../mypage/mypage.php">マイページ</a></li> -->
            <?php
            else : ?>
             <button class="login" onclick="location.href='../idpasslogin/index.html'">ログイン</button>
                <!-- <li><a href="../idpasslogin/index.html">ログイン</a></li> -->
            <?php
            endif;
            ?> 

	<button class="icon_newpost" onclick="location.href='../toukou/index2.php'">
		<strong style="font-size: 30px;">edit</strong><br>投稿する</button>

	<h1 class="appName" onclick="location.href='../ichiran/ichiran2.php'">なやみむら</h1>

            <button class="newpost" onclick="location.href='../toukou/index.php'">新規投稿</button>

	<div class="nav">
    
        <!-- ハンバーガーメニューの表示・非表示を切り替えるチェックボックス -->
        <input id="drawer_input" class="drawer_hidden" type="checkbox">
    
        <!-- ハンバーガーアイコン -->
        <label for="drawer_input" class="drawer_open"><span></span></label>
    
        <!-- ハンバーガーメニュー -->
        <nav class="nav_content">
            
            <div class="nav_title" style="margin-top: 60px;">カテゴリー
          <ul class="nav_list">
                <li class="nav_item"><a href="../ichiran/ichiran2.php">すべて</a></li>
                 <?php
                $categories = $db->query('SELECT * FROM category');
                $category_num = 0;
                $i = 1;
 while ($category2 = $categories->fetch()) {
    echo "<li class='nav_item'><a href='../ichiran/ichiran2.php?currentpage=1&category=".$i."'>".$category2['category_name']."</a></li>";
        $i =  $i + 1;
 }
                ?>
          </ul>
          </div>

	<?php
            if ($loginFlag == 1) : ?>
            <div class="nav_title" style="margin-top: 0;" onclick="location.href='../mypage/mypage.php?currentpage=1'">マイページ</div>
            <?php
            else : ?>
             <div class="nav_title" onclick="location.href='../idpasslogin/index.html'">ログイン</div>
            <?php
            endif;
            ?> 
       

</header>