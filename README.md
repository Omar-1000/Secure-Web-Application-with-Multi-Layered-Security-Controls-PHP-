# 🔐 Multi-Layered Secure Web Application (PHP)

<p align="center">
  <img src="screenshots/01_secure_interface.png" width="850">
</p>

## 📌 Project Overview

This project is a secure PHP-based web application developed to demonstrate the practical implementation of layered defensive security mechanisms in a real authentication workflow.

The system integrates multiple security controls across input handling, authentication, session management, authorization, and transport security to mitigate common web application attack vectors.

The objective of this project is not only functional authentication, but secure-by-design architecture applying defense-in-depth principles.

---

## 🎯 Security Objectives

The application was designed to defend against:

- Cross-Site Scripting (XSS)
- SQL Injection
- Cross-Site Request Forgery (CSRF)
- Session Hijacking
- Token Tampering
- Credential Theft
- Basic OTP abuse attempts (session & expiration control)
- Man-in-the-Middle (MITM) attacks via HTTPS

---

# 🏗 System Architecture

## Authentication Flow

1. User visits `index.php` (landing page).
2. User navigates to `index22.php` (login page).
3. Credentials are validated securely using prepared statements.
4. If valid:
   - A JWT token is generated.
   - A 6-digit OTP is generated.
   - OTP is sent via SMTP using PHPMailer.
5. User is redirected to `otp_verify.php`.
6. OTP is validated against session.
7. Upon successful validation:
   - JWT is stored in secure cookie.
   - User is redirected to `homepage.php`.
8. Admin users (based on `is_admin` flag) can access `admin.php`.
9. Logout clears session and authentication state.

---

# 🛠 Technology Stack

- **Backend:** PHP  
- **Database:** MySQL (phpMyAdmin)  
- **Web Server:** Apache (AMPP stack)  
- **Frontend:** HTML, CSS, JavaScript  
- **Libraries Used:**
  - PHPMailer (SMTP-based OTP delivery)
  - Firebase PHP-JWT (JWT token handling)

---

# 🔒 Implemented Security Controls

---

## 1️⃣ Input Validation & XSS Mitigation

All user inputs are sanitized and validated before processing.

Techniques used:

- `filter_input()`
- `FILTER_SANITIZE_SPECIAL_CHARS`
- `FILTER_SANITIZE_EMAIL`
- `filter_var()` validation
- `htmlspecialchars()` when rendering outputs

This prevents malicious script injection and reflected/stored XSS attacks.

---

## 2️⃣ SQL Injection Prevention

Database queries are implemented using:

- Prepared statements
- Parameter binding (`bind_param`)
- No direct SQL string concatenation

Example pattern:

```php
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
```

This ensures user input is strictly treated as data, not executable SQL.

---

## 3️⃣ CSRF Protection

Each form submission includes a CSRF token:

- Token generated using `random_bytes()`
- Stored in server-side session
- Embedded as hidden input field
- Validated before processing request

Invalid or missing tokens result in request rejection.

---

## 4️⃣ Secure Session Management (JWT-Based)

After successful login:

- A JWT token is created containing:
  - User email
  - Issued-at timestamp
  - Expiration timestamp

- Token is cryptographically signed.
- Stored inside a secure cookie configured with:
  - `HttpOnly`
  - `Secure`
  - `SameSite=Strict`

On protected pages:

- JWT signature is verified
- Expiration is validated
- User identity is revalidated against database

This protects against session hijacking, token tampering, and replay abuse.

---

## 5️⃣ HTTPS Enforcement

Transport security is implemented using:

- Self-signed SSL certificate generated via OpenSSL
- Apache HTTPS configuration
- Encrypted communication channel

This protects sensitive authentication data during transmission.

---

## 6️⃣ Two-Factor Authentication (2FA)

The application implements OTP-based second-factor authentication:

- 6-digit OTP generated securely
- Delivered via SMTP using PHPMailer
- Stored temporarily in session
- Validated before granting full access

Includes:

- Expiration checks
- Mismatch handling
- Access denial on invalid attempts

---

## 7️⃣ Password Security (bcrypt Hashing)

Passwords are never stored in plaintext.

During registration:

```php
password_hash($password, PASSWORD_DEFAULT);
```

During login:

```php
password_verify($password, $storedHash);
```

This protects against credential leakage and brute-force hash cracking.

---

# 👤 Role-Based Access Control

The database includes an `is_admin` field:

- 0 = Standard User
- 1 = Administrator

Admin-only pages validate:

- JWT authenticity
- Role authorization

Unauthorized access attempts are rejected.

---

# 🗄 Database Structure

Location:

```
db/database.sql
```

### Table: `users`

| Column      | Purpose |
|------------|----------|
| id         | Primary key (Auto Increment) |
| firstName  | User first name |
| lastName   | User last name |
| email      | Unique login identifier |
| password   | bcrypt hashed password |
| is_admin   | Role control flag |

---

# 📸 Application Interface

### 🔐 Main Secure Interface
<p align="center">
  <img src="screenshots/01_The_secure_website_interface.png" width="800">
</p>

### 🔑 Login Page
<p align="center">
  <img src="screenshots/02_sign in page.png" width="700">
</p>

### 📝 Registration Page
<p align="center">
  <img src="screenshots/03_sign up page (regidter).png" width="700">
</p>

### 🔢 OTP Verification
<p align="center">
  <img src="screenshots/04_OTP verification.png" width="700">
</p>

### 🛡 Admin Capabilities
<p align="center">
  <img src="screenshots/05_Admin capabilities.png" width="700">
</p>

---

# 🚀 Running the Application Locally

1. Install AMPPS (Apache + MySQL + PHP).
2. Copy `/src` contents into AMPP `www/`.
3. Create a database in phpMyAdmin.
4. Import `db/database.sql`.
5. Configure SMTP placeholders if testing OTP functionality.
6. Visit:

```
http://localhost/
```

---

# ⚠️ Security Notice

- credentials are replaced with placeholders.
- SSL private keys are not included.
- Secret keys are not exposed in repository.
- Intended for educational and secure coding demonstration purposes.

---

# 🧠 Security Principles Demonstrated

- Defense-in-depth
- Secure authentication design
- Token-based session management
- Input sanitization & validation
- Secure cookie configuration
- Cryptographic password storage
- Role-based authorization
- Encrypted transport layer

---

## 👨‍💻 Author

Omar Ahmad Ayesh  
Web Security Project – Secure Application Development
