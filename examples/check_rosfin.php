<?php

// Usage:
// $ php examples/check_rosfin.php

declare(strict_types=1);

use Damax\Services\Client\Configuration;

require __DIR__ . '/../vendor/autoload.php';

$config = new Configuration('https://product.damax.solutions/api', 'token');

$result = $config
    ->getClient()
    ->checkRosfin('Соколовский Руслан Геннадьевич')
;

dump($result);
