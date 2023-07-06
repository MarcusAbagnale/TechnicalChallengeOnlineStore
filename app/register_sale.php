<?php

require_once 'db_connection.php';


function registerSale($pdo, $items) {

  $pdo->beginTransaction();

  try {

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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['items'])) {
  $items = $_POST['items'];

  $result = registerSale($pdo, $items);

  if ($result === true) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => $result]);
  }

  exit;
}

$pdo = null;
?>
