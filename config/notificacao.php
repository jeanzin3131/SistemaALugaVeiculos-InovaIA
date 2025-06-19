<?php
require_once 'db.php'; // Conexão com o banco

$dados = file_get_contents("php://input");
$evento = json_decode($dados, true);

if (isset($evento['type']) && $evento['type'] == "payment") {
    $payment_id = $evento['data']['id'];

    // Consultar o status do pagamento na API do Mercado Pago
    $access_token = getenv('MERCADOPAGO_ACCESS_TOKEN');
    $url = "https://api.mercadopago.com/v1/payments/$payment_id";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $access_token"]);
    $response = curl_exec($ch);
    curl_close($ch);

    $pagamento = json_decode($response, true);

    if ($pagamento['status'] == "approved") {
        $veiculo_id = $pagamento['external_reference']; // Use esse campo para identificar a reserva
        $sql = "UPDATE reservas SET status_reserva = 'pago' WHERE veiculo_id = :veiculo_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":veiculo_id", $veiculo_id);
        $stmt->execute();
    }
}
http_response_code(200);
?>