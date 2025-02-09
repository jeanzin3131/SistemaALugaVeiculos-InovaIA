<?php
require_once '../config/db.php'; // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash seguro
    $telefone = $_POST['telefone'];

    try {
        $sql = "INSERT INTO usuarios (nome, email, senha, telefone, tipo_usuario) 
                VALUES (:nome, :email, :senha, :telefone, 'locatario')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
            ':telefone' => $telefone
        ]);

        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = '../public/login.html';</script>";
    } catch (PDOException $e) {
        die("Erro ao cadastrar: " . $e->getMessage());
    }
} else {
    header("Location: ../index.html");
    exit();
}
?>