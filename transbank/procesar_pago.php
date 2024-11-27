<?php

require __DIR__ . '/webpay-init.php';

use Transbank\Webpay\WebpayPlus\Transaction;

try {
    // Crear una instancia de Transaction
    $transaction = new Transaction();

    // Datos para la transacción
    $buyOrder = 'orden12345'; // Código único de la orden
    $sessionId = 'session12345'; // ID único de la sesión
    $amount = 10000; // Monto de la transacción
    $returnUrl = ' https://d704-158-170-177-104.ngrok-free.app/transbank/confirmar_pago.php'; // URL de retorno

    // Crear la transacción
    $response = $transaction->create($buyOrder, $sessionId, $amount, $returnUrl);

    // Mostrar los datos de la transacción
    echo 'URL de pago: ' . $response->getUrl() . PHP_EOL;
    echo 'Token de la transacción: ' . $response->getToken() . PHP_EOL;

    // Redirigir al usuario a la URL de pago
    header('Location: ' . $response->getUrl() . '?token_ws=' . $response->getToken());
    exit;

} catch (Exception $e) {
    // Manejo de errores
    echo 'Error al procesar el pago: ' . $e->getMessage();
}
