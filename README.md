# Secure Digital Asset & Client Management System

A production-ready, full-stack web application designed for secure corporate onboarding, digital file auditing, and compliant regulatory document management. This platform features a split public intake funnel and an authenticated administrative review dashboard backed by strict defensive security baselines.

---

## 🚀 Core Features

* **Dual-Faceted Workflow:** Public-facing multi-segmented registration workflows alongside a restricted administrative audit control panel.
* **Dynamic Digital Dossier:** Automated physical file classification mapping critical legal records, digital signatures, and site imagery by unique identifier (RFC).
* **Cryptographic Session Safeguards:** System-wide token verification protecting administrative views and mutation gateways.
* **Adaptive Binary Streaming:** Inline multi-format image and PDF rendering utilizing dynamic MIME evaluation without manual extension tracking.

---

## 🛠️ Tech Stack & Architecture
# 📋 Formulario-Clientes-Pihcsa

> A form management system for clients and providers of **Pihcsa**.

![PHP](https://img.shields.io/badge/PHP-46.2%25-777BB4?style=flat&logo=php&logoColor=white)
![HTML](https://img.shields.io/badge/HTML-41.0%25-E34F26?style=flat&logo=html5&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-3.5%25-F7DF1E?style=flat&logo=javascript&logoColor=black)
![CSS](https://img.shields.io/badge/CSS-1.3%25-1572B6?style=flat&logo=css3&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green?style=flat)

---

## 📖 Description

**Formulario-Clientes-Pihcsa** is a web application built in PHP that allows managing client and provider forms for the company Pihcsa. It includes authentication, document management, client search, and a secure file upload system.

---

## 📁 Project Structure

```
Formulario-Clientes-Pihcsa/
├── css/                        # Stylesheets
├── fpdf/                       # PDF generation library
├── includes/                   # Reusable PHP components
├── js/                         # JavaScript files
├── uploads/                    # Uploaded files (secured)
├── Database Structure.sql      # Database schema (without data)
├── admin.php                   # Admin panel
├── buscar_cliente.php          # Client search module
├── conexion.php                # Database connection
├── index.php                   # Entry point
├── leer_documento.php          # Document viewer
├── login.php                   # Authentication
├── logout.php                  # Session logout
├── procesar.php                # Form processing
├── ver_expediente.php          # Client record viewer
└── README.md
```

---

## ✨ Features

- 🔐 **Authentication** — Secure login/logout system
- 🔍 **Client Search** — Search and filter clients easily
- 📄 **Document Management** — Read and view client documents
- 📁 **File Uploads** — Secure file upload with content erasure on security events
- 🖨️ **PDF Generation** — Built-in PDF export using FPDF
- 🛡️ **Security** — Protection against Predictable Directory Browsing and file-type breaches

---

## 🗄️ Database

The file `Database Structure (Without Data).sql` contains the full schema to set up the database. **No sensitive data is included.**

To import it:

```bash
mysql -u your_user -p your_database < "Database Structure (Without Data).sql"
```

---

## 🚀 Installation

### Prerequisites

- PHP >= 7.4
- MySQL / MariaDB
- Apache or Nginx (with mod_rewrite enabled)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/johanUtm04/Formulario-Clientes-Pihcsa.git
   cd Formulario-Clientes-Pihcsa
   ```

2. **Set up the database**
   ```bash
   mysql -u your_user -p your_database < "Database Structure (Without Data).sql"
   ```

3. **Configure the connection**

   Edit `conexion.php` with your database credentials:
   ```php
   $host     = "localhost";
   $user     = "your_user";
   $password = "your_password";
   $database = "your_database";
   ```

4. **Set permissions for uploads folder**
   ```bash
   chmod 755 uploads/
   ```

5. **Start your local server** (e.g. with XAMPP/WAMP) and navigate to:
   ```
   http://localhost/Formulario-Clientes-Pihcsa/
   ```

---

## 🔒 Security Considerations

- The `uploads/` directory is protected against direct browsing.
- File type validation is enforced in `procesar.php`.
- Uploaded content is erased on security breach detection.
- Sensitive files are excluded from public access via server configuration.

---

## 👤 Author

**johanUtm04** — [johanlopez2004.dev](https://github.com/johanUtm04)

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
* **Backend Engine:** PHP 8.x (Modular Architecture)
* **Database Management:** MySQL / MariaDB
* **Frontend Layer:** HTML5, Component-Driven CSS3 Grid/Flexbox Layouts, JavaScript (Vanilla)
* **Server Environment Rules:** Apache Configuration (`.htaccess`)

---

## 🔒 Implemented Security Engineering (OWASP Top 10 Mitigation)

Instead of relying on basic sanitization, this project is built using a modern **Defense-in-Depth** security strategy:

### Broken Access Control & Forced Browsing Shield
* Files uploaded to the local disk are stored in a restricted `uploads/` directory protected via global Apache directives.
* **Direct URL access is completely blocked.** All digital assets are streamed on-demand through an intermediating gateway script (`leer_documento.php`) that checks active, server-side `$_SESSION` privileges before releasing data blocks.


### Cross-Site Request Forgery (CSRF) Tokens
* State-changing forms embed cryptographically secure pseudo-random tokens compared directly against active session states before evaluating processing routes.

---

## 📂 System File Architecture

```text
├── index.php                     # Client registration form UI components
├── procesar.php                  # Secure backend form processing, file validation & SQL engine
├── leer_documento.php            # Secure asset proxy gateway (prevents direct file access)
├── buscar_cliente.php            # Administrative lookup tool for existing records
├── ver_expediente.php            # Administrative digital dossier rendering view
├── admin.php                     # Restricted operations panel / dashboard hub
├── login.php                     # Admin authentication checkpoint
├── logout.php                    # Secure session termination destruction script
├── create_user.php               # Local development provisioning script for admin accounts
├── conexion.php                  # Core database connection abstraction driver
├── formulario.sql                # Relational MySQL database schema baseline blueprint
├── AVISO_PRIVACIDAD_FIRMADO.pdf  # Executed master privacy compliance template
├── uploads/                      # Protected customer record repository (segmented by RFC)
├── js/                           # Form interactive scripts and validation layers
│   └── funciones.js
├── includes/                     # Structural layout templates and partial modules
│   ├── header.php
│   ├── footer.php
│   ├── form_datos_generales.php  
│   └── form_documentos.php
├── css/                          # Application presentation styling definitions
└── fpdf/                         # Integrated PDF generation engine components
