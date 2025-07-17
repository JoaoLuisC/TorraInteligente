<?php

echo "=== TESTE COMPLETO DE LOGIN ===\n";

$loginData = [
    'email' => 'joao.silva@teste.com',
    'password' => 'senha123456'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Não seguir redirecionamentos automaticamente
curl_setopt($ch, CURLOPT_COOKIEJAR, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_VERBOSE, false);

echo "1. Acessando página de login...\n";
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/login');
curl_setopt($ch, CURLOPT_POST, false);
$loginPage = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "   Status: $httpCode\n";

// Extrair token CSRF
preg_match('/<input[^>]*name="_token"[^>]*value="([^"]*)"/', $loginPage, $matches);
if (!isset($matches[1])) {
    preg_match('/<meta name="csrf-token" content="(.+?)"/', $loginPage, $matches);
}

if (isset($matches[1])) {
    $csrfToken = $matches[1];
    echo "   Token CSRF: " . substr($csrfToken, 0, 15) . "...\n";

    echo "\n2. Fazendo POST de login...\n";
    $loginData['_token'] = $csrfToken;

    curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/login');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
    curl_setopt($ch, CURLOPT_HEADER, true); // Include headers na resposta

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);

    echo "   Status: $httpCode\n";

    if ($httpCode == 302) {
        // Procurar header Location
        if (preg_match('/Location: (.+)/i', $headers, $locationMatch)) {
            $location = trim($locationMatch[1]);
            echo "   ✅ REDIRECIONAMENTO PARA: $location\n";

            echo "\n3. Seguindo redirecionamento...\n";
            curl_setopt($ch, CURLOPT_URL, $location);
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_HEADER, false);

            $finalPage = curl_exec($ch);
            $finalCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            echo "   Status: $finalCode\n";

            if ($finalCode == 200) {
                echo "   ✅ PÁGINA CARREGADA COM SUCESSO!\n";

                // Verificar conteúdo da página
                if (strpos($finalPage, 'dashboard') !== false || strpos($finalPage, 'torradores') !== false) {
                    echo "   ✅ LOGIN BEM-SUCEDIDO - Usuário logado!\n";
                } else {
                    echo "   ⚠️  Página carregada, mas conteúdo inesperado\n";
                }
            }
        } else {
            echo "   ❌ Redirecionamento sem header Location\n";
        }
    } else {
        echo "   ❌ Status inesperado\n";

        // Verificar se há erros na página
        if (strpos($body, 'Credenciais inválidas') !== false) {
            echo "   ❌ ERRO: Credenciais inválidas\n";
        } elseif (strpos($body, 'error') !== false) {
            echo "   ❌ ERRO: Encontrado na página\n";
        }

        echo "\nHeaders:\n" . substr($headers, 0, 300) . "\n";
        echo "\nBody (primeiros 300 chars):\n" . substr($body, 0, 300) . "\n";
    }
} else {
    echo "   ❌ Token CSRF não encontrado\n";
}

curl_close($ch);

echo "\n=== VERIFICAÇÃO FINAL ===\n";
// Limpar cookies e verificar se o login persistiu
unlink('test_cookies.txt');
echo "Login manual testado. Verificar no navegador se funcionou.\n";
