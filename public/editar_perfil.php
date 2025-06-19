<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

try {
    $sql = "SELECT nome, email, telefone FROM usuarios WHERE id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':usuario_id' => $_SESSION['usuario_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        die("Usuário não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar dados do usuário: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone WHERE id = :usuario_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $_POST['nome'],
            ':email' => $_POST['email'],
            ':telefone' => $_POST['telefone'],
            ':usuario_id' => $_SESSION['usuario_id']
        ]);
        $mensagem = "Dados atualizados com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar dados: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - DirigeAí</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        h2 {
            text-align: center;
            font-weight: bold;
            color: #198754;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #198754;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #157347;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            border-radius: 8px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .form-label {
            font-weight: bold;
            color: #198754;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-user-edit me-2"></i>Editar Perfil</h2>
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-success text-center">
                <?= htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label"><i class="fas fa-user me-2"></i>Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label"><i class="fas fa-phone me-2"></i>Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone']); ?>" placeholder="Ex.: (11) 98765-4321" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i>Salvar Alterações</button>
        </form>
        <button onclick="window.history.back()" class="btn btn-secondary w-100 mt-3"><i class="fas fa-arrow-left me-2"></i>Voltar</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>