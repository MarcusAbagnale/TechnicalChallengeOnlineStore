<?php


function makeRequest($method, $url, $data = []) {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($method === 'POST' || $method === 'PUT') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$createData = [
    'name' => 'Product A',
    'price' => 10.99,
    'type' => 'Type A'
];
$response = makeRequest('POST', 'http://localhost/app/products_api.php', $createData);
echo "Create Test: " . $response . "\n";


$createdProduct = json_decode($response, true);
$createdProductId = $createdProduct['id'];

$response = makeRequest('GET', 'http://localhost/app/products_api.php?id=' . $createdProductId);
echo "Read Test: " . $response . "\n";

$updateData = [
    'id' => $createdProductId,
    'name' => 'Updated Product A',
    'price' => 15.99,
    'type' => 'Type B'
];
$response = makeRequest('PUT', 'http://localhost/app/products_api.php', $updateData);
echo "Update Test: " . $response . "\n";


$deleteId = $createdProductId;

if (isset($deleteId)) {
    $response = makeRequest('DELETE', 'http://localhost/app/products_api.php?id=' . $deleteId);
    echo "Delete Test: " . $response . "\n";
} else {
    echo "Delete Test: Invalid request. Missing ID.\n";
}

