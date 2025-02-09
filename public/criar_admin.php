<?php
session_start();
require_once '../config/db.php'; // Conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo_usuario = 'admin';

    try {
        // Verifica se o e-mail já existe
        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('E-mail já cadastrado!'); window.location.href = 'criar_admin.php';</script>";
            exit();
        }

        // Insere o novo administrador no banco
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (:nome, :email, :senha, :tipo_usuario)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senha, ':tipo_usuario' => $tipo_usuario]);

        echo "<script>alert('Administrador cadastrado com sucesso!'); window.location.href = 'login.html';</script>";
        exit();
    } catch (PDOException $e) {
        die("Erro ao cadastrar: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Administrador - AlugaVale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            background: #ffffff;
            padding: 20px;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #198754;
        }
        .btn-primary {
            background-color: #198754;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #146c43;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Criar Administrador</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Criar Administrador</button>
        </form>
    </div>
</body>
</html>