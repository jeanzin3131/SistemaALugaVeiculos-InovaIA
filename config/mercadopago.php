<?php
// Caminho para o autoload dentro da pasta config
require_once __DIR__ . '/autoload.php';  // Ajuste o caminho se necessário

// Configure the Mercado Pago SDK using an access token from the environment
$token = getenv('MERCADOPAGO_ACCESS_TOKEN');
if ($token) {
    MercadoPago\SDK::setAccessToken($token);
}
?>