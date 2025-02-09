<?php
// Verifica se o autoload existe
require_once __DIR__ . '/../vendor/autoload.php';  // Caminho correto para o autoload gerado pelo Composer

// Agora o SDK do Mercado Pago estará disponível
MercadoPago\SDK::setAccessToken('TEST-3911994694042579-020201-bb9af4d14ed4b7a3f91b6fb3e54114ce-2246647458');
?>