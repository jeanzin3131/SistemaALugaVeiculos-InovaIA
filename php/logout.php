<?php
session_start();

// Verifica se a sessão está ativa
if (isset($_SESSION['usuario_id'])) {
    // Destrói todas as variáveis da sessão
    session_unset();
    
    // Destrói a sessão
    session_destroy();
}

// Redireciona o usuário para a página de login
header("Location: ../public/index.html");
exit();
?>