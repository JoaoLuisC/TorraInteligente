#ifndef DATA_SENDER_H
#define DATA_SENDER_H

#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include "config.h"

// ========== ENVIADOR DE DADOS ==========

class DataSender {
private:
    static String serverURL;
    static int contadorDados;

public:
    // Configura URL do servidor
    static void configurarServidor(const String& url = DEFAULT_SERVER_URL) {
        serverURL = url;
    }

    // Envia dados para o servidor
    static bool enviarDados(const String& deviceKey) {
        if (WiFi.status() != WL_CONNECTED) {
            Serial.println("❌ WiFi desconectado - não é possível enviar dados");
            return false;
        }

        HTTPClient http;
        WiFiClient client;

        // Configura requisição HTTP
        http.begin(client, serverURL);
        http.setTimeout(TIMEOUT_HTTP);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        http.addHeader("User-Agent", "ESP8266-TorraInteligente/" FIRMWARE_VERSION);

        // Monta dados para envio
        String dadosPost = montarDadosPost(deviceKey);

        if (DEBUG_ENABLED) {
            Serial.println("📤 Enviando: " + dadosPost);
        }

        // Realiza POST
        int codigoResposta = http.POST(dadosPost);
        bool sucesso = false;

        if (codigoResposta > 0) {
            String resposta = http.getString();

            if (DEBUG_ENABLED) {
                Serial.println("📥 HTTP " + String(codigoResposta) + ": " + resposta);
            }

            if (codigoResposta == HTTP_CODE_OK) {
                contadorDados++;
                sucesso = true;
                if (DEBUG_ENABLED) {
                    Serial.println("✅ Dados enviados com sucesso! (#" + String(contadorDados) + ")");
                }
            } else {
                Serial.println("⚠️ Servidor retornou erro HTTP: " + String(codigoResposta));
            }
        } else {
            Serial.println("❌ Erro na requisição: " + String(codigoResposta));
        }

        http.end();
        return sucesso;
    }

    // Obtém estatísticas de envio
    static String obterEstatisticas() {
        return "Dados enviados: " + String(contadorDados) +
               " | Servidor: " + serverURL;
    }

    // Reseta contador
    static void resetarContador() {
        contadorDados = 0;
    }

private:
    // Lê temperatura do sensor
    static float lerTemperatura() {
        float soma = 0;
        for (int i = 0; i < TEMP_SAMPLES; i++) {
            int leitura = analogRead(TEMP_SENSOR_PIN);
            // Conversão para temperatura (ajustar conforme seu sensor)
            // Exemplo para LM35: (leitura * 3.3 / 1024) * 100
            float temp = (leitura * 3.3 / 1024) * 100;
            soma += temp;
            delay(10);
        }
        return soma / TEMP_SAMPLES;
    }

    // Monta string de dados para POST
    static String montarDadosPost(const String& deviceKey) {
        float temperatura = lerTemperatura();
        unsigned long tempoSegundos = millis() / 1000;

        return "device_key=" + deviceKey +
               "&temperatura=" + String(temperatura, 2) +
               "&tempo=" + String(tempoSegundos) +
               "&timestamp=" + String(millis()) +
               "&rssi=" + String(WiFi.RSSI()) +
               "&uptime=" + String(millis() / 1000) +
               "&free_heap=" + String(ESP.getFreeHeap()) +
               "&version=" + String(FIRMWARE_VERSION);
    }
};

// Inicialização de variáveis estáticas
String DataSender::serverURL = DEFAULT_SERVER_URL;
int DataSender::contadorDados = 0;

#endif
