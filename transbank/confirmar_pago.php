<?php

require __DIR__ . '/webpay-init.php';

use Transbank\Webpay\WebpayPlus\Transaction;

try {
    // Obtén el token de la URL
    $token = $_GET['token_ws'];

    // Crea una instancia de la transacción
    $transaction = new Transaction();

    // Verifica la transacción utilizando el token recibido
    $response = $transaction->commit($token); // Ahora usamos la instancia

    // Verifica si la transacción fue aprobada
    if ($response->status == 'TRANSFERRED') {
        // Pago exitoso
        echo 'Pago exitoso. La transacción fue aprobada.<br>';

        // Mostrar más detalles de la transacción
        echo 'Número de orden: ' . $response->buyOrder . '<br>';
        echo 'Token de la transacción: ' . $response->token . '<br>';
        echo 'Monto: ' . $response->amount . '<br>';
        echo 'Fecha: ' . $response->transactionDate . '<br>';
        echo 'Estado: ' . $response->status . '<br>';
        echo '<pre>';
        print_r($response);
        echo '</pre>';
        // Otros datos que puedes mostrar (según la respuesta)
        echo 'Detalle de la respuesta: ' . print_r($response, true);
        // Puedes hacer alguna acción como actualizar la base de datos, notificar al usuario, etc.
    } else if ($response->status == 'AUTHORIZED') {
        // Transacción autorizada pero no capturada
        echo 'La transacción está autorizada, pero aún no ha sido capturada.<br>';
        echo 'Número de orden: ' . $response->buyOrder . '<br>';
        echo 'Token de la transacción: ' . $response->token . '<br>';
        echo 'Monto autorizado: ' . $response->amount . '<br>';
        echo 'Estado: ' . $response->status . '<br>';
        echo '<pre>';
        print_r($response);
        echo '</pre>';
    } else {
        echo 'Orden de Compra '. $response->buyOrder .' rechazada <br>
        Las posibles causas de este rechazo son: <br>
        * Error en el ingreso de los datos de su tarjeta de crédito o débito (fecha y/o código de seguridad).<br>
        * Su tarjeta de crédito o débito no cuenta con saldo suficiente.<br>
        * Tarjeta aún no habilitada en el sistema financiero.<br>
        Error en el pago. El estado de la transacción es: ' . $response->status;
        echo '<pre>';
        print_r($response);
        echo '</pre>';
    }

} catch (Exception $e) {
    // Manejo de errores
    echo 'Error al procesar el pago: ' . $e->getMessage();
}
