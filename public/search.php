<?php
header('Content-Type: application/json');

// Incluindo o arquivo de conexão
require_once '../db/db_connect.php';

// Obter o parâmetro de pesquisa
$query = isset($_GET['site']) ? $_GET['site'] : '';

// Buscar logins no banco de dados
try {
    $stmt = $pdo->prepare("SELECT site, email, password FROM logins WHERE site LIKE :query");
    $stmt->execute(['query' => "%$query%"]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Enviar resposta JSON
    echo json_encode($result);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao consultar o banco de dados: ' . $e->getMessage()]);
}
?>
