<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$naran_db = 'sistem_repositori_jurnal';
$naran_uzuáriu = 'root';
$parola = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$naran_db;charset=utf8mb4", $naran_uzuáriu, $parola);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Ligasaun falla: " . $e->getMessage());
    die("error: " . $e->getMessage());
}

// Konfigurasaun URL baze
define('BASE_URL', 'http://localhost/journal-repository/');

// Halo $pdo sai disponivel ba globál
$GLOBALS['pdo'] = $pdo;
?>
