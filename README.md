# 🌱 Smart Farm IoT Monitoring & Actuator Control System

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![ESP8266 / ESP32](https://img.shields.io/badge/ESP8266%2FESP32-000000?style=for-the-badge&logo=expressif&logoColor=white)](https://www.espressif.com/)
[![Chart.js / Highcharts](https://img.shields.io/badge/Telemetry-Visualization-FF6384?style=for-the-badge)](https://www.chartjs.org/)

A lightweight, robust **PHP & MySQL IoT Telemetry & Actuator Management System** designed for smart farming, environmental monitoring, and automated irrigation control. It receives telemetry from microcontrollers (ESP8266, ESP32, Arduino), provides real-time sensor dashboards, and serves control settings back to edge devices.

---

## 🌟 Key Features

| Feature | Description |
| :--- | :--- |
| 📊 **Telemetry Ingestion API** | Endpoints (`add_data.php`, `update_data.php`) to log temperature, soil moisture, humidity, and gas/PPM readings. |
| 🎛️ **Actuator & Relay Control** | Endpoint (`get_data.php`) returning live device configuration states (relays, pumps, automated schedules). |
| ⏱️ **Automated Scheduler** | `scheduler.php` engine powering timed actuator toggles and scheduled irrigation cycles. |
| 💧 **Soil & Weather Analytics** | Specialized endpoints (`get_soil_info.php`) querying historical trends for analytics & charts. |
| 🔐 **Authentication & Settings** | Protected admin portal for toggling device states and configuring threshold triggers. |

---

## 🏗️ System Architecture

```
 +------------------------------------------------------------------+
 |                Microcontrollers & Edge Devices                   |
 |              (ESP8266 / ESP32 / Arduino / Sensors)               |
 +------------------------------------------------------------------+
                                  |
                   HTTP GET / POST Telemetry Requests
                                  |
                                  v
 +------------------------------------------------------------------+
 |                        PHP IoT API Engine                        |
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
 |                 Web Control Panel & Visualization                |
 |                     (index.php / Highcharts)                     |
 +------------------------------------------------------------------+
```

---

## 📁 Repository Directory Structure

```
iot/
├── index.php             # Main Web Dashboard & Sensor Monitoring Interface
├── config.php            # Database connection & timezone configuration
├── add_data.php          # Telemetry ingestion endpoint for microcontrollers
├── get_data.php          # Edge device config retriever (returns active relay states)
├── update_data.php       # Live sensor data updater (DHT11/DHT22 temp, hum, ppm)
├── get_soil_info.php     # Endpoint returning soil moisture & temp JSON history
├── scheduler.php         # Timed task executor for automated relays & pumps
├── include/
│   ├── class.php         # Core OOP IoT data handler class (`Iot`)
│   ├── header.php        # UI Header template
│   └── footer.php        # UI Footer template
├── css/                  # Custom CSS stylesheets & Bootstrap assets
├── js/                   # Dashboard JavaScript & charting scripts
└── lib/                  # Helper libraries & dependencies
```

---

## 🛠️ API Endpoint Specification

### 1. Ingest Sensor Data (`add_data.php`)
Microcontrollers send HTTP GET requests to log sensor telemetry:
```
GET /iot/add_data.php?sensor=soil_moisture&data=65&indata=24.5
```
- **`sensor`**: Target table/sensor identifier (e.g. `soil_moisture`, `dh11`).
- **`data`**: Primary sensor measurement (e.g. moisture level %).
- **`indata`**: Secondary sensor measurement (e.g. soil temperature °C).

### 2. Update Live Weather (`update_data.php`)
Update live ambient weather parameters:
```
GET /iot/update_data.php?tem=26.4&hum=72&ppm=412
```

### 3. Fetch Actuator Control States (`get_data.php`)
Edge devices poll this endpoint to fetch relay/pump operation states:
```
GET /iot/get_data.php
```
**Sample JSON Response:**
```json
{
  "pump": 11,
  "light": 10,
  "fan": 11
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
3. **Database Import**:
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
