<?php

declare(strict_types=1);

use Damax\Client\Configuration;
use Http\Adapter\Guzzle6\Client;

require __DIR__ . '/../vendor/autoload.php';

$config = new Configuration('https://product.damax.solutions/api', 'token');

$httpClient = Client::createWithConfig([
    'verify' => false,
]);

echo (string) $config
    ->setHttpClient($httpClient)
    ->getClient()
    ->downloadPassportCheck('74 05 558551')
;
