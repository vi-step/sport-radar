<?php

require_once 'vendor/autoload.php';

try {
    container()->consoleApp()->run();
} catch (Throwable $e) {
    echo container()->config['debug'] ? $e->getMessage() : 'Internal Error';
}
