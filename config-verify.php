<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .verification-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            padding: 30px;
        }
        .check-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-weight: 500;
        }
        .check-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }
        .check-error {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #f44336;
        }
        .check-icon {
            font-size: 24px;
            margin-right: 15px;
            min-width: 30px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .info-section {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <h1>🔍 Application Verification</h1>
        
        <?php
        // Check PHP Version
        $phpVersion = phpversion();
        $phpOK = version_compare($phpVersion, '7.4.0', '>=');
        ?>
        <div class="check-item <?php echo $phpOK ? 'check-success' : 'check-error'; ?>">
            <span class="check-icon"><?php echo $phpOK ? '✓' : '✗'; ?></span>
            <div>
                <strong>PHP Version:</strong> <?php echo htmlspecialchars($phpVersion); ?>
                <?php echo $phpOK ? '(Supported)' : '(7.4+ Required)'; ?>
            </div>
        </div>

        <?php
        // Check PDO Extension
        $pdoOK = extension_loaded('pdo');
        ?>
        <div class="check-item <?php echo $pdoOK ? 'check-success' : 'check-error'; ?>">
            <span class="check-icon"><?php echo $pdoOK ? '✓' : '✗'; ?></span>
            <div>
                <strong>PDO Extension:</strong> <?php echo $pdoOK ? 'Loaded' : 'Not Found'; ?>
            </div>
        </div>

        <?php
        // Check PDO MySQL Driver
        $pdoMysqlOK = extension_loaded('pdo_mysql');
        ?>
        <div class="check-item <?php echo $pdoMysqlOK ? 'check-success' : 'check-error'; ?>">
            <span class="check-icon"><?php echo $pdoMysqlOK ? '✓' : '✗'; ?></span>
            <div>
                <strong>PDO MySQL Driver:</strong> <?php echo $pdoMysqlOK ? 'Loaded' : 'Not Found'; ?>
            </div>
        </div>

        <?php
        // Check Database Connection
        require_once 'config/Database.php';
        $db = new Database();
        $dbOK = false;
        $dbError = '';
        
        try {
            $db->connect();
            $dbOK = true;
        } catch (Exception $e) {
            $dbError = $e->getMessage();
        }
        ?>
        <div class="check-item <?php echo $dbOK ? 'check-success' : 'check-error'; ?>">
            <span class="check-icon"><?php echo $dbOK ? '✓' : '✗'; ?></span>
            <div>
                <strong>Database Connection:</strong>
                <?php 
                if ($dbOK) {
                    echo 'Connected to crud_app';
                } else {
                    echo 'Connection Failed: ' . htmlspecialchars($dbError);
                }
                ?>
            </div>
        </div>

        <?php
        // Check Products Table
        $tableOK = false;
        $productCount = 0;
        
        if ($dbOK) {
            try {
                $conn = $db->getConnection();
                $stmt = $conn->query("SELECT COUNT(*) FROM products");
                $productCount = $stmt->fetchColumn();
                $tableOK = true;
            } catch (Exception $e) {
                $tableOK = false;
            }
        }
        ?>
        <div class="check-item <?php echo $tableOK ? 'check-success' : 'check-error'; ?>">
            <span class="check-icon"><?php echo $tableOK ? '✓' : '✗'; ?></span>
            <div>
                <strong>Products Table:</strong>
                <?php 
                if ($tableOK) {
                    echo "Found ($productCount products)";
                } else {
                    echo 'Not Found or No Access';
                }
                ?>
            </div>
        </div>

        <?php
        // Check File Permissions
        $filesOK = true;
        $requiredFiles = [
            'config/Database.php',
            'classes/Product.php',
            'api/products.php',
            'css/style.css',
            'js/app.js'
        ];
        
        $missingFiles = [];
        foreach ($requiredFiles as $file) {
            if (!file_exists($file)) {
                $filesOK = false;
                $missingFiles[] = $file;
            }
        }
        ?>
        <div class="check-item <?php echo $filesOK ? 'check-success' : 'check-error'; ?>">
            <span class="check-icon"><?php echo $filesOK ? '✓' : '✗'; ?></span>
            <div>
                <strong>Required Files:</strong>
                <?php 
                if ($filesOK) {
                    echo 'All files present';
                } else {
                    echo 'Missing: ' . htmlspecialchars(implode(', ', $missingFiles));
                }
                ?>
            </div>
        </div>

        <?php
        // Overall Status
        $allOK = $phpOK && $pdoOK && $pdoMysqlOK && $dbOK && $tableOK && $filesOK;
        ?>
        <div class="info-section">
            <h5>Status: <strong><?php echo $allOK ? '✓ Ready to Use' : '✗ Issues Found'; ?></strong></h5>
            <?php if ($allOK): ?>
                <p><strong>Next Steps:</strong></p>
                <ol>
                    <li>Open <a href="index.php" target="_blank" style="color: #0066cc; text-decoration: underline;">index.php</a> to start using the application</li>
                    <li>You can delete this file (config-verify.php) as it's only for setup verification</li>
                </ol>
            <?php else: ?>
                <p><strong>Issues to Fix:</strong></p>
                <ul>
                    <?php if (!$phpOK): ?>
                        <li>Update PHP to version 7.4 or higher</li>
                    <?php endif; ?>
                    <?php if (!$pdoOK || !$pdoMysqlOK): ?>
                        <li>Enable PDO and PDO MySQL extensions in php.ini</li>
                    <?php endif; ?>
                    <?php if (!$dbOK): ?>
                        <li>Verify MySQL is running and database credentials are correct</li>
                    <?php endif; ?>
                    <?php if (!$tableOK): ?>
                        <li>Import database.sql file to create the products table</li>
                    <?php endif; ?>
                    <?php if (!$filesOK): ?>
                        <li>Ensure all project files are in the correct directories</li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
