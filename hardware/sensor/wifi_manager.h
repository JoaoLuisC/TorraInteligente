#ifndef WIFI_MANAGER_H
#define WIFI_MANAGER_H

#include <ESP8266WiFi.h>
#include "config.h"

// ========== GERENCIADOR WIFI ==========

class WiFiManager {
private:
    static int tentativasReconexao;
    static unsigned long ultimaVerificacao;

public:
    // Conecta ao Wi-Fi com retry e timeout
    static bool conectar(const String& ssid, const String& password) {
        if (ssid.length() == 0) {
            Serial.println("❌ SSID não configurado");
            return false;
        }

        WiFi.mode(WIFI_STA);
        WiFi.begin(ssid.c_str(), password.c_str());

        Serial.print("🔄 Conectando à rede: " + ssid);

        int tentativas = 0;
        while (WiFi.status() != WL_CONNECTED && tentativas < MAX_TENTATIVAS_WIFI) {
            delay(500);
            Serial.print(".");
            tentativas++;
        }
        Serial.println();

        if (WiFi.status() == WL_CONNECTED) {
            Serial.println("✅ WiFi conectado!");
            Serial.println("📍 IP: " + WiFi.localIP().toString());
            Serial.println("📶 Sinal: " + String(WiFi.RSSI()) + " dBm");
            tentativasReconexao = 0;
            return true;
        } else {
            Serial.println("❌ Falha na conexão WiFi");
            tentativasReconexao++;
            return false;
        }
    }

    // Verifica status da conexão e reconecta se necessário
    static bool verificarConexao() {
        unsigned long agora = millis();

        // Verifica a cada 30 segundos
        if (agora - ultimaVerificacao > INTERVALO_VERIFICACAO_WIFI) {
            ultimaVerificacao = agora;

            if (WiFi.status() != WL_CONNECTED) {
                Serial.println("⚠️ WiFi desconectado. Tentando reconectar...");
                return false;
            }
        }

        return WiFi.status() == WL_CONNECTED;
    }

    // Inicia Access Point para configuração
    static IPAddress iniciarAP() {
        WiFi.mode(WIFI_AP);
        String apName = "TorraInteligente_" + String(ESP.getChipId(), HEX);
        WiFi.softAP(apName.c_str());

        delay(100);
        IPAddress myIP = WiFi.softAPIP();

        Serial.println("📡 Access Point ativo!");
        Serial.println("🌐 Nome: " + apName);
        Serial.println("📍 IP: " + myIP.toString());

        return myIP;
    }

    // Obtém informações da conexão atual
    static String obterInfoConexao() {
        if (WiFi.status() != WL_CONNECTED) {
            return "Desconectado";
        }

        return "IP: " + WiFi.localIP().toString() +
               " | Sinal: " + String(WiFi.RSSI()) + "dBm" +
               " | SSID: " + WiFi.SSID();
    }

    // Força reinício se muitas tentativas falharam
    static void verificarReinicio() {
        if (tentativasReconexao >= 3) {
            Serial.println("❌ Muitas falhas de reconexão. Reiniciando...");
            delay(2000);
            ESP.restart();
        }
    }
};

// Inicialização de variáveis estáticas
int WiFiManager::tentativasReconexao = 0;
unsigned long WiFiManager::ultimaVerificacao = 0;

#endif
