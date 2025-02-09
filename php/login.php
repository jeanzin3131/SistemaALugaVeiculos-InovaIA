<?php
session_start();
require_once '../config/db.php'; // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Consulta para verificar se o usuário existe
        $sql = "SELECT id, nome, senha, tipo_usuario FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Senha correta, salvar dados na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            // Redirecionar com base no tipo de usuário
            if ($usuario['tipo_usuario'] === 'admin') {
                header("Location: ../public/dashboard_admin.php");
            } elseif ($usuario['tipo_usuario'] === 'locador') {
                header("Location: ../public/dashboard_locador.php");
            } elseif ($usuario['tipo_usuario'] === 'locatario') {
                header("Location: ../public/dashboard_locatario.php");
            }
            exit();
        } else {
            // Usuário ou senha inválidos
            echo "<script>
                alert('E-mail ou senha inválidos. Por favor, tente novamente.');
                window.location.href = '../public/login.html';
            </script>";
        }
    } catch (PDOException $e) {
        die("Erro ao tentar autenticar: " . $e->getMessage());
    }
} else {
    header("Location: ../public/index.html");
    exit();
}
?>