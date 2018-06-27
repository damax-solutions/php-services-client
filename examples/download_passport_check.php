<?php

// Usage:
// $ php examples/download_passport_check.php > result.pdf

declare(strict_types=1);

use Damax\Services\Client\Configuration;

require __DIR__ . '/../vendor/autoload.php';

$config = new Configuration('https://api.damax.solutions/services', 'token');

echo (string) $config
    ->getClient()
    ->downloadPassportCheck('74 05 558551')
    ->getBody()
;
