<?php


use PHPUnit\Framework\TestCase;

class ProductsApiTest extends TestCase
{
    public function testCreateProduct()
    {
        $createData = [
            'name' => 'Product B',
            'price' => 10.99,
            'type' => 'Type A'
        ];

        $response = $this->makeRequest('POST', 'http://localhost/app/products_api.php', $createData);
        $createdProduct = json_decode($response, true);

        $this->assertEquals(200, $createdProduct['status']);
        $this->assertArrayHasKey('id', $createdProduct);

        return $createdProduct['id'];
    }

    /**
     * @depends testCreateProduct
     */
    public function testReadProduct($productId)
    {
        $response = $this->makeRequest('GET', 'http://localhost/app/products_api.php?id=' . $productId);
        $product = json_decode($response, true);

        var_dump($product);

        $this->assertEquals(200, $product['status']);
        $this->assertEquals('Product A', $product['name']);
        $this->assertEquals(10.99, $product['price']);
        $this->assertEquals('Type A', $product['type']);
    }

    /**
     * @depends testCreateProduct
     */
    public function testUpdateProduct($productId)
    {
        $updateData = [
            'id' => $productId,
            'name' => 'Updated Product A',
            'price' => 15.99,
            'type' => 'Type B'
        ];

        $response = $this->makeRequest('PUT', 'http://localhost/app/products_api.php', $updateData);
        $updatedProduct = json_decode($response, true);

        $this->assertEquals(200, $updatedProduct['status']);
        $this->assertEquals('Updated Product A', $updatedProduct['name']);
        $this->assertEquals(15.99, $updatedProduct['price']);
        $this->assertEquals('Type B', $updatedProduct['type']);
    }

    /**
     * @depends testCreateProduct
     */
    public function testDeleteProduct($productId)
    {
        $response = $this->makeRequest('DELETE', 'http://localhost/app/products_api.php?id=' . $productId);
        $deleteResponse = json_decode($response, true);

        $this->assertEquals(200, $deleteResponse['status']);
    }

    private function makeRequest($method, $url, $data = [])
    {
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
}
