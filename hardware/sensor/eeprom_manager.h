#ifndef EEPROM_MANAGER_H
#define EEPROM_MANAGER_H

#include <EEPROM.h>
#include "config.h"

// ========== GERENCIADOR EEPROM ==========

class EEPROMManager {
public:
    // Carrega configurações da EEPROM
    static bool loadConfig(String& ssid, String& password, String& deviceKey) {
        EEPROM.begin(EEPROM_SIZE);

        // Verifica se há configuração salva
        byte configFlag = EEPROM.read(CONFIG_ADDR);
        if (configFlag != CONFIG_FLAG) {
            EEPROM.end();
            return false;
        }

        // Carrega as configurações
        EEPROM.get(SSID_ADDR, ssid);
        EEPROM.get(PASS_ADDR, password);
        EEPROM.get(KEY_ADDR, deviceKey);

        EEPROM.end();

        // Limpa strings se necessário
        ssid.trim();
        password.trim();
        deviceKey.trim();

        return (ssid.length() > 0 && deviceKey.length() > 0);
    }

    // Salva configurações na EEPROM
    static bool saveConfig(const String& ssid, const String& password, const String& deviceKey) {
        if (ssid.length() == 0 || deviceKey.length() == 0) {
            Serial.println("❌ Dados inválidos para salvar");
            return false;
        }

        if (ssid.length() > MAX_SSID_LENGTH ||
            password.length() > MAX_PASSWORD_LENGTH ||
            deviceKey.length() > MAX_DEVICE_KEY_LENGTH) {
            Serial.println("❌ Dados muito longos");
            return false;
        }

        EEPROM.begin(EEPROM_SIZE);

        // Limpa a área de memória
        for (int i = 0; i < 400; i++) {
            EEPROM.write(i, 0);
        }

        // Salva as configurações
        EEPROM.put(SSID_ADDR, ssid);
        EEPROM.put(PASS_ADDR, password);
        EEPROM.put(KEY_ADDR, deviceKey);
        EEPROM.write(CONFIG_ADDR, CONFIG_FLAG);

        bool success = EEPROM.commit();
        EEPROM.end();

        if (success) {
            Serial.println("✅ Configuração salva na EEPROM");
        } else {
            Serial.println("❌ Erro ao salvar na EEPROM");
        }

        return success;
    }

    // Limpa todas as configurações
    static void clearConfig() {
        EEPROM.begin(EEPROM_SIZE);
        for (int i = 0; i < EEPROM_SIZE; i++) {
            EEPROM.write(i, 0);
        }
        EEPROM.commit();
        EEPROM.end();
        Serial.println("🗑️ Configurações EEPROM limpas");
    }
};

#endif
