<?php
require 'CProducts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']); // Product ID
    $quantity = intval($_POST['quantity']); // New quantity

    // Initialize CProducts with database connection
    $products = new CProducts('localhost', 'root', '', 'test_db');

    // Update quantity using the method from CProducts
    $products->updateQuantity($id, $quantity);

    echo "success";
}
?>
