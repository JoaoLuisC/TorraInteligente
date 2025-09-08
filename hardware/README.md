# 🌡️ Sensor Torra Inteligente - Hardware

## 📋 Visão Geral

Este projeto contém o firmware para o sensor IoT da **Torra Inteligente**, desenvolvido para ESP8266. O dispositivo monitora e transmite dados de sensores durante o processo de torrefação do café.

## ✨ Funcionalidades

### 🔧 Configuração Automática
- **Captive Portal**: Configuração fácil via WiFi
- **Interface Web**: Interface amigável para configuração
- **Persistência**: Configurações salvas na EEPROM
- **Auto-reconexão**: Conecta automaticamente na inicialização

### 📡 Conectividade
- **WiFi robusto**: Sistema de reconexão automática
- **Timeout inteligente**: Evita travamentos
- **Monitoramento**: Verifica qualidade do sinal
- **Retry automático**: Tentativas de reconexão com limite

### 📊 Transmissão de Dados
- **Intervalo configurável**: Envio a cada 5 segundos (padrão)
- **Dados enriquecidos**: Inclui timestamp, RSSI e device_key
- **HTTP resiliente**: Timeout e retry para requisições
- **Logs detalhados**: Monitoramento completo via Serial

## 🚀 Como Usar

### 1️⃣ Primeira Configuração
1. **Flash o firmware** no ESP8266
2. **Conecte** à rede WiFi `TorraInteligente_XXXXXX`
3. **Abra qualquer site** no navegador (será redirecionado)
4. **Configure** as credenciais:
   - Nome da rede WiFi
   - Senha da rede
   - Chave única do dispositivo

### 2️⃣ Funcionamento Normal
- O dispositivo conecta automaticamente ao WiFi configurado
- Envia dados para o servidor a cada 5 segundos
- Monitora a conexão e reconecta se necessário
- Logs detalhados disponíveis via Serial Monitor

### 3️⃣ Reconfiguração
- Se a conexão falhar por mais de 3 tentativas, o dispositivo reinicia em modo configuração
- Acesse novamente a rede `TorraInteligente_XXXXXX` para reconfigurar

## 🔧 Configurações Técnicas

### 📡 Conectividade
```cpp
#define INTERVALO_ENVIO 5000      // 5 segundos entre envios
#define MAX_TENTATIVAS_WIFI 30    // 30 tentativas de conexão
#define TIMEOUT_HTTP 10000        // 10 segundos timeout HTTP
```

### 💾 Endereços EEPROM
```cpp
#define SSID_ADDR 0      // Endereço do SSID
#define PASS_ADDR 100    // Endereço da senha
#define KEY_ADDR 200     // Endereço da chave do dispositivo
#define CONFIG_ADDR 300  // Flag de configuração salva
```

### 📊 Dados Transmitidos
- `device_key`: Identificador único do dispositivo
- `valor`: Contador incremental (para teste)
- `timestamp`: Timestamp em milissegundos
- `rssi`: Força do sinal WiFi em dBm

## 🛠️ Melhorias Implementadas

### ✅ **Robustez**
- Validação completa de dados de entrada
- Tratamento de erros em todas as operações
- Sistema de retry para conexões
- Timeouts para evitar travamentos

### ✅ **Interface Melhorada**
- Design responsivo e moderno
- Validação de formulário
- Feedback visual do status
- Informações do dispositivo na tela

### ✅ **Eficiência**
- Gestão inteligente de memória EEPROM
- Otimização do loop principal
- Redução do consumo de energia
- Logs estruturados e informativos

### ✅ **Manutenibilidade**
- Código bem documentado
- Constantes configuráveis
- Separação clara de responsabilidades
- Funções modulares e reutilizáveis

## 🔍 Monitoramento e Debug

### 📊 Serial Monitor (115200 baud)
```
🌡️ Iniciando Sensor Torra Inteligente v2.0
📋 Configuração encontrada na EEPROM
SSID: MinhaRede
Device Key: SENSOR_001
✅ WiFi conectado!
IP: 192.168.1.100
Sinal: -45 dBm
🚀 Sistema pronto para envio de dados!
📤 Enviando dados: device_key=SENSOR_001&valor=1&timestamp=15000&rssi=-45
📥 Resposta HTTP 200: {"status":"success"}
✅ Dados enviados com sucesso!
```

### 🌐 Interface Web
- **URL**: `http://192.168.4.1` (modo configuração)
- **Recursos**: Configuração, status do dispositivo, informações de rede

## 📱 Compatibilidade

### 🔧 Hardware Suportado
- **ESP8266** (todas as variantes)
- **NodeMCU v3**
- **Wemos D1 Mini**
- **ESP-01** (com adaptações)

### 📚 Bibliotecas Necessárias
```cpp
#include <ESP8266WiFi.h>      // WiFi do ESP8266
#include <ESP8266WebServer.h>  // Servidor web
#include <ESP8266HTTPClient.h> // Cliente HTTP
#include <DNSServer.h>         // DNS para captive portal
#include <EEPROM.h>           // Persistência de dados
#include <WiFiClientSecure.h>  // HTTPS (futuro)
#include <ArduinoJson.h>      // JSON (futuro)
```

## 🚧 Próximas Melhorias

### 🔮 Versão 3.0 (Planejada)
- [ ] **HTTPS**: Comunicação segura com certificados
- [ ] **JSON**: Formato de dados mais estruturado
- [ ] **OTA**: Atualizações over-the-air
- [ ] **Deep Sleep**: Economia de bateria
- [ ] **Sensores reais**: DHT22, termopares, etc.
- [ ] **WebSocket**: Comunicação em tempo real
- [ ] **Configuração avançada**: Múltiplos servidores, intervals personalizados

### 🎯 Sensores Futuros
- **Temperatura**: Termopar tipo K para alta temperatura
- **Umidade**: DHT22 ou SHT30
- **Pressão**: BMP280
- **Fumaça**: MQ-2
- **Peso**: Load cell com HX711

## 📞 Suporte

Para dúvidas ou problemas:
1. Verifique os logs no Serial Monitor
2. Confirme as configurações de rede
3. Teste a conectividade do servidor
4. Verifique a documentação do projeto principal

---

> **Desenvolvido para o Projeto Torra Inteligente**  
> Instituto Federal do Sul de Minas - Campus Machado  
> 2025
