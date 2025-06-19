<?php
// Verifica se o autoload existe
require_once __DIR__ . '/../vendor/autoload.php';  // Caminho correto para o autoload gerado pelo Composer

// Usa o token de acesso definido no ambiente
$token = getenv('MERCADOPAGO_ACCESS_TOKEN');
if ($token) {
    MercadoPago\SDK::setAccessToken($token);
}
?>