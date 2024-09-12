<?php
$host = 'localhost';
$db = 'login';
$user = 'root'; // Substitua pelo seu usuário do MySQL
$pass = '';     // Substitua pela sua senha do MySQL

$dsn = "mysql:host=$host;dbname=$db;charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
