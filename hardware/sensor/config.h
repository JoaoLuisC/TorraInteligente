#ifndef CONFIG_H
#define CONFIG_H

// ========== CONFIGURAÇÕES PRINCIPAIS ==========

// Versão do firmware
#define FIRMWARE_VERSION "2.1"

// Configurações de rede
#define DNS_PORT 53
#define SERVER_PORT 80
#define TIMEOUT_HTTP 10000    // 10 segundos
#define MAX_TENTATIVAS_WIFI 30
#define INTERVALO_VERIFICACAO_WIFI 30000  // 30 segundos

// Configurações de dados
#define INTERVALO_ENVIO 5000  // 5 segundos em ms

// Configurações EEPROM
#define EEPROM_SIZE 512
#define SSID_ADDR 0
#define PASS_ADDR 100
#define KEY_ADDR 200
#define CONFIG_ADDR 300
#define CONFIG_FLAG 0xAA

// URL do servidor (pode ser alterada via configuração futura)
#define DEFAULT_SERVER_URL "http://192.168.47.41/Tecnologia-Aplicada-Torra-do-Caf-/DadosTeste.php"

// Limites de tamanho
#define MAX_SSID_LENGTH 32
#define MAX_PASSWORD_LENGTH 64
#define MAX_DEVICE_KEY_LENGTH 32

// Configurações de debug
#define SERIAL_BAUD_RATE 115200
#define DEBUG_ENABLED true

#endif
