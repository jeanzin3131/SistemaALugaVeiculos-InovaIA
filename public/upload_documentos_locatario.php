<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Documentos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #198754;
            color: white;
            position: fixed;
            padding: 20px;
            transition: all 0.3s;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #146c43;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #198754;
            color: white;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #198754;
            color: white;
            border-radius: 8px;
        }
        .btn-custom:hover {
            background-color: #146c43;
        }
        .alert-info {
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3><i class="fas fa-cogs me-2"></i>Locatário</h3>
        <a href="dashboard_locatario.php"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="meus_documentos.php"><i class="fas fa-file-alt me-2"></i>Meus Documentos</a>
        <a href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2><i class="fas fa-file-alt me-2"></i>Upload de Documentos</h2>

        <!-- Exibir Motivo de Reprovação se existir -->
        <?php if ($usuario['documentos_verificados'] == 0 && $usuario['motivo_reprovacao']): ?>
            <div class="alert alert-info">
                <strong>Atenção:</strong> Seu envio foi reprovado. Motivo: <?php echo $usuario['motivo_reprovacao']; ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de Upload de Documentos -->
        <div class="card mt-4">
            <div class="card-header">Envio de Documentos</div>
            <div class="card-body">
                <!-- Formulário de upload de documentos -->
                <form method="POST" action="upload_documentos_locatario.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="cnh">CNH (Carteira Nacional de Habilitação):</label>
                        <input type="file" class="form-control" name="cnh" id="cnh">
                    </div>
                    <div class="form-group mt-3">
                        <label for="comprovante_residencia">Comprovante de Residência:</label>
                        <input type="file" class="form-control" name="comprovante_residencia" id="comprovante_residencia">
                    </div>
                    <div class="form-group mt-3">
                        <label for="documento_veiculo">Documento do Veículo (caso locador):</label>
                        <input type="file" class="form-control" name="documento_veiculo" id="documento_veiculo">
                    </div>

                    <button type="submit" class="btn btn-custom mt-3">Enviar Documentos</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>