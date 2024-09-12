<?php
  // Conexão com o banco de dados
  $conn = new mysqli('localhost', 'username', 'password', 'database');

  if ($conn->connect_error) {
    die('Erro na conexão: ' . $conn->connect_error);
  }

  // Recebendo dados via POST
  $data = json_decode(file_get_contents('php://input'), true);

  $site = $conn->real_escape_string($data['site']);
  $email = $conn->real_escape_string($data['email']);
  $password = $conn->real_escape_string($data['password']);

  // Inserindo dados no banco
  $sql = "INSERT INTO logins (site, email, password) VALUES ('$site', '$email', '$password')";

  if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
  }

  $conn->close();
?>
