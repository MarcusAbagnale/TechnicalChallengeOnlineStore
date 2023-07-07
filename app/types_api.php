<?php

require_once 'db_connection.php';

class TypesAPI {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getTypeById($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM type WHERE name = (select type from products where id = ?) ');
            $stmt->execute([$id]);
            $type = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($type);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to retrieve type: ' . $e->getMessage()]);
        }
    }

    public function getTypes() {
        try {
            $stmt = $this->pdo->query('SELECT * FROM type');
            $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($types);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to retrieve types: ' . $e->getMessage()]);
        }
    }

    public function createType($data) {
        $name = $data['name'];
        $tax = $data['tax'];

        try {
            $stmt = $this->pdo->prepare('INSERT INTO type (name, tax) VALUES (?, ?)');
            $stmt->execute([$name, $tax]);
            $typeId = $this->pdo->lastInsertId();
            $stmt = $this->pdo->query("SELECT * FROM type WHERE id = $typeId");
            $type = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($type);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create type: ' . $e->getMessage()]);
        }
    }

    public function updateType($data) {
        $id = $data['id'];
        $name = $data['name'];
        $tax = $data['tax'];

        try {
            $stmt = $this->pdo->prepare('UPDATE type SET name = ?,  tax = ? WHERE id = ?');
            $stmt->execute([$name,  $tax, $id]);
            echo json_encode(['message' => 'Type updated successfully']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update type: ' . $e->getMessage()]);
        }
    }

    public function deleteType($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM type WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['message' => 'Type deleted successfully']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }
}


$typesAPI = new TypesAPI($pdo);

// Handle the API request based on the HTTP method
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $typesAPI->getTypeById($id);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $typesAPI->getTypes();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $typesAPI->createType($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $typesAPI->updateType($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $typesAPI->deleteType($data);
}

?>
