<?php
// config/db.php - PDO MySQL Database Connection & Dynamic Auto-Init

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mbc_db');
define('DB_PORT', '3306');

function getDB() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        // First try to connect to the specific database
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // If database doesn't exist, try connecting without dbname and create it
        try {
            $dsnRoot = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
            $pdoRoot = new PDO($dsnRoot, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Connect to newly created database
            $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // Auto-run database seed SQL file
            $sqlFile = __DIR__ . '/../database/mbc_db.sql';
            if (file_exists($sqlFile)) {
                $sqlContent = file_get_contents($sqlFile);
                $pdo->exec($sqlContent);
            }
            return $pdo;
        } catch (PDOException $ex) {
            // Display friendly message if MySQL server is not running
            die("
            <div style='font-family: Arial, sans-serif; background: #fff5f5; border: 2px solid #e53e3e; padding: 25px; border-radius: 10px; max-width: 650px; margin: 50px auto; text-align: center; color: #2d3748;'>
                <h2 style='color: #c53030;'>MySQL Connection Error</h2>
                <p style='font-size: 16px;'>Could not connect to MySQL server at <strong>" . DB_HOST . "</strong>.</p>
                <div style='text-align: left; background: #edf2f7; padding: 15px; border-radius: 6px; font-size: 14px;'>
                    <p><strong>Common Solutions:</strong></p>
                    <ol style='margin-left: 20px; line-height: 1.6;'>
                        <li>Make sure <strong>MySQL / Laragon / XAMPP</strong> service is running on your computer.</li>
                        <li>Open <strong>phpMyAdmin</strong> and create database <code>" . DB_NAME . "</code>.</li>
                        <li>Import the provided <code>database/mbc_db.sql</code> file into phpMyAdmin.</li>
                    </ol>
                </div>
                <p style='font-size: 12px; color: #718096; margin-top: 15px;'>Technical Error: " . htmlspecialchars($ex->getMessage()) . "</p>
            </div>
            ");
        }
    }
}
