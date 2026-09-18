# ⚡ IoT Smart Climate Control System

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![ESP8266 / ESP32](https://img.shields.io/badge/ESP8266%2FESP32-000000?style=for-the-badge&logo=espressif&logoColor=white)](https://www.espressif.com/)
[![Arduino Mega](https://img.shields.io/badge/Arduino-Mega2560-00979D?style=for-the-badge&logo=arduino&logoColor=white)](https://www.arduino.cc/)

The **IoT Smart Climate Control System** is a complete hardware-to-cloud **PHP & MySQL Telemetry & Actuator Management Platform** built for environmental monitoring, mushroom farming climate regulation, soil moisture tracking, and automated relay/device control. Designed to interface with Arduino Mega and NodeMCU (ESP8266) microcontrollers, it processes incoming sensor telemetry streams and serves real-time operational state commands back to edge devices.

---

## 📺 Live Video Demo & Dashboard Walkthrough

Watch the complete project demonstration showing the live PHP climate control dashboard, real-time telemetry graphs, and hardware actuator relays in action:

[![PHP Climate Control Dashboard Demo](https://img.youtube.com/vi/f8a_zdn-VkQ/maxresdefault.jpg)](https://youtu.be/f8a_zdn-VkQ)

▶️ **Watch on YouTube:** [https://youtu.be/f8a_zdn-VkQ](https://youtu.be/f8a_zdn-VkQ)

---

## 🛠️ Required Hardware Components & Bill of Materials (BOM)

### 🔌 Microcontroller Boards & Gateways
| Component | Quantity | Description |
| :--- | :---: | :--- |
| **Arduino Mega 2560** | 1 | Primary physical controller & I/O expander for sensor reading & relay manipulation. |
| **NodeMCU (ESP8266 v3)** | 2 | Wi-Fi IoT gateway boards handling server HTTP POST/GET telemetry & data polling. |

### 🌡️ Sensors & Telemetry Probes
| Component | Quantity | Interface | Description |
| :--- | :---: | :---: | :--- |
| **DHT22 / DHT11** | 1 | Digital Pin 14 | Ambient Air Temperature & Relative Humidity sensor. |
| **MQ-135** | 1 | Analog Pin A0 | Air Quality, CO2, Smoke, and Hazardous Gas sensor. |
| **DS18B20** | 1 | OneWire Pin D2 | Waterproof Probe for soil/substrate temperature monitoring. |
| **Capacitive / Resistive Soil Moisture Sensor** | 1 | Analog Pin A0 | Substrate moisture level sensor. |

### ⚡ Actuators, Displays & Hardware Peripherals
| Component | Quantity | Description |
| :--- | :---: | :--- |
| **4-Channel 5V Relay Module** | 1 | Controls high-voltage AC/DC loads (Exhaust Fan, Heater, Humidifier/Pump, Light). |
| **16x2 Character LCD Display (I2C 0x27)** | 2 | Displays real-time metrics, connection status, and IP addresses locally. |
| **12V / 5V Power Supply Unit** | 1 | Delivers stable power to NodeMCU, Arduino Mega, sensors, and relay coils. |
| **Jumper Wires & Breadboard / PCB** | - | Male-to-Female & Male-to-Male jumper cables for pin connections. |

---

## 🤖 Firmware & Hardware Controller Architecture

This system uses a modular multi-controller hardware setup connected over Wi-Fi and I2C/Serial:

| Module / Sketch | Microcontroller | Primary Role |
| :--- | :--- | :--- |
| 🖥️ **`Mega_DHT22_MQ135`** | **Arduino Mega 2560** | Main physical controller & local display driver. Reads DHT22 (Temp & Humidity) + MQ135 (Air Quality/CO2), controls 4-relay outputs (fans, heaters, humidifiers, pumps), and updates LCD. |
| 📡 **`NodeMCU_Auto_Wifi`** | **NodeMCU (ESP8266)** | Cloud & Server Gateway. Manages Wi-Fi connections via `WiFiManager`, syncs ambient telemetry with the server (`update_data.php`), and polls relay control commands (`get_data.php`). |
| 💧 **`NodeMCU_Soil_Sensor`** | **NodeMCU (ESP8266)** | Soil & Substrate Monitor. Reads analog soil moisture & DS18B20 waterproof temperature probe, displays stats on 16x2 I2C LCD, and posts data to server (`add_data.php?sensor=soil`). |

---

## 🌟 Key Features

| Feature | Description |
| :--- | :--- |
| 📊 **Telemetry Ingestion API** | Endpoints (`add_data.php`, `update_data.php`) to log temperature, humidity, soil moisture, and gas/PPM readings. |
| 🎛️ **Actuator & Relay Control** | Dedicated endpoint (`get_data.php`) delivering real-time actuator commands (fans, heaters, pumps, lights). |
| ⏱️ **Automated Scheduler** | `scheduler.php` engine for timed relay triggers, climate control cycles, and scheduled automation routines. |
| 💧 **Soil & Climate Analytics** | Specialized query endpoints (`get_soil_info.php`) generating historical time-series datasets for interactive charts. |
| 🔐 **Settings & Device Management** | Administrative web interface to toggle relay states and configure sensor threshold values dynamically. |

---

## 🗄️ Database Structure (MySQL DDL)

Below is the complete SQL DDL schema required to initialize the `dev_iot` database:

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
