<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'username', 'password', 'database');

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Erro na conexão com o banco de dados: ' . $conn->connect_error]));
}

// Verifica se o arquivo foi enviado
if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['file']['tmp_name'];

    // Verifica se o arquivo é legível
    if (!is_readable($file)) {
        echo json_encode(['success' => false, 'error' => 'Arquivo não pode ser lido.']);
        exit;
    }

    // Abre o arquivo e lê linha por linha
    $fileContent = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (!$fileContent) {
        echo json_encode(['success' => false, 'error' => 'Erro ao ler o conteúdo do arquivo.']);
        exit;
    }

    $inserted = 0;
    $errors = [];

    foreach ($fileContent as $lineNumber => $line) {
        // Remove espaços em branco e quebra de linha
        $line = trim($line);

        // Verifica se a linha não está vazia
        if (!empty($line)) {
            // Divide a linha em site, email e senha
            $data = explode(',', $line);

            // Verifica se o número de elementos na linha está correto (deve ser 3)
            if (count($data) === 3) {
                $site = $conn->real_escape_string(trim($data[0]));
                $email = $conn->real_escape_string(trim($data[1]));
                $password = $conn->real_escape_string(trim($data[2]));

                // Insere os dados no banco de dados
                $sql = "INSERT INTO logins (site, email, password) VALUES ('$site', '$email', '$password')";
                if ($conn->query($sql) === TRUE) {
                    $inserted++;
                } else {
                    $errors[] = "Erro ao inserir login na linha " . ($lineNumber + 1) . ": " . $conn->error;
                }
            } else {
                $errors[] = "Formato inválido na linha " . ($lineNumber + 1) . ": $line";
            }
        }
    }

    if (empty($errors)) {
        echo json_encode(['success' => true, 'inserted' => $inserted]);
    } else {
        echo json_encode(['success' => false, 'inserted' => $inserted, 'errors' => $errors]);
    }
} else {
    // Detecta o tipo de erro no upload do arquivo
    $error_message = 'Nenhum arquivo enviado ou erro ao fazer o upload.';
    if (isset($_FILES['file']['error'])) {
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error_message = 'O arquivo enviado é muito grande.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $error_message = 'O upload do arquivo foi feito parcialmente.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $error_message = 'Nenhum arquivo foi enviado.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $error_message = 'Pasta temporária ausente.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $error_message = 'Falha ao escrever o arquivo no disco.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $error_message = 'Uma extensão do PHP interrompeu o upload do arquivo.';
                break;
        }
    }

    echo json_encode(['success' => false, 'error' => $error_message]);
}

$conn->close();
?>
