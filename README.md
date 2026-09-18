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
 |               [ setting ] [ dh11 ] [ sensor_logs ]               |
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
GET /iot/add_data.php?sensor=soil_moisture&data=65&indata=24.5
```
- **`sensor`**: Target table/sensor identifier (e.g. `soil_moisture`, `dh11`).
- **`data`**: Primary sensor measurement (e.g. moisture level %).
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
  "fan": 11,
  "heater": 10,
  "pump": 11
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
2. **Configure Database Connection (`config.php`)**:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'dev_iot');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('URL', 'http://localhost/iot/');
   ```
3. **Database Setup**:
   Ensure MySQL database `dev_iot` is created with tables for `setting`, `dh11`, and your target sensor tables.

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
