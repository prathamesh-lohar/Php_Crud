<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>CRUD App Diagnostic Test</h2>";

echo "<h3>1. PHP Version</h3>";
echo "PHP Version: " . phpversion() . "<br>";

echo "<h3>2. PDO Check</h3>";
if (extension_loaded('pdo')) {
    echo "✓ PDO extension is loaded<br>";
} else {
    echo "✗ PDO extension NOT loaded<br>";
}

if (extension_loaded('pdo_mysql')) {
    echo "✓ PDO MySQL driver is loaded<br>";
} else {
    echo "✗ PDO MySQL driver NOT loaded<br>";
}

echo "<h3>3. Files Check</h3>";
$files = [
    'config/Database.php',
    'classes/Product.php',
    'api/products.php',
    'css/style.css',
    'js/app.js'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✓ $file exists<br>";
    } else {
        echo "✗ $file NOT found<br>";
    }
}

echo "<h3>4. Database Connection Test</h3>";
try {
    require_once 'config/Database.php';
    $db = new Database();
    $conn = $db->connect();
    echo "✓ Database connected successfully<br>";
    
    require_once 'classes/Product.php';
    $product = new Product($conn);
    $products = $product->getAll();
    echo "✓ Products table accessible<br>";
    echo "Found " . count($products) . " products in database<br>";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

echo "<h3>5. API Test</h3>";
$apiUrl = 'http://localhost/new/api/products.php?action=read';
echo "API URL: <a href='$apiUrl' target='_blank'>$apiUrl</a><br>";

echo "<h3>6. Main App</h3>";
$appUrl = 'http://localhost/new/';
echo "App URL: <a href='$appUrl' target='_blank'>$appUrl</a><br>";

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Make sure Apache and MySQL are running in XAMPP Control Panel</li>";
echo "<li>Check that database 'crud_app' exists in phpMyAdmin</li>";
echo "<li>Import the database.sql file if table doesn't exist</li>";
echo "<li>Click the App URL above to test the application</li>";
echo "</ol>";
?>
