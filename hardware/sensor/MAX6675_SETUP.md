#include "max6675.h"

// MAX6675 pin connections for ESP8266
int thermoDO = 14;   // GPIO12 (D6)12
int thermoCS = 12;   // GPIO13 (D7)13
int thermoCLK = 13;  // GPIO14 (D5)14

MAX6675 thermocouple(thermoCLK, thermoCS, thermoDO);

void setup() {
  Serial.begin(115200);
  Serial.println("MAX6675 ESP8266 test");
  
  // Wait for MAX chip to stabilize
  delay(500);
}

void loop() {
  // Basic readout test, just print the current temp
  Serial.print("C = "); 
  Serial.println(thermocouple.readCelsius());
 
  // For the MAX6675 to update, you must delay AT LEAST 250ms between reads!
  delay(1000);
}