<?php
require __DIR__ . '/../vendor/autoload.php';

$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (!strpos($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v);
        if ($v === '') continue;
        putenv("$k=$v");
    }
}

$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE') ?: 'medicalds';
$dbUser = getenv('DB_USERNAME') ?: 'root';
$dbPass = getenv('DB_PASSWORD') ?: '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=$charset";
try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => "DB connect error: " . $e->getMessage()]);
    exit(1);
}

try {
    $stmt = $pdo->query('SELECT * FROM clinical_cases ORDER BY id DESC LIMIT 1');
    $row = $stmt->fetch();
    if (!$row) {
        echo json_encode(["message" => "no rows"]);
    } else {
        echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Query error: " . $e->getMessage()]);
    exit(1);
}
