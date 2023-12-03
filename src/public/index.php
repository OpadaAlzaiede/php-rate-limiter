<?php

declare(strict_types=1);

const BASE_PATH = __DIR__ . '/../';

$config = require "../config.php";
define('CONFIG', $config);

spl_autoload_register(function($class) {

    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);

    require BASE_PATH . $class . ".php";
});

$rateLimiter = \RateLimiting\TokenBucket::getRateLimiter();
