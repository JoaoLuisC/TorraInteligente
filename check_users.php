<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Verificando usuários na base de dados:\n";

$users = App\Models\User::all(['email', 'nome', 'tipo']);

foreach ($users as $user) {
    echo $user->email . " - " . $user->nome . " (" . $user->tipo . ")\n";
}

echo "\nTotal de usuários: " . App\Models\User::count() . "\n";

$analistas = App\Models\User::where('tipo', 'Analista')->count();
echo "Analistas: " . $analistas . "\n";
