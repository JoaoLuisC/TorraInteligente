#ifndef WEB_INTERFACE_H
#define WEB_INTERFACE_H

#include <ESP8266WebServer.h>
#include <DNSServer.h>

// ========== INTERFACE WEB ==========

// Página principal de configuração
const char* HTML_CONFIG_PAGE = R"rawliteral(
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Torra Inteligente</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); max-width: 400px; width: 90%; }
        .header { text-align: center; margin-bottom: 2rem; }
        .header h1 { color: #333; font-size: 1.5rem; margin-bottom: 0.5rem; }
        .header .emoji { font-size: 2rem; }
        .info-box { background: #e3f2fd; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #555; }
        input[type="text"], input[type="password"] { width: 100%; padding: 0.8rem; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1rem; transition: border-color 0.3s; }
        input[type="text"]:focus, input[type="password"]:focus { outline: none; border-color: #667eea; }
        .btn { width: 100%; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: transform 0.2s; }
        .btn:hover { transform: translateY(-2px); }
        .footer { text-align: center; margin-top: 1rem; font-size: 0.8rem; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="emoji">🌡️</div>
            <h1>Sensor Torra Inteligente</h1>
        </div>

        <div class="info-box">
            <strong>IP:</strong> %%IP%%<br>
            <strong>Versão:</strong> %%VERSION%%<br>
            <strong>Status:</strong> Aguardando configuração
        </div>

        <form action="/save" method="POST">
            <div class="form-group">
                <label for="ssid">📶 Nome da Rede Wi-Fi</label>
                <input type="text" id="ssid" name="ssid" required placeholder="Ex: MinhaRede" maxlength="32">
            </div>

            <div class="form-group">
                <label for="password">🔒 Senha da Rede</label>
                <input type="password" id="password" name="password" placeholder="Deixe vazio se não houver senha" maxlength="64">
            </div>

            <div class="form-group">
                <label for="key">🔑 Chave do Dispositivo</label>
                <input type="text" id="key" name="key" required placeholder="Ex: SENSOR_001" maxlength="32">
            </div>

            <button type="submit" class="btn">💾 Salvar e Conectar</button>
        </form>

        <div class="footer">
            Projeto Torra Inteligente - IFSULDEMINAS
        </div>
    </div>
</body>
</html>
)rawliteral";

// Página de sucesso
const char* HTML_SUCCESS_PAGE = R"rawliteral(
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuração Salva</title>
    <style>
        body { font-family: Arial, sans-serif; background: #e8f5e8; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .container { background: white; padding: 2rem; border-radius: 12px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .success-icon { font-size: 4rem; color: #4caf50; margin-bottom: 1rem; }
        h2 { color: #333; margin-bottom: 1rem; }
        p { color: #666; margin-bottom: 0.5rem; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #4caf50; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 1rem auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✅</div>
        <h2>Configuração Salva!</h2>
        <p>Conectando à rede Wi-Fi...</p>
        <div class="spinner"></div>
        <p><em>O dispositivo será configurado automaticamente.</em></p>
    </div>
</body>
</html>
)rawliteral";

// Página de erro
const char* HTML_ERROR_PAGE = R"rawliteral(
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro na Configuração</title>
    <style>
        body { font-family: Arial, sans-serif; background: #ffe8e8; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .container { background: white; padding: 2rem; border-radius: 12px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .error-icon { font-size: 4rem; color: #f44336; margin-bottom: 1rem; }
        h2 { color: #333; margin-bottom: 1rem; }
        p { color: #666; margin-bottom: 1rem; }
        .btn { display: inline-block; padding: 0.8rem 1.5rem; background: #007bff; color: white; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">❌</div>
        <h2>Erro na Configuração</h2>
        <p>%%ERROR_MESSAGE%%</p>
        <a href="/" class="btn">🔄 Tentar Novamente</a>
    </div>
</body>
</html>
)rawliteral";

#endif
