/*
 * ========================================
 * SENSOR TORRA INTELIGENTE v2.1
 * ========================================
 *
 * Firmware modular para ESP8266
 * Projeto: Torra Inteligente - IFSULDEMINAS
 *
 * Funcionalidades:
 * - Configuração via Captive Portal
 * - Conexão WiFi automática
 * - Envio de dados para servidor
 * - Interface web responsiva
 * - Gestão inteligente de reconexão
 *
 * ========================================
 */

// ========== INCLUDES DOS MÓDULOS ==========
#include "config.h"
#include "web_interface.h"
#include "eeprom_manager.h"
#include "wifi_manager.h"
#include "data_sender.h"

// Bibliotecas padrão
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <DNSServer.h>

// ========== OBJETOS GLOBAIS ==========
ESP8266WebServer server(SERVER_PORT);
DNSServer dnsServer;

// ========== VARIÁVEIS GLOBAIS ==========
String ssid = "";
String password = "";
String deviceKey = "";

bool sistemaConfigurado = false;
bool wifiConectado = false;
unsigned long proximoEnvio = 0;

// ========== HANDLERS WEB ==========

void handleRoot() {
    String html = HTML_CONFIG_PAGE;
    html.replace("%%IP%%", WiFi.softAPIP().toString());
    html.replace("%%VERSION%%", FIRMWARE_VERSION);
    server.send(200, "text/html", html);
}

void handleSave() {
    // Captura dados do formulário
    String novoSSID = server.arg("ssid");
    String novaSenha = server.arg("password");
    String novaChave = server.arg("key");

    // Validação básica
    if (novoSSID.length() == 0 || novaChave.length() == 0) {
        String errorPage = HTML_ERROR_PAGE;
        errorPage.replace("%%ERROR_MESSAGE%%", "SSID e Chave do Dispositivo são obrigatórios!");
        server.send(400, "text/html", errorPage);
        return;
    }

    if (novoSSID.length() > MAX_SSID_LENGTH ||
        novaSenha.length() > MAX_PASSWORD_LENGTH ||
        novaChave.length() > MAX_DEVICE_KEY_LENGTH) {
        String errorPage = HTML_ERROR_PAGE;
        errorPage.replace("%%ERROR_MESSAGE%%", "Dados informados são muito longos!");
        server.send(400, "text/html", errorPage);
        return;
    }

    // Tenta salvar configuração
    if (!EEPROMManager::saveConfig(novoSSID, novaSenha, novaChave)) {
        String errorPage = HTML_ERROR_PAGE;
        errorPage.replace("%%ERROR_MESSAGE%%", "Falha ao salvar configuração na memória!");
        server.send(500, "text/html", errorPage);
        return;
    }

    // Atualiza variáveis locais
    ssid = novoSSID;
    password = novaSenha;
    deviceKey = novaChave;

    // Envia página de sucesso
    server.send(200, "text/html", HTML_SUCCESS_PAGE);

    // Aguarda um pouco e tenta conectar
    delay(2000);

    // Para o AP e tenta conectar ao WiFi
    WiFi.softAPdisconnect(true);

    if (WiFiManager::conectar(ssid, password)) {
        sistemaConfigurado = true;
        wifiConectado = true;
        DataSender::configurarServidor(); // Usa servidor padrão
        Serial.println("🚀 Sistema configurado e pronto!");
    } else {
        Serial.println("❌ Falha na conexão. Reiniciando...");
        delay(3000);
        ESP.restart();
    }
}

void handleNotFound() {
    // Redireciona para página principal (captive portal)
    server.sendHeader("Location", "/", true);
    server.send(302, "text/plain", "");
}

// ========== FUNÇÕES PRINCIPAIS ==========

void setup() {
    Serial.begin(SERIAL_BAUD_RATE);
    Serial.println();
    Serial.println("========================================");
    Serial.println("🌡️ SENSOR TORRA INTELIGENTE v" FIRMWARE_VERSION);
    Serial.println("========================================");

    // Tenta carregar configuração existente
    if (EEPROMManager::loadConfig(ssid, password, deviceKey)) {
        Serial.println("📋 Configuração encontrada!");
        Serial.println("📶 SSID: " + ssid);
        Serial.println("🔑 Device Key: " + deviceKey);

        // Tenta conectar automaticamente
        if (WiFiManager::conectar(ssid, password)) {
            sistemaConfigurado = true;
            wifiConectado = true;
            DataSender::configurarServidor();
            Serial.println("🚀 Sistema pronto para operação!");
            return; // Setup completo, vai para o loop
        } else {
            Serial.println("⚠️ Falha na conexão automática");
        }
    } else {
        Serial.println("📝 Primeira configuração necessária");
    }

    // Inicia modo configuração (Access Point)
    iniciarModoConfiguracao();
}

void iniciarModoConfiguracao() {
    Serial.println("� Iniciando modo configuração...");

    // Inicia Access Point
    IPAddress apIP = WiFiManager::iniciarAP();

    // Configura DNS captive portal
    dnsServer.start(DNS_PORT, "*", apIP);

    // Configura rotas do servidor web
    server.on("/", handleRoot);
    server.on("/save", HTTP_POST, handleSave);
    server.onNotFound(handleNotFound);

    server.begin();
    Serial.println("🌐 Servidor web iniciado!");
    Serial.println("📱 Conecte-se à rede e acesse qualquer site");
}

void loop() {
    // Se está em modo configuração (AP ativo)
    if (!sistemaConfigurado) {
        dnsServer.processNextRequest();
        server.handleClient();
        delay(50);
        return;
    }

    // Se está configurado, gerencia operação normal
    unsigned long agora = millis();

    // Verifica conexão WiFi
    if (!WiFiManager::verificarConexao()) {
        wifiConectado = false;

        // Tenta reconectar
        if (WiFiManager::conectar(ssid, password)) {
            wifiConectado = true;
            Serial.println("🔄 Reconectado com sucesso!");
        } else {
            WiFiManager::verificarReinicio(); // Reinicia se muitas falhas
        }
    }

    // Envia dados se conectado e no intervalo correto
    if (wifiConectado && agora >= proximoEnvio) {
        proximoEnvio = agora + INTERVALO_ENVIO;

        if (DataSender::enviarDados(deviceKey)) {
            if (DEBUG_ENABLED) {
                Serial.println("📊 " + DataSender::obterEstatisticas());
            }
        } else {
            Serial.println("⚠️ Falha no envio de dados");
        }
    }

    // Status periódico (a cada 60 segundos)
    static unsigned long proximoStatus = 0;
    if (agora >= proximoStatus) {
        proximoStatus = agora + 60000;
        Serial.println("📡 Status: " + WiFiManager::obterInfoConexao());
    }

    delay(100); // Evita sobrecarregar o processador
}
