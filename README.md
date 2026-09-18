# ⚡ IoT Smart Climate Control System

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![ESP8266 / ESP32](https://img.shields.io/badge/ESP8266%2FESP32-000000?style=for-the-badge&logo=espressif&logoColor=white)](https://www.espressif.com/)
[![Arduino Mega](https://img.shields.io/badge/Arduino-Mega2560-00979D?style=for-the-badge&logo=arduino&logoColor=white)](https://www.arduino.cc/)

The **IoT Smart Climate Control System** is an enterprise-grade, hardware-to-cloud **PHP & MySQL Telemetry & Actuator Management Platform** specifically engineered for environmental climate regulation, mushroom farming, smart agriculture, soil moisture management, and automated industrial relay control. 

Designed to interface with Arduino Mega 2560 and NodeMCU (ESP8266) microcontrollers, it processes real-time sensor telemetry streams, renders interactive Highcharts analytics, and serves bidirectional operational state commands back to edge devices over Wi-Fi.

---

## 📺 Live Video Demo & Dashboard Walkthrough

Watch the complete project demonstration showing the live PHP climate control dashboard, real-time telemetry graphs, and hardware actuator relays in action:

[![PHP Climate Control Dashboard Demo](https://img.youtube.com/vi/f8a_zdn-VkQ/maxresdefault.jpg)](https://youtu.be/f8a_zdn-VkQ)

▶️ **Watch on YouTube:** [https://youtu.be/f8a_zdn-VkQ](https://youtu.be/f8a_zdn-VkQ)

---

## 🌟 Key Features

| Feature | Description |
| :--- | :--- |
| 📊 **Telemetry Ingestion API** | High-performance endpoints (`add_data.php`, `update_data.php`) for logging temperature, humidity, soil moisture, and air quality (PPM). |
| 🎛️ **Actuator & Relay Control** | Dedicated REST endpoint (`get_data.php`) delivering real-time relay switch commands (exhaust fans, heaters, humidifiers/pumps, grow lights). |
| ⏱️ **Automated Scheduler** | `scheduler.php` background task engine executing timed relay triggers, automated irrigation cycles, and climate threshold routines. |
| 💧 **Soil & Climate Analytics** | Specialized data query endpoints (`get_soil_info.php`) outputting JSON time-series streams for interactive Highcharts visualization. |
| 🔐 **Settings & Device Management** | Protected administrative dashboard for toggling relay states live and setting dynamic environmental thresholds. |
| 📡 **Wi-Fi Captive Portal** | Integrated `WiFiManager` capability on NodeMCU firmware allowing headless Wi-Fi provisioning without hardcoding SSIDs. |

---

## 🛠️ Required Hardware Components & Bill of Materials (BOM)

### 🔌 Microcontroller Boards & Gateways
| Component | Quantity | Specification | Description |
| :--- | :---: | :---: | :--- |
| **Arduino Mega 2560** | 1 | ATmega2560 (16 MHz, 54 I/O Pins) | Primary physical controller & I/O expander for local sensor acquisition & relay driving. |
| **NodeMCU (ESP8266 v3)** | 2 | ESP-12E (80/160 MHz, Wi-Fi 802.11 b/g/n) | Wi-Fi IoT gateway boards handling HTTP POST/GET server communication & data polling. |

### 🌡️ Sensors & Telemetry Probes
| Component | Quantity | Interface / Pin | Description |
| :--- | :---: | :---: | :--- |
| **DHT22 / DHT11** | 1 | Digital Pin 14 | High-precision ambient air temperature & relative humidity sensor. |
| **MQ-135 Sensor** | 1 | Analog Pin A0 | Air Quality, CO2, Smoke, Ammonia, and Hazardous Gas sensor. |
| **DS18B20 Probe** | 1 | OneWire Pin D2 | Waterproof stainless-steel temperature probe for soil & mushroom substrate. |
| **Soil Moisture Sensor** | 1 | Analog Pin A0 | Substrate moisture level sensor for irrigation control. |

### ⚡ Actuators, Displays & Peripherals
| Component | Quantity | Specification | Description |
| :--- | :---: | :---: | :--- |
| **4-Channel Relay Module** | 1 | 5V Optocoupler (10A 250VAC) | Controls high-voltage AC/DC equipment (Exhaust Fan, Heater, Humidifier/Pump, Light). |
| **16x2 Character LCD** | 2 | I2C Bus (Address `0x27`) | Real-time local status display showing IP address, temperature, humidity & Wi-Fi state. |
| **Power Supply Unit** | 1 | 12V 2A DC / 5V Step-Down | Delivers stable DC power to NodeMCU, Arduino Mega, sensors, and 5V relay coils. |
| **Breadboard & Cable Harness** | - | Dupont Jumper Cables | Male-to-Female and Male-to-Male wiring harness. |

---

## 🤖 Firmware & Hardware Controller Architecture

The hardware layer adopts a distributed multi-controller architecture connected over Wi-Fi HTTP requests and local I2C/OneWire protocols:

```
                                  +---------------------------------------+
                                  |     NodeMCU (ESP8266 - Gateway)       |
                                  |  (WiFiManager + HTTP Sync Client)     |
                                  +---------------------------------------+
                                                     |
                                            HTTP GET Telemetry
                                                     |
                                                     v
 +----------------------------+           +-------------------------------+           +----------------------------+
 | Arduino Mega 2560          |           |   PHP Web Server & MySQL DB   |           | NodeMCU Substrate Monitor  |
 | (DHT22 + MQ135 + 4-Relays) |           |  (update_data.php / db.php)   |           | (DS18B20 + Soil Moisture)  |
 +----------------------------+           +-------------------------------+           +----------------------------+
               |                                     ^                                              |
               v                                     |                                              v
      [ Local 16x2 LCD ]                             +<----------------------------------- [ Local 16x2 LCD ]
```

### Firmware Sketches Breakdown

1. 🖥️ **`Mega_DHT22_MQ135`** ([`firmware/Mega_DHT22_MQ135/Mega_DHT22_MQ135.ino`](file:///D:/wamp64/www/iot/firmware/Mega_DHT22_MQ135/Mega_DHT22_MQ135.ino)):
   - **Target**: Arduino Mega 2560
   - **Role**: Main physical controller & local display driver. Reads DHT22 (Temp & Humidity) + MQ135 (Air Quality/CO2), controls 4-relay outputs (fans, heaters, humidifiers, pumps), and updates LCD screen.

2. 📡 **`NodeMCU_Auto_Wifi`** ([`firmware/NodeMCU_Auto_Wifi/NodeMCU_Auto_Wifi.ino`](file:///D:/wamp64/www/iot/firmware/NodeMCU_Auto_Wifi/NodeMCU_Auto_Wifi.ino)):
   - **Target**: NodeMCU (ESP8266)
   - **Role**: Cloud & Server Gateway. Manages Wi-Fi connections via captive portal `WiFiManager`, syncs ambient telemetry with server (`update_data.php`), and polls relay control commands (`get_data.php`).

3. 💧 **`NodeMCU_Soil_Sensor`** ([`firmware/NodeMCU_Soil_Sensor/NodeMCU_Soil_Sensor.ino`](file:///D:/wamp64/www/iot/firmware/NodeMCU_Soil_Sensor/NodeMCU_Soil_Sensor.ino)):
   - **Target**: NodeMCU (ESP8266)
   - **Role**: Substrate & Soil Monitor. Reads analog soil moisture & DS18B20 waterproof temperature probe, displays statistics on 16x2 I2C LCD, and posts data to server (`add_data.php?sensor=soil`).

---

## 🗄️ Complete Database Structure (MySQL DDL)

Import this DDL schema to set up the MySQL database (`dev_iot`):

```sql
CREATE DATABASE IF NOT EXISTS `dev_iot` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dev_iot`;

-- 1. SETTING TABLE (Actuator states, thresholds & relays)
CREATE TABLE IF NOT EXISTS `setting` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `value` VARCHAR(255) NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `setting` (`id`, `name`, `value`) VALUES
(1, 'fan_sw', 'off'), (2, 'light_sw', 'off'), (3, 'pump_sw', 'off'), (4, 'heater_sw', 'off'),
(5, 'temp_threshold', '30'), (6, 'humidity_threshold', '70'), (7, 'moisture_threshold', '40');

-- 2. DH11 TABLE (DHT11/DHT22 Ambient Weather Telemetry)
CREATE TABLE IF NOT EXISTS `dh11` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tem` FLOAT DEFAULT '0',
  `hum` FLOAT DEFAULT '0',
  `ppm` FLOAT DEFAULT '0',
  `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `dh11` (`id`, `tem`, `hum`, `ppm`) VALUES (100, 25.0, 60.0, 400.0);

-- 3. SOIL TABLE (Soil Moisture & Substrate Temperature Logs)
CREATE TABLE IF NOT EXISTS `soil` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `value` FLOAT DEFAULT '0',
  `tem` FLOAT DEFAULT '0',
  `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. USERS TABLE (Dashboard Authentication)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user` VARCHAR(50) NOT NULL UNIQUE,
  `pwd` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`user`, `pwd`) VALUES ('admin', 'password');
```

---

## 🔌 Complete RESTful API Specifications

### 1. Ingest Substrate Telemetry (`add_data.php`)
Microcontrollers submit HTTP GET telemetry requests:
```http
GET /iot/add_data.php?sensor=soil&data=65.0&indata=24.5 HTTP/1.1
Host: 192.168.1.99
```
- **Parameters**:
  - `sensor`: Target database table (`soil`, `dh11`).
  - `data`: Substrate moisture level (%).
  - `indata`: Substrate temperature (°C).
- **Response**: `{"type":"success","msg":"Successfully"}`

### 2. Update Ambient Telemetry (`update_data.php`)
Update live climate values:
```http
GET /iot/update_data.php?tem=26.4&hum=72.0&ppm=412.0 HTTP/1.1
Host: 192.168.1.99
```
- **Parameters**: `tem` (Temperature °C), `hum` (Humidity %), `ppm` (Air Quality PPM).
- **Response**: `{"type":"success","message":"Your data has been saved! successfully"}`

### 3. Fetch Actuator Control States (`get_data.php`)
Edge gateways poll this endpoint to read current relay switch states:
```http
GET /iot/get_data.php HTTP/1.1
Host: 192.168.1.99
```
- **Response**:
  ```json
  {
    "fan_sw": 10,
    "light_sw": 10,
    "pump_sw": 11,
    "heater_sw": 10
  }
  ```
  *(Note: `11` = Relay ON, `10` = Relay OFF)*

---

## ⚙️ Software Installation & Deployment Guide

### Prerequisites
- PHP 7.4+ or PHP 8.x
- MySQL / MariaDB Server
- Web Server (WAMP, XAMPP, Nginx, or Apache)

### Step-by-Step Setup

1. **Clone Repository to Web Root**:
   ```bash
   git clone https://github.com/technicguy/iot-smart-climate-control-system.git D:/wamp64/www/iot
   ```
2. **Database Initialization**:
   Import the DDL code provided in the **Database Structure** section into your MySQL server (`dev_iot`).
3. **Configure Database Connection (`config.php`)**:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'dev_iot');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('URL', 'http://localhost/iot/');
   ```
4. **Flash Microcontroller Firmware**:
   - Open `.ino` sketches in Arduino IDE.
   - Install required libraries (`WiFiManager`, `LiquidCrystal_I2C`, `DallasTemperature`, `OneWire`, `ArduinoJson`).
   - Set the `host` IP variable in `NodeMCU_Soil_Sensor.ino` and `NodeMCU_Auto_Wifi.ino` to match your server IP.

---

## 📁 Repository Directory Structure

```
iot/
├── firmware/                              # 🤖 Microcontroller Source Code
│   ├── Mega_DHT22_MQ135/                  # Arduino Mega physical display & relay controller
│   │   └── Mega_DHT22_MQ135.ino
│   ├── NodeMCU_Auto_Wifi/                 # NodeMCU server gateway & Wi-Fi manager
│   │   └── NodeMCU_Auto_Wifi.ino
│   └── NodeMCU_Soil_Sensor/               # NodeMCU soil moisture & temp probe telemetry
│       └── NodeMCU_Soil_Sensor.ino
├── index.php                              # Web Dashboard & Live Climate Panel
├── config.php                             # Database connection & timezone configuration
├── add_data.php                           # Telemetry ingestion endpoint for sensor nodes
├── get_data.php                           # Edge device state retriever (relays, pumps, switches)
├── update_data.php                        # Live climate updater (DHT11/DHT22 temp, humidity, ppm)
├── get_soil_info.php                      # Endpoint returning soil moisture & temp JSON history
├── scheduler.php                          # Timed task executor for climate & irrigation cycles
├── include/                               # Core backend classes & layout templates
│   ├── class.php                          # Main `Iot` helper class
│   ├── header.php                         # Header template
│   └── footer.php                         # Footer template
├── css/                                   # CSS stylesheets & Bootstrap assets
├── js/                                    # Dashboard JavaScript & charting handlers
└── lib/                                   # Highcharts, FontAwesome, etc.
```

---

## 📬 Contact & Support

For support, inquiries, or collaboration, feel free to reach out across any of these channels:

- 📧 **Email:** [technicguy@gmail.com](mailto:technicguy@gmail.com)
- 🌐 **Website:** [https://esanshar.com.np/](https://esanshar.com.np/)
- 📞 **Phone:** [+977 986 445 0173](tel:+9779864450173)
- 💬 **WhatsApp:** [+977 984 470 7950](https://wa.me/9779844707950)
- 💼 **LinkedIn:** [linkedin.com/in/technicguy](https://www.linkedin.com/in/technicguy/)
- 👤 **Facebook:** [facebook.com/imakashgc](https://www.facebook.com/imakashgc)
- 📺 **YouTube Channels:**
  - 🎵 **Sound & Frequency:** [Mystic Sound Journeys](https://www.youtube.com/@MysticSoundJourneys?sub_confirmation=1)
  - 👶 **Kids Content:** [MummaBaba](https://www.youtube.com/@MummaBaba?sub_confirmation=1)
