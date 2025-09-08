<?php

// Teste de login de usuário
echo "=== TESTE DE LOGIN DE USUÁRIO ===\n";

// Dados de login (usando o usuário que acabamos de criar)
$loginData = [
    'email' => 'joao.silva@teste.com',
    'password' => 'senha123456'
];

echo "Tentando login com:\n";
echo "- Email: {$loginData['email']}\n";
echo "- Password: [oculta]\n";

$url = 'http://127.0.0.1:8000/login';

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies_login.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies_login.txt');

// Primeiro, pegar a página de login para obter o token CSRF
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, false);
$loginPage = curl_exec($ch);

// Extrair token CSRF
preg_match('/<meta name="csrf-token" content="(.+?)"/', $loginPage, $matches);
if (!isset($matches[1])) {
    preg_match('/<input[^>]*name="_token"[^>]*value="([^"]*)"/', $loginPage, $matches);
}

if (isset($matches[1])) {
    $csrfToken = $matches[1];
    echo "\nToken CSRF encontrado: " . substr($csrfToken, 0, 10) . "...\n";

    $loginData['_token'] = $csrfToken;

    // Fazer POST do login
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    echo "Código HTTP: $httpCode\n";

    if ($httpCode == 302) {
        $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        echo "Redirecionamento para: $redirectUrl\n";
        echo "✅ LOGIN REALIZADO COM SUCESSO!\n";
    } else {
        echo "❌ ERRO NO LOGIN\n";
        echo "Resposta (primeiros 500 chars):\n";
        echo substr($response, 0, 500) . "\n";

        // Verificar se há mensagens de erro
        if (strpos($response, 'error') !== false || strpos($response, 'invalid') !== false) {
            echo "\n⚠️  Possível erro de autenticação\n";
        }
    }
} else {
    echo "❌ Token CSRF não encontrado\n";
}

curl_close($ch);

echo "\n=== VERIFICAÇÃO DIRETA NO BANCO ===\n";

// Verificar credenciais diretamente no banco
try {
    require_once 'vendor/autoload.php';

    $app = require_once 'bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    $user = \App\Models\User::where('email', $loginData['email'])->first();

    if ($user) {
        echo "✅ USUÁRIO ENCONTRADO:\n";
        echo "- Email: {$user->email}\n";
        echo "- Senha hash: " . substr($user->senha, 0, 20) . "...\n";

        // Verificar se a senha bate
        if (\Illuminate\Support\Facades\Hash::check($loginData['password'], $user->senha)) {
            echo "✅ SENHA CORRETA - Hash válido\n";
        } else {
            echo "❌ SENHA INCORRETA - Hash inválido\n";
        }

        // Testar autenticação Laravel
        if (\Illuminate\Support\Facades\Auth::attempt(['email' => $loginData['email'], 'password' => $loginData['password']])) {
            echo "✅ AUTH::ATTEMPT FUNCIONOU\n";
        } else {
            echo "❌ AUTH::ATTEMPT FALHOU\n";
        }

    } else {
        echo "❌ USUÁRIO NÃO ENCONTRADO\n";
    }

} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}
