#include <Arduino.h>
#include <Wire.h>
#include <ArduinoJson.h>
#include <Adafruit_GFX.h>    // Core graphics library
#include <Adafruit_TFTLCD.h> // Hardware-specific library
#include <TouchScreen.h>
#include <SPI.h>
#include <DHT.h>
#include <IRremote.h>
#include <MQUnifiedsensor.h>

#define MINPRESSURE 1
#define MAXPRESSURE 1000

#define DHTPIN 22     // what digital pin we're connected to

#define DHTTYPE DHT11   // DHT 11
//#define DHTTYPE DHT22   // DHT 22  (AM2302), AM2321#define DHTTYPE DHT21   // DHT 21 (AM2301)

DHT dht(DHTPIN, DHTTYPE);


//const int sensorPin=10;
//Definitions
#define placa "Arduino UNO"
#define Voltage_Resolution 5
#define sensorPin A10 //Analog input 0 of your arduino
#define type "MQ-135" //MQ135
#define ADC_Bit_Resolution 10 // For arduino UNO/MEGA/NANO
#define RatioMQ135CleanAir 3.6//RS / R0 = 3.6 ppm  
//Declare Sensor
MQUnifiedsensor MQ135(placa, Voltage_Resolution, ADC_Bit_Resolution, sensorPin, type);

const int16_t I2C_MASTER = 0x42;
const int16_t I2C_SLAVE = 0x8;

int RELAY_PIN1 = 50;
int RELAY_PIN2 = 51;
int RELAY_PIN3 = 52;
int RELAY_PIN4 = 53;


// The control pins for the LCD can be assigned to any digital or
// analog pins...but we'll use the analog pins as this allows us to
// double up the pins with the touch screen (see the TFT paint example).
#define LCD_CS A3 // Chip Select goes to Analog 3
#define LCD_CD A2 // Command/Data goes to Analog 2
#define LCD_WR A1 // LCD Write goes to Analog 1
#define LCD_RD A0 // LCD Read goes to Analog 0

#define LCD_RESET A4 // Can alternately just connect to Arduino's reset pin

// When using the BREAKOUT BOARD only, use these 8 data lines to the LCD:
// For the Arduino Uno, Duemilanove, Diecimila, etc.:
//   D0 connects to digital pin 8  (Notice these are
//   D1 connects to digital pin 9   NOT in order!)
//   D2 connects to digital pin 2
//   D3 connects to digital pin 3
//   D4 connects to digital pin 4
//   D5 connects to digital pin 5
//   D6 connects to digital pin 6
//   D7 connects to digital pin 7
// For the Arduino Mega, use digital pins 22 through 29
// (on the 2-row header at the end of the board).

// Assign human-readable names to some common 16-bit color values:
#define  BLACK   0x0000
#define BLUE    0x001F
#define RED     0xF800
#define GREEN   0x07E0
#define CYAN    0x07FF
#define MAGENTA 0xF81F
#define YELLOW  0xFFE0
#define WHITE   0xFFFF

// Color definitions
#define ILI9341_BLACK       0x0000      /*   0,   0,   0 */
#define ILI9341_NAVY        0x000F      /*   0,   0, 128 */
#define ILI9341_DARKGREEN   0x03E0      /*   0, 128,   0 */
#define ILI9341_DARKCYAN    0x03EF      /*   0, 128, 128 */
#define ILI9341_MAROON      0x7800      /* 128,   0,   0 */
#define ILI9341_PURPLE      0x780F      /* 128,   0, 128 */
#define ILI9341_OLIVE       0x7BE0      /* 128, 128,   0 */
#define ILI9341_LIGHTGREY   0xC618      /* 192, 192, 192 */
#define ILI9341_DARKGREY    0x7BEF      /* 128, 128, 128 */
#define ILI9341_BLUE        0x001F      /*   0,   0, 255 */
#define ILI9341_GREEN       0x07E0      /*   0, 255,   0 */
#define ILI9341_CYAN        0x07FF      /*   0, 255, 255 */
#define ILI9341_RED         0xF800      /* 255,   0,   0 */
#define ILI9341_MAGENTA     0xF81F      /* 255,   0, 255 */
#define ILI9341_YELLOW      0xFFE0      /* 255, 255,   0 */
#define ILI9341_WHITE       0xFFFF      /* 255, 255, 255 */
#define ILI9341_ORANGE      0xFD20      /* 255, 165,   0 */
#define ILI9341_GREENYELLOW 0xAFE5      /* 173, 255,  47 */
#define ILI9341_PINK        0xF81F

/******************* UI details */
#define BUTTON_X 40
#define BUTTON_Y 100
#define BUTTON_W 60
#define BUTTON_H 30
#define BUTTON_SPACING_X 20
#define BUTTON_SPACING_Y 20
#define BUTTON_TEXTSIZE 2

// text box where numbers go
#define TEXT_X 10
#define TEXT_Y 10
#define TEXT_W 220
#define TEXT_H 50
#define TEXT_TSIZE 3
#define TEXT_TCOLOR ILI9341_MAGENTA
// the data (phone #) we store in the textfield
#define TEXT_LEN 12
char textfield[TEXT_LEN+1] = "";
uint8_t textfield_i=0;

#define YP A3  // must be an analog pin, use "An" notation!
#define XM A2  // must be an analog pin, use "An" notation!
#define YM 9   // can be a digital pin
#define XP 8   // can be a digital pin

#define TS_MINX 150
#define TS_MINY 120
#define TS_MAXX 920
#define TS_MAXY 940
// We have a status line for like, is FONA working
#define STATUS_X 10
#define STATUS_Y 65


Adafruit_TFTLCD tft(LCD_CS, LCD_CD, LCD_WR, LCD_RD, LCD_RESET);
TouchScreen ts = TouchScreen(XP, YP, XM, YM, 300);
// If using the shield, all control and data lines are fixed, and
// a simpler declaration can optionally be used:
// Adafruit_TFTLCD tft;

Adafruit_GFX_Button buttons[15];
Adafruit_GFX_Button settingsButton;
Adafruit_GFX_Button backHomeButton;
/* create 15 buttons, in classic candybar phone style */
char buttonlabels[15][5] = {"Ent.", "Clr.", "Esc.", "1", "2", "3", "4", "5", "6", "7", "8", "9", "*", "0", "#" };
uint16_t buttoncolors[15] = {ILI9341_DARKGREEN, ILI9341_DARKGREY, ILI9341_RED, 
                             ILI9341_BLUE, ILI9341_BLUE, ILI9341_BLUE, 
                             ILI9341_BLUE, ILI9341_BLUE, ILI9341_BLUE, 
                             ILI9341_BLUE, ILI9341_BLUE, ILI9341_BLUE, 
                             ILI9341_ORANGE, ILI9341_BLUE, ILI9341_ORANGE};

bool numericScreenOn = false;
bool numericScreenInit = false;
bool homeScreenOn = true;
bool homeScreenInit = false;
bool settingsScreenOn = false;
bool settingsScreenInit = false;
bool numericScreenOnTemperature = false;
bool numericScreenOnTemperature2 = false;
bool numericScreenOnHumidity = false;
bool numericScreenOnHumidity2 = false;

int mint,maxt,minh,maxh,minp,maxp,mins,maxs,ppm;
String temperatureSP="15";
String temperatureSP2="22";
String humiditySP="85";
String humiditySP2="95";

void setup(void) {
  Wire.begin(I2C_SLAVE);
  dht.begin();
  
  pinMode(RELAY_PIN1, OUTPUT);
  digitalWrite(RELAY_PIN1, HIGH);
  pinMode(RELAY_PIN2, OUTPUT);
  digitalWrite(RELAY_PIN2, HIGH);
  pinMode(RELAY_PIN3, OUTPUT);
  digitalWrite(RELAY_PIN3, HIGH);
  pinMode(RELAY_PIN4, OUTPUT);
  digitalWrite(RELAY_PIN4, HIGH);   
   
  Serial.begin(115200);
  //Serial.println(F("TFT LCD test"));
#ifdef USE_ADAFRUIT_SHIELD_PINOUT
  Serial.println(F("Using Adafruit 2.4\" TFT Arduino Shield Pinout"));
#else
  Serial.println(F("Using Adafruit 2.4\" TFT Breakout Board Pinout"));
#endif

  Serial.print("TFT size is "); Serial.print(tft.width()); Serial.print("x"); Serial.println(tft.height());

  tft.reset();
  
 // uint16_t identifier = 0x9341;    //Need hardcode here (IC)
  uint16_t identifier = tft.readID();
  if(identifier==0x0101)
      identifier=0x9341;  
  if(identifier == 0x9325) {
    Serial.println(F("Found ILI9325 LCD driver"));
  } else if(identifier == 0x4535) {
    Serial.println(F("Found LGDP4535 LCD driver"));
  }else if(identifier == 0x9328) {
    Serial.println(F("Found ILI9328 LCD driver"));
  } else if(identifier == 0x7575) {
    Serial.println(F("Found HX8347G LCD driver"));
  } else if(identifier == 0x9341) {
    Serial.println(F("Found ILI9341 LCD driver"));
  } else if(identifier == 0x8357) {
    Serial.println(F("Found HX8357D LCD driver"));
  } else {
    Serial.print(F("Unknown LCD driver chip: "));
    Serial.println(identifier, HEX);
    Serial.println(F("If using the Adafruit 2.4\" TFT Arduino shield, the line:"));
    Serial.println(F("  #define USE_ADAFRUIT_SHIELD_PINOUT"));
    Serial.println(F("should appear in the library header (Adafruit_TFT.h)."));
    Serial.println(F("If using the breakout board, it should NOT be #defined!"));
    Serial.println(F("Also if using the breakout, double-check that all wiring"));
    Serial.println(F("matches the tutorial."));
    return;
  }
   MQ135.setRegressionMethod(1); //_PPM =  a*ratio^b
   MQ135.init();
  Serial.print("Calibrating please wait.");
  float calcR0 = 0;
  for(int i = 1; i<=10; i ++){
    MQ135.update(); // Update data, the arduino will be read the voltage on the analog pin
    calcR0 += MQ135.calibrate(RatioMQ135CleanAir);
    Serial.print(".");
  }
  MQ135.setR0(calcR0/10);
  Serial.println("  done!.");
  
  if(isinf(calcR0)) {Serial.println("Warning: Conection issue founded, R0 is infite (Open circuit detected) please check your wiring and supply"); while(1);}
  if(calcR0 == 0){Serial.println("Warning: Conection issue founded, R0 is zero (Analog pin with short circuit to ground) please check your wiring and supply"); while(1);}
  
     
  tft.begin(identifier); 
  Wire.onRequest(sendInfo);
  Wire.onReceive(receiveEvent);
}

void loop(void) {
   MQ135.update();
   MQ135.setA(110.47); MQ135.setB(-2.862); 
  digitalWrite(13, HIGH);
  TSPoint p = ts.getPoint();
  digitalWrite(13, LOW);

  // if sharing pins, you'll need to fix the directions of the touchscreen pins
  pinMode(XM, OUTPUT);
  pinMode(YP, OUTPUT);
  
   if (p.z > MINPRESSURE && p.z < MAXPRESSURE) {
     // turn from 0->1023 to tft.width 
    p.x = map(p.x, TS_MINX, TS_MAXX, tft.width(), 0);
    p.y = map(p.y, TS_MINY, TS_MAXY, tft.height(), 0);
   }

       float h = dht.readHumidity();
       float t = dht.readTemperature();  
       h = h+4;
       float ppm = MQ135.readSensor();
       Serial.println("~~~~~~~~~~~~~~");            
       Serial.print(h);
       Serial.println("%");
       Serial.print(ppm);
       Serial.println("ppm");

      if (isnan(h) || isnan(t)) {
        Serial.println(F("Failed to read from DHT sensor!"));
        return;
      } 
         
    if(homeScreenOn){ 
        HomeScreen(String(ppm), String(t),String(h), temperatureSP, temperatureSP2, humiditySP, humiditySP2);
        //Settings Button
        if(homeScreenOn && p.x>0 && p.x<240 && p.y>130 && p.y<320 ){
          Serial.println("Settings Button Settings ON");
          settingsScreenOn = true;
          numericScreenOn = false;
          homeScreenOn = false;
          numericScreenOnTemperature = false;
          initAllScreens();          
          }    
      }      

    if(settingsScreenOn){      
      SettingsScreen(temperatureSP, temperatureSP2, humiditySP, humiditySP2);
         
         // BackHome Button x, y, w, h, outline, fill, text
        if(settingsScreenOn && p.x>0 && p.x<240 && p.y>290 && p.y<320){
          Serial.println("BackHome Button Home ON");
          numericScreenOnTemperature = false;
          settingsScreenOn = false;
          numericScreenOn = false;
          homeScreenOn = true;
          initAllScreens();
          }    
            
        //Temperature TextBox min
        if(settingsScreenOn && p.x>10 && p.x<110 && p.y>40 && p.y<80){
        Serial.println("Temperature min TextBox");
        numericScreenOnTemperature = true;
        numericScreenOnHumidity = false;
        settingsScreenOn = false;
        homeScreenOn = false;
        initAllScreens();
        }
        
        //Temperature TextBox max
        if(settingsScreenOn && p.x>125 && p.x<230 && p.y>40 && p.y<80){
        Serial.println("Temperature max TextBox");
        numericScreenOnTemperature2 = true;
        numericScreenOnHumidity = false;
        settingsScreenOn = false;
        homeScreenOn = false;
        initAllScreens();
        }
  
        //Humidity TextBox min
        if(settingsScreenOn && p.x>10 && p.x<110 && p.y>120 && p.y<200){
        Serial.println("Humidity min TextBox");
        numericScreenOnTemperature = false;
        numericScreenOnHumidity = true;
        settingsScreenOn = false;
        homeScreenOn = false;
        initAllScreens();
        }
        
        //Humidity TextBox max
        if(settingsScreenOn && p.x>125 && p.x<230 && p.y>120 && p.y<200){
        Serial.println("Humidity max TextBox");
        numericScreenOnTemperature = false;
        numericScreenOnHumidity2 = true;
        settingsScreenOn = false;
        homeScreenOn = false;
        initAllScreens();
        }       
    }
      //Serial.println("Temp. SP "+temperatureSP+"settingsScreen "+settingsScreenOn);
    if(numericScreenOnTemperature || numericScreenOnHumidity || numericScreenOnTemperature2 || numericScreenOnHumidity2){
      NumericKeyboardScreen(p);
    }
    
   //NumericKeyboardScreen(p);
    delay(500);
   
}
void sendInfo() { 
  MQ135.update();
  MQ135.setA(110.47); MQ135.setB(-2.862);
 float h = dht.readHumidity();
 h = h+4;
 float t = dht.readTemperature(); 
   int aq = MQ135.readSensor();
    DynamicJsonDocument doc(1024);
    JsonObject obj = doc.to<JsonObject>();
    obj["t"]= t;
    obj["h"]= h;
    obj["p"]= aq;    
    serializeJson(obj, Wire);
    Serial.println("Sending info >>>>>>");
    serializeJson(obj, Serial);
    Serial.println();
  //delay(1000);
}

void receiveEvent(int howMany) {
    String data="";
    MQ135.update();
    MQ135.setA(110.47); MQ135.setB(-2.862); 
     int air = MQ135.readSensor();
     //int air = analogRead(sensorPin);
     int hum = dht.readHumidity();
     hum = hum + 4;
     int tmp = dht.readTemperature();       
     int sw =1;
      
    while (0 < Wire.available()) {
      char c = Wire.read(); // receive byte as a character
       data += c;     
    } 
  Serial.println(data);
   DynamicJsonDocument doc(1024);  
   deserializeJson(doc, data);
  DeserializationError err = deserializeJson(doc, data);
  if (err) {
    Serial.println("~~~~~~~~~~~~~~~~~");
    Serial.print(F("deserializeJson() failed with code "));
    Serial.println(err.c_str());    
  }  
 JsonObject root = doc.as<JsonObject>();
 
for (JsonObject::iterator it=root.begin(); it!=root.end(); ++it) {
    String key = it->key().c_str();
    if (key=="t") {
              mint = doc["t"][0];
               maxt = doc["t"][1];
     String    tems = doc["t"][2];
     String    temw = doc["t"][3]; 
  rellaySW("Temperature", tems, temw, mint, maxt, tmp, RELAY_PIN1); 
    }if (key=="h") {
             minh = doc["h"][0];
             maxh = doc["h"][1];
   String    hums = doc["h"][2];
   String    humw = doc["h"][3];
   rellaySW("Humidity", hums, humw, minh, maxh, hum, RELAY_PIN2);      
    }if (key=="a") {
             minp = doc["a"][0];
             maxp = doc["a"][1];
   String    ppms = doc["a"][2];
   String    ppmw = doc["a"][3];
   rellaySW("Air Quality", ppms, ppmw, minp, maxp, air, RELAY_PIN3);      
    }if (key=="s") { 
               mins = doc["s"][0];
               maxs = doc["s"][1];
     String    sws  = doc["s"][2];
     String    sww  = doc["s"][3];
    rellaySW("Switching", sws, sww, mins, maxs, sw, RELAY_PIN4);      
    }
}

 //delay(1000);
}

void NumericKeyboardScreen(TSPoint p){
  if (!numericScreenInit){
        tft.fillScreen(BLACK);
      
      // create buttons
      for (uint8_t row=0; row<5; row++) {
        for (uint8_t col=0; col<3; col++) {
          buttons[col + row*3].initButton(&tft, BUTTON_X+col*(BUTTON_W+BUTTON_SPACING_X), 
                     BUTTON_Y+row*(BUTTON_H+BUTTON_SPACING_Y),    // x, y, w, h, outline, fill, text
                      BUTTON_W, BUTTON_H, ILI9341_WHITE, buttoncolors[col+row*3], ILI9341_WHITE,
                      buttonlabels[col + row*3], BUTTON_TEXTSIZE); 
          buttons[col + row*3].drawButton();
        }
      }
      
      // create 'text field'
      tft.drawRect(TEXT_X, TEXT_Y, TEXT_W, TEXT_H, ILI9341_WHITE);
      numericScreenInit = true;
    }

  // go thru all the buttons, checking if they were pressed
  for (uint8_t b=0; b<15; b++) {
    if (buttons[b].contains(p.x, p.y)) {
      //Serial.print("Pressing: "); Serial.println(b);
      buttons[b].press(true);  // tell the button it is pressed
    } else {
      buttons[b].press(false);  // tell the button it is NOT pressed
    }
  }

  // now we can ask the buttons if their state has changed
  for (uint8_t b=0; b<15; b++) {
    if (buttons[b].justReleased()) {
      // Serial.print("Released: "); Serial.println(b);
      buttons[b].drawButton();  // draw normal
    }
    
    if (buttons[b].justPressed()) {
        buttons[b].drawButton(true);  // draw invert!
        
        // if a numberpad button, append the relevant # to the textfield
        if (b >= 3) {
          if (textfield_i < TEXT_LEN) {
            textfield[textfield_i] = buttonlabels[b][0];
            textfield_i++;
      textfield[textfield_i] = 0; // zero terminate
            
           // fona.playDTMF(buttonlabels[b][0]);
          }
        }

        // clr button! delete char
        if (b == 1) {          
          textfield[textfield_i] = 0;
          if (textfield > 0) {
            textfield_i--;
            textfield[textfield_i] = ' ';
          }
        }

        // update the current text field
        Serial.println(textfield);
        tft.setCursor(TEXT_X + 2, TEXT_Y+10);
        tft.setTextColor(TEXT_TCOLOR, ILI9341_BLACK);
        tft.setTextSize(TEXT_TSIZE);
        tft.print(textfield);

        // Esc. Button
        if (b == 2) {
          //status(F("Hanging up"));
          //fona.hangUp();
          if(numericScreenOnTemperature || numericScreenOnTemperature2){
            //temperatureSP = textfield;
            settingsScreenOn = true;
            settingsScreenInit = false;
            numericScreenOnTemperature = false;
            numericScreenOnTemperature2 = false;
            }
            if(numericScreenOnHumidity || numericScreenOnHumidity2){
            //temperatureSP = textfield;
            settingsScreenOn = true;
            settingsScreenInit = false;
            numericScreenOnHumidity = false;
            numericScreenOnHumidity2 = false;
            }

            for(int i=0; i<=12;i++){
              textfield[i] = ' ';
            }
            textfield_i = 0;
        }
        // Enter Button
        if (b == 0) {
          //Serial.print("Calling "); Serial.print(textfield);
          if(numericScreenOnTemperature){
            temperatureSP = textfield;
            settingsScreenOn = true;
            settingsScreenInit = false;
            numericScreenOnTemperature = false;
            }
          if(numericScreenOnTemperature2){
            temperatureSP2 = textfield;
            settingsScreenOn = true;
            settingsScreenInit = false;
            numericScreenOnTemperature2 = false;
            }

           if(numericScreenOnHumidity){
            humiditySP = textfield;
            settingsScreenOn = true;
            settingsScreenInit = false;
            numericScreenOnHumidity = false;
            }
           if(numericScreenOnHumidity2){
            humiditySP2 = textfield;
            settingsScreenOn = true;
            settingsScreenInit = false;
            numericScreenOnHumidity2 = false;
            }
          for(int i=0; i<=12;i++){
              textfield[i] = ' ';
            }
            textfield_i = 0;
            
          
          //fona.callPhone(textfield);
          //temperatureSP,settingsScreenOn,settingsScreenInit
        }
        
      delay(100); // UI debouncing
    }
  }
  
  }

void HomeScreen(String co2, String temperature, String humidity,String tempSP, String tempSP2, String humSP, String humSP2){
  
  if(!homeScreenInit){
    tft.fillScreen(BLACK); 
                              // x, y, w, h, outline, fill, text
   // settingsButton.initButton(&tft, 120, 40, 230, 50, ILI9341_WHITE, ILI9341_DARKGREEN, ILI9341_WHITE, "Settings", BUTTON_TEXTSIZE); 
   // settingsButton.drawButton();


    tft.setCursor(10, 30);
    tft.setTextColor(ILI9341_BLUE);
    tft.setTextSize(2);
    tft.print("Air Quality");

   
    tft.setCursor(10, 130);
    tft.setTextColor(ILI9341_WHITE);
    tft.setTextSize(2);
    tft.print("Temperature");
      tft.drawCircle(180, 135, 10, RED);
      tft.fillCircle(180, 135, 10, RED);
          
   // tft.setCursor(10, 190);
   // tft.setTextColor(ILI9341_ORANGE);
   // tft.print("Set: Min:"+tempSP+" Max:"+tempSP2);
    
    tft.setCursor(10, 230);
    tft.setTextColor(ILI9341_WHITE);
    tft.setTextSize(2);
    tft.print("Humidity");    
      tft.drawCircle(180, 235, 10, GREEN);
      tft.fillCircle(180, 235, 10, GREEN);
      
   // tft.setCursor(10, 300);
   // tft.setTextColor(ILI9341_BLUE);
    //tft.print("Set: Min:"+humSP+" Max:"+humSP2);
 
          
    homeScreenInit = true;
    }
    
    tft.setCursor(10,50);
    tft.setTextSize(4);
    tft.setTextColor(ILI9341_WHITE,BLACK);
    tft.print(co2 +"  ");
    tft.setCursor(200, 50);
    tft.setTextSize(2);   
    tft.print("ppm");  
               // x, y, w, h, outline, fill, text
   // backHomeButton.initButton(&tft, 120,290, 230,50, ILI9341_WHITE, ILI9341_LIGHTGREY, ILI9341_WHITE, "Back Home", BUTTON_TEXTSIZE); 
   // tft.drawRect(0, 50, 250, 40, ILI9341_BLACK);
   
    tft.setCursor(10,150);
    tft.setTextSize(5);
    tft.setTextColor(ILI9341_WHITE,BLACK);
    tft.print(temperature + " C");


    tft.setCursor(10,250);
    tft.setTextSize(5);
    tft.setTextColor(ILI9341_WHITE,BLACK);
    tft.print(humidity + " %");

  }

void SettingsScreen(String temp, String temp2, String hum, String hum2){
  if(!settingsScreenInit){
    tft.fillScreen(BLACK);    
    tft.setTextColor(ILI9341_WHITE);
    tft.setTextSize(2);
    
    tft.setCursor(10, 10);
    tft.print("Temp. Min & Max");
    tft.drawRect(10, 30, 110, TEXT_H, ILI9341_WHITE);
    tft.drawRect(125, 30, 110, TEXT_H, ILI9341_WHITE);

    tft.setCursor(10, 100);
    tft.print("Humidity. Min & Max");
    tft.drawRect(10, 120, 110, TEXT_H, ILI9341_WHITE);  
    tft.drawRect(125, 120, 110, TEXT_H, ILI9341_WHITE);
                     // x, y, w, h, outline, fill, text
    backHomeButton.initButton(&tft, 120,290, 230,50, ILI9341_WHITE, ILI9341_LIGHTGREY, ILI9341_WHITE, "Back Home", BUTTON_TEXTSIZE); 
    backHomeButton.drawButton();
    
    settingsScreenInit = true;
    }
    tft.setCursor(11 + 2, 42);
    tft.setTextColor(TEXT_TCOLOR, ILI9341_BLACK);
    tft.setTextSize(TEXT_TSIZE);
    tft.print(temp);

    tft.setCursor(126 + 2, 42);
    tft.setTextColor(TEXT_TCOLOR, ILI9341_BLACK);
    tft.setTextSize(TEXT_TSIZE);
    tft.print(temp2);
    
    tft.setCursor(11 + 2, 132);
    tft.setTextColor(TEXT_TCOLOR, ILI9341_BLACK);
    tft.setTextSize(TEXT_TSIZE);
    tft.print(hum);
    
    tft.setCursor(126 + 2, 132);
    tft.setTextColor(TEXT_TCOLOR, ILI9341_BLACK);
    tft.setTextSize(TEXT_TSIZE);
    tft.print(hum2);   
  
  }

void initAllScreens(){
  numericScreenInit = false;
  homeScreenInit = false;
  settingsScreenInit = false; 
  }

  void getScreen(){
 
    
  }



void rellaySW(String lab, String sws, String sww, int mn, int mx, int w, int pin){
  if(mn>0 && mx>0){
      //Mannual Mode
      Serial.println();
      if(sws=="off"){
        Serial.print("----");    
        Serial.print(lab);
        Serial.println(" Mannual Mode------");
        Serial.println(w);        
        if(sww=="on"){  
          Serial.println("OFF");
          digitalWrite(pin, LOW);      
        }else if(sww=="off"){
          Serial.println("ON");
          digitalWrite(pin, HIGH);
        }
      }
        //Auto Mode
       if(sws=="on"){
         Serial.print("----");     
          Serial.println(lab);
         Serial.println(" Auto Mode------");
         Serial.println(w);
        if(w < mn ){
           Serial.println("ON");
           digitalWrite(pin, LOW);   
         }else if(w > mx){
            Serial.println("OFF");
            digitalWrite(pin, HIGH);
         }       
      } 
  }
}
