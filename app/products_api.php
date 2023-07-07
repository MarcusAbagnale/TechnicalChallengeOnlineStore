<?php

require_once 'db_connection.php';

class ProductsAPI {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getProductById($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product not found']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to retrieve product: ' . $e->getMessage()]);
        }
    }

    public function getProducts() {
        try {
            $stmt = $this->pdo->query('SELECT * FROM products');
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($products);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to retrieve products: ' . $e->getMessage()]);
        }
    }

    public function createProduct($data) {
        $name = $data['name'];
        $price = $data['price'];
        $type = $data['type'];

        try {
            $stmt = $this->pdo->prepare('INSERT INTO products (name, price, type) VALUES (?, ?, ?)');
            $stmt->execute([$name, $price, $type]);
            
            $productId = $this->pdo->lastInsertId();
            $this->getProductById($productId);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create product: ' . $e->getMessage()]);
        }
    }

    public function updateProduct($data) {
        $id = $data['id'];
        $name = $data['name'];
        $price = $data['price'];
        $type = $data['type'];

        try {
            $stmt = $this->pdo->prepare('UPDATE products SET name = ?, price = ?, type = ? WHERE id = ?');
            $stmt->execute([$name, $price, $type, $id]);
            
            echo json_encode(['message' => 'Product updated successfully']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    public function deleteProduct($data) {
        $id = $data['id'];

        try {
            $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$id]);
            
            echo json_encode(['message' => 'Product deleted successfully']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }
}


$productsAPI = new ProductsAPI($pdo);

// Verify the request method and execute the corresponding action
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $productsAPI->getProductById($id);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productsAPI->getProducts();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $productsAPI->createProduct($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
    $productsAPI->updateProduct($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $productsAPI->deleteProduct($data);
}
