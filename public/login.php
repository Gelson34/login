<?php
// Inclua o arquivo de conexão com o banco de dados
include('../db/db_connect.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            echo json_encode(['status' => 'success', 'message' => 'Login bem-sucedido.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'E-mail ou senha inválidos.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao fazer login: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Método não permitido
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
}
?>
