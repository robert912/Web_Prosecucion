<?php
require __DIR__ . '/../vendor/autoload.php'; // Asegúrate de incluir el autoloader de Composer

use Transbank\Webpay\WebpayPlus;

// Configuración del SDK
WebpayPlus::configureForTesting(); // Configura automáticamente el commerceCode, apiKey y entorno
