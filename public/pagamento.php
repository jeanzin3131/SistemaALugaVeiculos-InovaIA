<?php
session_start();

if (!isset($_GET['veiculo_id'])) {
    die("Veículo não especificado.");
}

$access_token = "APP_USR-3911994694042579-020201-9c00076201ed71d02162dc7a91f487dc-2246647458";

$veiculo_id = $_GET['veiculo_id'];
$titulo = "Reserva de Veículo #$veiculo_id";
$preco = 100.00;

$preference_data = [
    "items" => [
        [
            "title" => $titulo,
            "quantity" => 1,
            "unit_price" => $preco,
            "currency_id" => "BRL"
        ]
    ],
    "payment_methods" => [
        "excluded_payment_types" => [],
        "excluded_payment_methods" => [],
        "default_payment_method_id" => "pix"  // Define Pix como método principal
    ],
    "back_urls" => [
        "success" => "http://seusite.com/sucesso.php",
        "failure" => "http://seusite.com/falha.php",
        "pending" => "http://seusite.com/pendente.php"
    ],
    "auto_return" => "approved"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/checkout/preferences");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $access_token"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preference_data));

$response = curl_exec($ch);
curl_close($ch);

$mp_response = json_decode($response, true);

if (isset($mp_response['init_point'])) {
    header("Location: " . $mp_response['init_point']);
    exit();
} else {
    echo "Erro ao gerar pagamento: " . $response;
}
?>