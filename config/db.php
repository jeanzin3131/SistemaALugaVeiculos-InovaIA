<?php
try {
    $host = 'localhost';
    $port = 8001; // Verifique se está correta a porta configurada
    $dbname = 'dirigeai'; // Substitua pelo nome do seu banco
    $username = 'root'; // Substitua pelo usuário do banco
    $password = ''; // Substitua pela senha do banco

    // Criar a conexão PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>