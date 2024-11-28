<?php
require 'CProducts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $products = new CProducts('localhost', 'root', '', 'test_db');

    if ($products->hideProduct($id)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
