<?php

require_once 'db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = $data['password'];


    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user) {
        echo json_encode($user);

    } else {
            http_response_code(401); 
            echo json_encode(['error' => 'Invalid credentials']);
        }
    }
