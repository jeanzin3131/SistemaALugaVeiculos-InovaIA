<?php
session_start();
require_once "../config/db.php"; // Arquivo de conexão com o banco de dados

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensagem_upload'] = ["tipo" => "erro", "mensagem" => "Usuário não autenticado."];
    header("Location: dashboard_locador.php");
    exit();
}

// Definição do diretório para armazenar os arquivos
$diretorio = "../uploads/documentos/";
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0777, true);
    chmod($diretorio, 0777);
}

// Verifica se o formulário foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo']) && isset($_POST['tipo_documento'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $tipo_documento = filter_var($_POST['tipo_documento'], FILTER_SANITIZE_STRING);
    $arquivo = $_FILES['arquivo'];

    // Tipos de documentos permitidos
    $documentos_permitidos = ['cnh', 'comprovante_residencia', 'documento_veiculo'];
    if (!in_array($tipo_documento, $documentos_permitidos)) {
        $_SESSION['mensagem_upload'] = ["tipo" => "erro", "mensagem" => "Tipo de documento inválido."];
        header("Location: dashboard_locador.php");
        exit();
    }

    // Validação do tipo de arquivo permitido (PDF, PNG, JPG, JPEG)
    $extensoes_permitidas = ['pdf', 'png', 'jpg', 'jpeg'];
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoes_permitidas)) {
        $_SESSION['mensagem_upload'] = ["tipo" => "erro", "mensagem" => "Formato inválido. Apenas PDF, PNG, JPG e JPEG são aceitos."];
        header("Location: dashboard_locador.php");
        exit();
    }

    // Nome do arquivo único
    $nome_arquivo = $usuario_id . "_" . $tipo_documento . "_" . time() . "." . $extensao;
    $caminho_completo = $diretorio . $nome_arquivo;
    $caminho_salvo = "uploads/documentos/" . $nome_arquivo; // Caminho salvo no banco

    // Move o arquivo para o diretório correto
    if (move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
        // Insere no banco de dados
        $stmt = $pdo->prepare("INSERT INTO documentos_usuarios (usuario_id, tipo_documento, caminho_arquivo, data_envio) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$usuario_id, $tipo_documento, $caminho_salvo])) {
            $_SESSION['mensagem_upload'] = ["tipo" => "sucesso", "mensagem" => "Documento enviado com sucesso!"];
        } else {
            $_SESSION['mensagem_upload'] = ["tipo" => "erro", "mensagem" => "Erro ao registrar no banco de dados."];
        }
    } else {
        $_SESSION['mensagem_upload'] = ["tipo" => "erro", "mensagem" => "Erro ao salvar o arquivo."];
    }
} else {
    $_SESSION['mensagem_upload'] = ["tipo" => "erro", "mensagem" => "Requisição inválida."];
}

// Redireciona de volta para o dashboard
header("Location: dashboard_locador.php");
exit();
?>