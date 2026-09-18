# ⚡ IoT Smart Climate Control System

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![ESP8266 / ESP32](https://img.shields.io/badge/ESP8266%2FESP32-000000?style=for-the-badge&logo=espressif&logoColor=white)](https://www.espressif.com/)
[![Chart.js / Highcharts](https://img.shields.io/badge/Telemetry-Visualization-FF6384?style=for-the-badge)](https://www.chartjs.org/)

The **IoT Smart Climate Control System** is a real-time **PHP & MySQL Telemetry & Actuator Management Platform** built for environmental monitoring, climate regulation, soil moisture tracking, and automated relay/device control. Designed to interface seamlessly with microcontrollers (ESP8266, ESP32, Arduino), it processes incoming sensor streams and serves real-time operational states back to edge devices.

---

## 📺 Live Video Demo & Dashboard Walkthrough

Watch the complete project demonstration showing the live PHP climate control dashboard, real-time telemetry graphs, and hardware actuator relays in action:

[![PHP Climate Control Dashboard Demo](https://img.youtube.com/vi/f8a_zdn-VkQ/maxresdefault.jpg)](https://youtu.be/f8a_zdn-VkQ)

▶️ **Watch on YouTube:** [https://youtu.be/f8a_zdn-VkQ](https://youtu.be/f8a_zdn-VkQ)

---

## 🌟 Key Features

| Feature | Description |
| :--- | :--- |
| 📊 **Telemetry Ingestion API** | High-performance endpoints (`add_data.php`, `update_data.php`) to log temperature, soil moisture, humidity, and gas/PPM readings. |
| 🎛️ **Actuator & Relay Control** | Dedicated endpoint (`get_data.php`) delivering real-time actuator commands (fans, heaters, irrigation pumps, lights). |
| ⏱️ **Automated Scheduler** | `scheduler.php` engine for timed relay triggers, climate control cycles, and scheduled automation routines. |
| 💧 **Soil & Climate Analytics** | Specialized data query endpoints (`get_soil_info.php`) generating historical time-series datasets for interactive charts. |
| 🔐 **Settings & Device Management** | Administrative interface to toggle relay states and configure sensor threshold values dynamically. |

---

## 🗄️ Complete Database Structure (MySQL DDL)

Below is the complete SQL DDL schema required to create the MySQL database tables (`dev_iot`) for this platform:

```sql
CREATE DATABASE IF NOT EXISTS `dev_iot` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dev_iot`;

-- ========================================================
-- 1. SETTING TABLE (Actuator states, thresholds & relays)
-- ========================================================
CREATE TABLE IF NOT EXISTS `setting` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `value` VARCHAR(255) NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Default Actuators & Threshold Settings
INSERT INTO `setting` (`id`, `name`, `value`) VALUES
(1, 'fan_sw', 'off'),
(2, 'light_sw', 'off'),
(3, 'pump_sw', 'off'),
(4, 'heater_sw', 'off'),
(5, 'temp_threshold', '30'),
(6, 'humidity_threshold', '70'),
(7, 'moisture_threshold', '40');

-- ========================================================
-- 2. DH11 TABLE (DHT11/DHT22 Ambient Weather Telemetry)
-- ========================================================
CREATE TABLE IF NOT EXISTS `dh11` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tem` FLOAT DEFAULT '0',
  `hum` FLOAT DEFAULT '0',
  `ppm` FLOAT DEFAULT '0',
  `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Default Live Weather Row (ID 100 used by system)
INSERT INTO `dh11` (`id`, `tem`, `hum`, `ppm`) VALUES
(100, 25.0, 60.0, 400.0);

-- ========================================================
-- 3. SOIL TABLE (Soil Moisture & Temperature Logs)
-- ========================================================
CREATE TABLE IF NOT EXISTS `soil` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `value` FLOAT DEFAULT '0',
  `tem` FLOAT DEFAULT '0',
  `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- 4. USERS TABLE (Dashboard Administrative Authentication)
-- ========================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user` VARCHAR(50) NOT NULL UNIQUE,
  `pwd` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Admin User (Username: admin, Password: password)
INSERT INTO `users` (`user`, `pwd`) VALUES
('admin', 'password');
```

---

## 🏗️ System Architecture

```
 +------------------------------------------------------------------+
 |                Microcontrollers & Edge Devices                   |
 |         (ESP8266 / ESP32 / Arduino / Environmental Sensors)      |
 +------------------------------------------------------------------+
                                  |
                   HTTP GET / POST Telemetry Requests
                                  |
                                  v
 +------------------------------------------------------------------+
 |                    PHP IoT Climate Control API                   |
 |   add_data.php | get_data.php | update_data.php | scheduler.php   |
 +------------------------------------------------------------------+
                                  |
                        PDO MySQL Database Handler
                                  |
                                  v
 +------------------------------------------------------------------+
 |                         MySQL Database                           |
 |          [ setting ] [ dh11 ] [ soil ] [ users ]                 |
 +------------------------------------------------------------------+
                                  |
                                  v
 +------------------------------------------------------------------+
 |            Web Control Panel & Interactive Analytics             |
 |                     (index.php / Highcharts)                     |
 +------------------------------------------------------------------+
```

---

## 📁 Project Directory Structure

```
iot/
├── index.php             # Web Dashboard & Live Climate Monitoring Panel
├── config.php            # Database connection & timezone configuration
├── add_data.php          # Telemetry ingestion endpoint for sensor nodes
├── get_data.php          # Edge device state retriever (relays, pumps, switches)
├── update_data.php       # Live climate updater (DHT11/DHT22 temp, humidity, ppm)
├── get_soil_info.php     # Endpoint returning soil moisture & temp JSON history
├── scheduler.php         # Timed task executor for climate & irrigation cycles
├── include/
│   ├── class.php         # Core OOP IoT data handler class (`Iot`)
│   ├── header.php        # UI Header template
│   └── footer.php        # UI Footer template
├── css/                  # Custom CSS stylesheets & Bootstrap assets
├── js/                   # Dashboard JavaScript & charting scripts
└── lib/                  # Helper libraries (Highcharts, FontAwesome, etc.)
```

---

## 🛠️ API Endpoint Specification

### 1. Ingest Sensor Data (`add_data.php`)
Microcontrollers send HTTP GET requests to log telemetry:
```
GET /iot/add_data.php?sensor=soil&data=65&indata=24.5
```
- **`sensor`**: Target table name (e.g. `soil`, `dh11`).
- **`data`**: Primary sensor measurement (e.g. soil moisture %).
- **`indata`**: Secondary sensor measurement (e.g. soil temperature °C).

### 2. Update Live Climate (`update_data.php`)
Update live ambient weather and climate parameters:
```
GET /iot/update_data.php?tem=26.4&hum=72&ppm=412
```

### 3. Fetch Actuator Control States (`get_data.php`)
Edge devices poll this endpoint to retrieve current relay/switch states:
```
GET /iot/get_data.php
```
**Sample JSON Response:**
```json
{
  "fan_sw": 10,
  "light_sw": 10,
  "pump_sw": 11,
  "heater_sw": 10
}
```
*(Note: `11` = ON, `10` = OFF)*

---

## ⚙️ Setup & Installation

### Prerequisites
- PHP 7.4+ or PHP 8.x
- MySQL / MariaDB Server
- Web Server (WAMP, XAMPP, Nginx, or Apache)

### Configuration

1. **Copy repository** into your web root (e.g. `C:\wamp64\www\iot`).
2. **Execute Database DDL**: Import the SQL DDL schema provided in the **Database Structure** section above into your MySQL server.
3. **Configure Database Connection (`config.php`)**:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'dev_iot');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('URL', 'http://localhost/iot/');
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
