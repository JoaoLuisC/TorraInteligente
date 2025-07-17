<?php

// Teste de registro de usuário
echo "=== TESTE DE REGISTRO DE USUÁRIO ===\n";

// Simular dados de registro
$userData = [
    'firstName' => 'João',
    'lastName' => 'Silva',
    'email' => 'joao.silva@teste.com',
    'role' => 'administrador',
    'password' => 'senha123456',
    'password_confirmation' => 'senha123456'
];

echo "Dados do teste:\n";
foreach ($userData as $key => $value) {
    if ($key !== 'password' && $key !== 'password_confirmation') {
        echo "- $key: $value\n";
    }
}

// Fazer requisição POST para /register
$url = 'http://127.0.0.1:8000/register';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($userData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

// Primeiro, precisamos pegar o token CSRF
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/register');
curl_setopt($ch, CURLOPT_POST, false);
$registerPage = curl_exec($ch);

// Extrair token CSRF
preg_match('/<meta name="csrf-token" content="(.+?)"/', $registerPage, $matches);
if (!isset($matches[1])) {
    preg_match('/<input[^>]*name="_token"[^>]*value="([^"]*)"/', $registerPage, $matches);
}

if (isset($matches[1])) {
    $csrfToken = $matches[1];
    echo "\nToken CSRF encontrado: " . substr($csrfToken, 0, 10) . "...\n";

    $userData['_token'] = $csrfToken;

    // Agora fazer o POST
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($userData));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    echo "Código HTTP: $httpCode\n";

    if ($httpCode == 302) {
        $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        echo "Redirecionamento para: $redirectUrl\n";
        echo "✅ REGISTRO REALIZADO COM SUCESSO!\n";
    } else {
        echo "❌ ERRO NO REGISTRO\n";
        echo "Resposta (primeiros 500 chars):\n";
        echo substr($response, 0, 500) . "\n";
    }
} else {
    echo "❌ Token CSRF não encontrado\n";
}

curl_close($ch);

// Verificar se o usuário foi criado no banco
try {
    require_once 'vendor/autoload.php';

    $app = require_once 'bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    $user = \App\Models\User::where('email', $userData['email'])->first();

    if ($user) {
        echo "\n✅ USUÁRIO ENCONTRADO NO BANCO DE DADOS:\n";
        echo "- ID: {$user->id}\n";
        echo "- Nome: {$user->nome}\n";
        echo "- Sobrenome: {$user->sobrenome}\n";
        echo "- Email: {$user->email}\n";
        echo "- Tipo: {$user->tipo}\n";
        echo "- Criado em: {$user->criado_em}\n";
    } else {
        echo "\n❌ USUÁRIO NÃO ENCONTRADO NO BANCO DE DADOS\n";
    }

} catch (Exception $e) {
    echo "\n❌ ERRO AO VERIFICAR BANCO: " . $e->getMessage() . "\n";
}
