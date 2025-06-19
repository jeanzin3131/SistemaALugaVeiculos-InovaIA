<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Certifique-se de ajustar o caminho correto
use MercadoPago\MercadoPagoConfig;
require_once '../config/db.php'; // Conexão com o banco de dados

// Configuração do Mercado Pago
$config = new MercadoPagoConfig();
$token = getenv('MERCADOPAGO_ACCESS_TOKEN');
if ($token) {
    $config->setAccessToken($token);
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha
    $collector_id = uniqid(); // Gera um ID único simulado para o collector_id
    $tipo_usuario = 'locador'; // Definido como locador para este exemplo

    try {
        // Prepara a consulta SQL para inserir os dados
        $sql = "INSERT INTO usuarios (nome, email, senha, collector_id, tipo_usuario) 
                VALUES (:nome, :email, :senha, :collector_id, :tipo_usuario)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
            ':collector_id' => $collector_id,
            ':tipo_usuario' => $tipo_usuario
        ]);

        // Redireciona com mensagem de sucesso
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = '../public/login.html';</script>";
    } catch (PDOException $e) {
        // Registra o erro no log do servidor
        error_log("Erro no banco de dados: " . $e->getMessage());
        // Exibe uma mensagem de erro para o usuário
        echo "<script>alert('Erro ao tentar cadastrar no banco de dados. Por favor, tente novamente mais tarde.'); window.history.back();</script>";
    } catch (Exception $e) {
        // Registra erros inesperados
        error_log("Erro inesperado: " . $e->getMessage());
        echo "<script>alert('Erro inesperado ao tentar cadastrar. Por favor, tente novamente mais tarde.'); window.history.back();</script>";
    }
} else {
    // Redireciona para a página inicial se o acesso não for via POST
    header("Location: ../index.html");
    exit();
}