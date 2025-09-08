#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <DNSServer.h>
#include <EEPROM.h>

#define DNS_PORT 53

ESP8266WebServer server(80);
DNSServer dnsServer;

String ssid = "";
String password = "";
String deviceKey = "";

// Variáveis de controle
bool conectado = false;
unsigned long ultimoEnvio = 0;
int contador = 0;

// Página de configuração
const char* htmlForm = R"rawliteral(
<!DOCTYPE html>
<html>
  <head><title>Configurar Dispositivo</title></head>
  <body>
    <h2>Configurar Rede e Chave</h2>
    <form action="/save" method="POST">
      <label>Nome do Wi-Fi:</label><br>
      <input type="text" name="ssid"><br><br>
      <label>Senha:</label><br>
      <input type="password" name="password"><br><br>
      <label>Chave do Dispositivo:</label><br>
      <input type="text" name="key"><br><br>
      <input type="submit" value="Salvar e Conectar">
    </form>
  </body>
</html>
)rawliteral";

// Salva os dados na EEPROM
void saveConfig(String ssid, String pass, String key) {
  EEPROM.begin(512);
  EEPROM.put(0, ssid);
  EEPROM.put(100, pass);
  EEPROM.put(200, key);
  EEPROM.commit();
  EEPROM.end();
}

void handleRoot() {
  server.send(200, "text/html", htmlForm);
}

void handleSave() {
  ssid = server.arg("ssid");
  password = server.arg("password");
  deviceKey = server.arg("key");

  saveConfig(ssid, password, deviceKey);

  server.send(200, "text/html", "<h2>Salvo! Conectando à rede Wi-Fi...</h2><p>Aguarde 10 segundos.</p>");

  delay(2000);

  WiFi.softAPdisconnect(true);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid.c_str(), password.c_str());

  Serial.println("Conectando à rede Wi-Fi do cliente...");

  int tentativas = 0;
  while (WiFi.status() != WL_CONNECTED && tentativas < 20) {
    delay(500);
    Serial.print(".");
    tentativas++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n✅ Conectado com sucesso!");
    Serial.print("IP do dispositivo: ");
    Serial.println(WiFi.localIP());
    conectado = true;
  } else {
    Serial.println("\n❌ Falha ao conectar. Reiniciando modo AP.");
    delay(3000);
    ESP.restart();
  }
}

void setup() {
  Serial.begin(115200);

  WiFi.mode(WIFI_AP);
  WiFi.softAP("Michelangelo_Connect");

  delay(100);
  IPAddress myIP = WiFi.softAPIP();
  Serial.print("AP ativo! IP: ");
  Serial.println(myIP);
  Serial.println("Conecte-se e acesse qualquer site!");

  dnsServer.start(DNS_PORT, "*", myIP);

  server.on("/", handleRoot);
  server.on("/save", HTTP_POST, handleSave);
  server.begin();
}

void loop() {
  dnsServer.processNextRequest();
  server.handleClient();

  if (conectado && WiFi.status() == WL_CONNECTED) {
    unsigned long agora = millis();
    if (agora - ultimoEnvio > 300) { // Envia a cada 5 segundos
      ultimoEnvio = agora;

      HTTPClient http;
      WiFiClient client;
      String url = "http://192.168.47.41/Tecnologia-Aplicada-Torra-do-Caf-/DadosTeste.php";
      http.begin(client, url);
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");

      String dados = "valor=" + String(contador);
      int resposta = http.POST(dados);

      if (resposta > 0) {
        String retorno = http.getString();
        Serial.println("Resposta do servidor:");
        Serial.println(retorno);
      } else {
        Serial.println("❌ Erro ao enviar dados.");
      }

      http.end();
      contador++;
    }
  }
}
