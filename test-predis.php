<?php

require __DIR__ . '/vendor/autoload.php';

$host     = env('REDIS_HOST', '127.0.0.1');
$port     = env('REDIS_PORT', 6379);
$password = env('REDIS_PASSWORD');
$db       = env('REDIS_DB', 0);

echo "Попытка подключения:\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Password: " . ($password ? '***' : 'нет') . "\n";
echo "DB: $db\n\n";

$client = new Predis\Client([
    'scheme'   => 'tcp',
    'host'     => $host,
    'port'     => $port,
    'password' => $password ?: null,
    'database' => $db,
]);

try {
    $client->connect();
    $pong = $client->ping();
    echo "УСПЕХ! PING → $pong\n";
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
    echo "Код: " . $e->getCode() . "\n";
}
