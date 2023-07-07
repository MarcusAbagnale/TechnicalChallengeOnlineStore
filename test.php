<?php

// Function to make an HTTP request and return the response
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

// Test the Create operation
$createData = [
    'name' => 'Product A',
    'price' => 10.99,
    'type' => 'Type A'
];
$response = makeRequest('POST', 'http://localhost/app/products_api.php', $createData);
echo "Create Test: " . $response . "\n";

// Extract the created product's ID from the response
$createdProduct = json_decode($response, true);
$createdProductId = $createdProduct['id'];

// Test the Read operation
$response = makeRequest('GET', 'http://localhost/app/products_api.php?id=' . $createdProductId);
echo "Read Test: " . $response . "\n";

// Test the Update operation
$updateData = [
    'id' => $createdProductId,
    'name' => 'Updated Product A',
    'price' => 15.99,
    'type' => 'Type B'
];
$response = makeRequest('PUT', 'http://localhost/app/products_api.php', $updateData);
echo "Update Test: " . $response . "\n";

// Test the Delete operation
$deleteData = [
    'id' => $createdProductId
];
$response = makeRequest('DELETE', 'http://localhost/app/products_api.php', $deleteData);
echo "Delete Test: " . $response . "\n";
