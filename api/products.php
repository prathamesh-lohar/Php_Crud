<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/Database.php';
require_once '../classes/Product.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

$database = new Database();
$db = $database->connect();
$product = new Product($db);

try {
    switch($action) {
        case 'read':
            $products = $product->getAll();
            echo json_encode([
                'success' => true,
                'data' => $products
            ]);
            break;
            
        case 'read_by_category':
            $categoryCounts = $product->getByCategory();
            echo json_encode([
                'success' => true,
                'data' => $categoryCounts
            ]);
            break;
            
        case 'get':
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id <= 0) {
                throw new Exception('Invalid product ID');
            }
            $productData = $product->getById($id);
            if ($productData) {
                echo json_encode([
                    'success' => true,
                    'data' => $productData
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Product not found'
                ]);
            }
            break;
            
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method. POST required.');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                throw new Exception('Invalid JSON data');
            }
            
            $product->name = isset($data['name']) ? trim($data['name']) : '';
            $product->price = isset($data['price']) ? floatval($data['price']) : 0;
            $product->category = isset($data['category']) ? trim($data['category']) : '';
            $product->description = isset($data['description']) ? trim($data['description']) : '';
            
            if ($product->create()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Product created successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create product'
                ]);
            }
            break;
            
        case 'update':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method. POST required.');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                throw new Exception('Invalid JSON data');
            }
            
            $product->id = isset($data['id']) ? intval($data['id']) : 0;
            $product->name = isset($data['name']) ? trim($data['name']) : '';
            $product->price = isset($data['price']) ? floatval($data['price']) : 0;
            $product->category = isset($data['category']) ? trim($data['category']) : '';
            $product->description = isset($data['description']) ? trim($data['description']) : '';
            
            if ($product->id <= 0) {
                throw new Exception('Invalid product ID');
            }
            
            if ($product->update()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Product updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update product'
                ]);
            }
            break;
            
        case 'delete':
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id <= 0) {
                throw new Exception('Invalid product ID');
            }
            
            if ($product->delete($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete product'
                ]);
            }
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action'
            ]);
    }
} catch(Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
