<?php
// Inclua o arquivo de conexão com o banco de dados
include('../db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);

        echo json_encode(['status' => 'success', 'message' => 'Registro realizado com sucesso.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Método não permitido
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
}
?>
