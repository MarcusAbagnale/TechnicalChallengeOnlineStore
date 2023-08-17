<?php

require_once 'db_connection.php';

class ProductsAPI {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getProductById($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * from products where id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $response = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'type' => $product['type'],
                    'status' => 200
                ];
                echo json_encode($response);
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
            
            $response = [
                'products' => $products,
                'status' => 200
            ];
            echo json_encode($response);
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
            $stmt = $this->pdo->query("SELECT * FROM products WHERE id = $productId");
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            $response = [
                'id' => $productId,
                'name' => $product['name'],
                'price' => $product['price'],
                'type' => $product['type'],
                'status' => 200
            ];
            echo json_encode($response);
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
            $stmt = $this->pdo->prepare('UPDATE products SET name = ?,  price = ?, type = ? WHERE id = ?');
            $stmt->execute([$name,  $price, $type, $id]);
            $response = [
                'status' => 200,
                'message' => 'Product updated successfully'
            ];
            echo json_encode($response);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    public function deleteProduct($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $response = [
                'status' => 200,
                'message' => 'Product deleted successfully'
            ];
            echo json_encode($response);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }
}

$productsAPI = new ProductsAPI($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $productsAPI->getProductById($id);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productsAPI->getProducts();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productsAPI->createProduct($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productsAPI->updateProduct($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $productsAPI->deleteProduct($id);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}

?>
