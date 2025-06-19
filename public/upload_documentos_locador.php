<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locador') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

// Verificar se os documentos foram enviados
$stmt = $pdo->prepare("SELECT documentos_verificados FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$documentos_verificados = $user['documentos_verificados'] ?? 0;

// Processar o upload dos documentos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erro = false;
    $mensagem = "";

    // Definir os tipos de arquivos permitidos
    $tipos_permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
    $documentos = ['documento_veiculo'];
    $uploads_dir = '../uploads/';

    foreach ($documentos as $documento) {
        if (isset($_FILES[$documento]) && $_FILES[$documento]['error'] === UPLOAD_ERR_OK) {
            $arquivo = $_FILES[$documento];
            $tipo = $arquivo['type'];
            $nome_arquivo = basename($arquivo['name']);
            $caminho_arquivo = $uploads_dir . $nome_arquivo;

            if (!in_array($tipo, $tipos_permitidos)) {
                $erro = true;
                $mensagem = "Formato de arquivo inválido. Aceitos: JPEG, PNG ou PDF.";
            }

            if (!$erro && !move_uploaded_file($arquivo['tmp_name'], $caminho_arquivo)) {
                $erro = true;
                $mensagem = "Erro ao fazer upload do arquivo $nome_arquivo.";
            }
        }
    }

    // Se não houver erro, atualizar a tabela de usuários e marcar os documentos como verificados
    if (!$erro) {
        $stmt = $pdo->prepare("UPDATE usuarios SET documentos_verificados = 1 WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['usuario_id']);
        $stmt->execute();
        $mensagem = "Documentos enviados com sucesso!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Documentos - DirigeAí</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #198754;
        }
        .navbar-brand {
            font-weight: bold;
        }
        footer {
            margin-top: 40px;
            padding: 15px 0;
            background-color: #343a40;
            color: #fff;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-car-side"></i> DirigeAí - Locador</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Enviar Documentos</h5>

                        <!-- Exibir mensagem de sucesso ou erro -->
                        <?php if (!empty($mensagem)): ?>
                            <div class="alert <?= $erro ? 'alert-danger' : 'alert-success' ?>" role="alert">
                                <?= $mensagem ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulário de envio de documentos -->
                        <form action="upload_documentos_locador.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="documento_veiculo" class="form-label">Documento do Veículo</label>
                                <input type="file" class="form-control" id="documento_veiculo" name="documento_veiculo" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Enviar Documento</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y') ?> DirigeAí - Todos os direitos reservados.</p>
    </footer>

    <!-- Bootstrap e Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>