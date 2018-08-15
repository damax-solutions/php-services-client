<?php

// Usage:
// $ php examples/check_multiple_passports.php

declare(strict_types=1);

use Damax\Services\Client\Configuration;

require __DIR__ . '/../vendor/autoload.php';

$config = new Configuration('https://api.damax.solutions/services', 'token');

$result = $config
    ->getClient()
    ->checkMultiplePassports(['74 05 558551', '46 05 685971'])
;

var_dump($result);
