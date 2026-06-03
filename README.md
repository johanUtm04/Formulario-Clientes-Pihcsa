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