<?php

// Carrega o autoload do Laravel
require __DIR__.'/vendor/autoload.php';

// Inicializa o Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Configura o Laravel
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$databaseName = config('database.connections.mysql.database');

// Tenta conectar ao banco de dados
try {
    \DB::connection()->getPdo();
    echo "Conexão com o banco de dados '$databaseName' estabelecida com sucesso.";
} catch (\Exception $e) {
    die("Não foi possível conectar ao banco de dados. Verifique suas configurações. Erro: " . $e->getMessage());
}