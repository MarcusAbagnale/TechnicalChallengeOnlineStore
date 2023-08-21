<?php

require_once 'db_connection.php';

function registerSale($pdo, $items) {
  try {
    $pdo->beginTransaction();

    $query = "INSERT INTO sales (date) VALUES (CURRENT_TIMESTAMP) RETURNING id";
    $stmt = $pdo->query($query);
    $saleId = $stmt->fetchColumn();

    $insertItemQuery = "INSERT INTO sale_items (sale_id, product_id, quantity, subtotal) VALUES (:sale_id, :product_id, :quantity, :subtotal)";
    $insertItemStmt = $pdo->prepare($insertItemQuery);

    foreach ($items as $item) {
      $productId = $item['product']['id'];
      $quantity = $item['quantity'];
      $subtotal = $item['subtotal'];

      $insertItemStmt->execute([
        'sale_id' => $saleId,
        'product_id' => $productId,
        'quantity' => $quantity,
        'subtotal' => $subtotal
      ]);
    }

    $pdo->commit();

    return true;
  } catch (Exception $e) {
    $pdo->rollBack();

    return $e->getMessage();
  }
}

$data = json_decode(file_get_contents("php://input"), true); // Recebe os dados do corpo da requisição POST
$items = $data['items']; // Obtém o array de itens do corpo da requisição

var_dump($items);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


  $result = registerSale($pdo, $items); // Chama a função de registro de venda

  if ($result === true) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => $result]);
  }

  exit;
}
?>
