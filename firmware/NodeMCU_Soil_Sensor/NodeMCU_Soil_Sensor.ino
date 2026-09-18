#include <ESP8266WiFi.h>
#include <DNSServer.h>
#include <WiFiManager.h> 
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
#include <ESP8266HTTPClient.h>
#include <Wire.h>
#include <ArduinoJson.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <LiquidCrystal_I2C.h>
#include "DHT.h"

#define DHTPIN 14
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);


#define ONE_WIRE_BUS 2
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

float Celsius = 0;
float Fahrenheit = 0;

const uint16_t port = 80;
//const char* host = "akash.net.np";
const char* host = "192.168.1.99";
#define TRIGGER_PIN 0

const int msensor = A0;  /* Soil moisture sensor O/P pin */
int sensor_pin = A0; // moisture sensor is connected with the analog pin A1 of the Arduino
int msvalue = 0; // moisture sensor value 
int dryValue = 675;
int wetValue = 220; //220
int friendlyDryValue = 0;
int friendlyWetValue = 100;

LiquidCrystal_I2C lcd(0x27,16,2);  // set the LCD address to 0x27 for a 16 chars and 2 line display

int period = 5000;
unsigned long time_now = 0;
int i = 0;
int j = 0;
int k = 0;
int delayTime2 = 300; 


void setup(){
  Serial.begin(115200);
  pinMode(TRIGGER_PIN, INPUT);
  sensors.begin();
  lcd.init();
  lcd.backlight();
  
  lcd.setCursor(0,0);
  lcd.print("Connecting WiFi!");    
  Serial.println("\n Connecting to WiFi...");
  delay(500);
  WiFiManager wifiManager;
  //wifiManager.resetSettings();
  wifiManager.autoConnect("SmartWifi", "password");   
  
  lcd.setCursor(0,0);
  lcd.print("Wifi connected !");
  Serial.println("Successfully connected...");
  delay(500);
  
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  lcd.setCursor(0,0);
  lcd.print("IP:");lcd.print(WiFi.localIP());
  delay(500);
  Serial.println("\n Starting");
  
  lcd.setCursor(0,0);
  lcd.print("Starting...     ");
  delay(500);

  lcd.setCursor(0,0);
  lcd.print("Analysis data   ");
  delay(1000);
  lcd.clear(); 

}


void loop(){
  float moisture_percentage;
  int sensor_analog;
  sensor_analog = analogRead(sensor_pin);
  moisture_percentage = ( 100 - ( (sensor_analog/1023.00) * 100 ) );    

  sensors.requestTemperatures();
  Celsius = sensors.getTempCByIndex(0);
  //Fahrenheit = sensors.toFahrenheit(Celsius);
  
  ms_data(moisture_percentage, Celsius);
  

  if (millis() > time_now + period){ 
    time_now = millis();
      if (WiFi.status() == WL_CONNECTED){ 
          Serial.println("---------Send----------"); 
          saveToServer(moisture_percentage, Celsius);     
          Serial.println("---------end----------"); 
       }
  }



}
void ms_data(float data, float indata){
  //Serial.print("Soil Moisture:"); Serial.println(data);
  
  lcd.setCursor(0,0);
  lcd.print("                ");
  lcd.setCursor(0,1);
  //lcd.print("S:");
  lcd.print(data);lcd.print("% ");

  Serial.print(indata);
  Serial.println(" °C");
 //lcd.print(" T:");
 lcd.print(indata);lcd.print("°C");
   
  delay(500);
}

void saveToServer(float data, float indata){

  WiFiClient client;
  
  if (isnan(data)) {
    Serial.println(F("Failed to read sensor!"));
    //scrollInFromRight(0, "Failed to read sensor!");
    lcd.setCursor(0,0); 
    lcd.print("Sensor Error !  ");
    return;
  }

  
  if (!client.connect(host, port)) {
    Serial.println("connection failed");
    //scrollInFromRight(0, "connection failed");
    lcd.setCursor(0,0);
    lcd.print("connection fail!");   
    //delay(5000);
    //return;
  }   
  Serial.printf("\n[Connecting to %s ... ", host);
  //scrollInFromRight(0, "Connecting to host "); 
  lcd.setCursor(0,0);
  lcd.print("connection host!");   

  if (client.connect(host, port)){
      Serial.println("connected");
      Serial.println("[Sending a request]"); 
      //scrollInFromRight(0, "Sending a request"); 
      lcd.setCursor(0,0);
      lcd.print("Sending request!");     
      client.print("GET /iot/add_data.php?sensor=");
      client.print("soil");
      client.print("&data=");
      client.print(data);
      client.print("&indata=");
      client.print(indata);
      client.print(" HTTP/1.1\r\n");
      client.print("Host: ");
      client.print(host);
      client.print("\r\n");
      client.print("Connection: close\r\n");
      client.print("\r\n");
      Serial.println("[Response:]");
      Serial.println(data);
      //scrollInFromRight(0, "Response request" );
      lcd.setCursor(0,0);
      lcd.print("Response receive");
     while (client.connected() || client.available()){
          if (client.available()) {
            String line = client.readStringUntil('\n');
            Serial.println(line);                                      
          }
        }
    client.stop();
    Serial.println("\n[Disconnected]");
    //scrollInFromRight(0, "Disconnected" );
    lcd.setCursor(0,0); 
    lcd.print("Disconnected   !");  
  }else{
    Serial.println("connection failed!]");
    //scrollInFromRight(0, "connection failed!" );
    lcd.setCursor(0,0);
    lcd.print("connection fail!");
    client.stop();
  }
  
}
void reciveData(String command) { 
   DynamicJsonDocument doc(256);
   deserializeJson(doc, command);        
    String type = doc["type"];
    String msg = doc["msg"];
    Serial.println("Json Decode:");
    Serial.println(type); 
    lcd.setCursor(0,0);
    lcd.print(msg);  
   
}

void scrollInFromRight (int line, char str1[]) {
 
  i = strlen(str1);
  
  for (j = 16; j >= 0; j--) {  
    lcd.setCursor(0, line);  
    for (k = 0; k <= 15; k++) {  
      lcd.print(" ");
    }
    
    lcd.setCursor(j, line);  
    lcd.print(str1);  
    delay(delayTime2);  
  }

}


void scrollInFromLeft (int line, char str1[]) {
  i = 40 - strlen(str1);  
  line = line - 1;
  
  for (j = i; j <= i + 16; j++) {    
      for (k = 0; k <= 15; k++) {
        lcd.print(" ");
      }    
      lcd.setCursor(j, line);
      lcd.print(str1);
      delay(delayTime2);
  }

}
