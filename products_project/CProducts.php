<?php
class CProducts {
    private $conn;

    public function __construct($host, $user, $password, $database) {
        $this->conn = new mysqli($host, $user, $password, $database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Fetch visible products
    public function getProducts($limit = 10) {
        $stmt = $this->conn->prepare("SELECT * FROM Products WHERE IS_HIDDEN = 0 ORDER BY DATE_CREATE DESC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Hide a product
    public function hideProduct($productId) {
        $stmt = $this->conn->prepare("UPDATE Products SET IS_HIDDEN = 1 WHERE ID = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
    }

    // Update product quantity
    public function updateQuantity($productId, $quantity) {
        $stmt = $this->conn->prepare("UPDATE Products SET PRODUCT_QUANTITY = ? WHERE ID = ?");
        $stmt->bind_param("ii", $quantity, $productId);
        $stmt->execute();
    }
}
?>
