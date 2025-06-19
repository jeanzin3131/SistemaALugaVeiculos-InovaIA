<?php
// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=localhost;port=8001;dbname=dirigeai", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Capturar os dados do formulário
$nome = $_POST['nome'] ?? null;
$email = $_POST['email'] ?? null;
$senha = $_POST['senha'] ?? null;

// Validar os dados
if (!$nome || !$email || !$senha) {
    die("Erro: Todos os campos devem ser preenchidos.");
}

// Inserir os dados na tabela "locadores"
try {
    $sql = "INSERT INTO locadores (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => password_hash($senha, PASSWORD_BCRYPT), // Armazenar senha com hash
    ]);

    echo "Locador cadastrado com sucesso!";
} catch (PDOException $e) {
    die("Erro ao cadastrar locador: " . $e->getMessage());
}
?>