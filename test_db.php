<?php
$host = '[2406:da18:243:7424:8c60:43b1:d5e5:c0ed]';
$port = '5432';
$dbname = 'postgres';
$user = 'postgres';
$password = 'Arulpusing12#';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Koneksi Berhasil!\n";
} catch (PDOException $e) {
    echo "Koneksi Gagal: " . $e->getMessage() . "\n";
}
