<?php
require_once 'CProducts.php';

$productsHandler = new CProducts('localhost', 'root', '', 'test_db');

// Handle AJAX requests
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $productId = $_POST['product_id'];

    if ($action === 'hide') {
        $productsHandler->hideProduct($productId);
    } elseif ($action === 'update_quantity') {
        $quantity = $_POST['quantity'];
        $productsHandler->updateQuantity($productId, $quantity);
    }
    echo json_encode(['success' => true]);
    exit;
}

// Fetch visible products
$products = $productsHandler->getProducts(100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .button { padding: 5px 10px; color: #fff; border: none; cursor: pointer; border-radius: 3px; }
        .hide-btn { background-color: #e74c3c; }
        .quantity-btn { background-color: #3498db; color: #fff; border-radius: 3px; padding: 5px 10px; }
    </style>
</head>
<body>
    <h1>Products Management</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="products-table">
            <?php foreach ($products as $product): ?>
                <tr id="row-<?= $product['ID'] ?>">
                    <td><?= htmlspecialchars($product['PRODUCT_NAME']) ?></td>
                    <td><?= htmlspecialchars($product['PRODUCT_PRICE']) ?></td>
                    <td>
                        <button class="quantity-btn minus-btn" data-id="<?= $product['ID'] ?>">-</button>
                        <span id="quantity-<?= $product['ID'] ?>"><?= htmlspecialchars($product['PRODUCT_QUANTITY']) ?></span>
                        <button class="quantity-btn plus-btn" data-id="<?= $product['ID'] ?>">+</button>
                    </td>
                    <td><?= htmlspecialchars($product['DATE_CREATE']) ?></td>
                    <td>
                        <button class="button hide-btn" data-id="<?= $product['ID'] ?>">Hide</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Handle Hide button
        $(".hide-btn").click(function () {
            const productId = $(this).data("id");
            $.post("products.php", { action: "hide", product_id: productId }, function (response) {
                if (JSON.parse(response).success) {
                    $("#row-" + productId).fadeOut(300, function () {
                        $(this).remove();
                    });
                }
            });
        });

        // Handle Quantity buttons
        $(".plus-btn, .minus-btn").click(function () {
            const button = $(this);
            const productId = button.data("id");
            const quantitySpan = $("#quantity-" + productId);
            let currentQuantity = parseInt(quantitySpan.text());
            const newQuantity = button.hasClass("plus-btn") ? currentQuantity + 1 : Math.max(currentQuantity - 1, 0);

            $.post("products.php", { action: "update_quantity", product_id: productId, quantity: newQuantity }, function (response) {
                if (JSON.parse(response).success) {
                    quantitySpan.text(newQuantity);
                }
            });
        });
    </script>
</body>
</html>
