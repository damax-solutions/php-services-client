<?php

declare(strict_types=1);

use Damax\Client\Configuration;
use Http\Adapter\Guzzle6\Client;

require __DIR__ . '/../vendor/autoload.php';

$config = new Configuration('https://product.damax.solutions/api', 'token');

$httpClient = Client::createWithConfig([
    'verify' => false,
]);

$result = $config
    ->setHttpClient($httpClient)
    ->getClient()
    ->checkPassport('74 05 558551')
;

dump($result);
