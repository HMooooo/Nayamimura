<?php require('../dbconnect.php'); ?>

<?php $categories = $db->query('SELECT * FROM category') ?>
 <?php while ($category = $categories->fetch()) {

        // $id = $category['category_id'];
        $name = (string)$category['category_name'];
        $str_id = (string)$category['category_id'];
        $check_id = "checkbox_" . $str_id;

        // var_dump($id);
        // echo "<br>";
        // var_dump($name);
        echo "<br>";

        echo '<input type="checkbox" id="' . $check_id . '" name="category[]" class="categoryCheckbox" value="' . $str_id . '" />';
        echo '<label for="' . $str_id . '">  ' . $name . '</label>';
    }
    ?>