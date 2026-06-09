# Laravel E-commerce Multi-Vendor

A web-based E-commerce Multi-Vendor built using Laravel that unites sellers from around the world in one seamless marketplace. With multi-language support.

---

## 📌 Features

- documented by swagger
- User authentication (Register / verifyEmail ).
- User authentication (Login / Login no Password).
- display ,update user Profile and his favicon.
- Create, update, delete, and display Categories in multiple languages.
- Create, update, delete, and display user Products Multi language.
- Create, update, delete, and display user Shops Multi language.
- Add or remove Translations for Products and Categories.
- Search for Products and Shops.
- Ban or unban for products and shops.
- Create, update, delete multi-language comments.
- Create, add and remove products from a Cart, delete, and display user Carts. 
- Create, update, delete, shipping, deliver, delete, and display Orders.
- Payment Gateways using Paymob and Tab gateways via Mobile Wallet, Online Card, or PayPal.
- User authorization display Users and Change role or ban or activate or destroy User.
- User authorization Roles (create / update / delete) .
- User authorization permissions .

---

## 🛠️ Technologies Used

- Laravel 11+
- PHP 8.2+
- MySQL
- Darkaonline L5 Swagger UI
- Laravel Phone package 
---
---
## ⚙️ Installation

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/ahmed4-75/E-Commerce-Multi-Vendor.git
cd laravel-ECommerce_Multi_Vendor
```

### 2️⃣ Install Dependencies
```bash
composer install
```

### 3️⃣ Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```
---
---
### 🗄️ Database Configuration

### 1️⃣ Update .env file :
```bash
B_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Ecommerce
DB_USERNAME=root
DB_PASSWORD=
```
### 2️⃣ Run migrations:
```bash
php artisan migrate
```
### 3️⃣ Run Command:
```bash
php artisan create:owner
```
Answer the questions to create your first User, and his role is "owner", and it has all Permissions

###  ℹ️ Note
Testing on Localhost, you need to install ngrok to test the Paymob gateway.

---
---
### 🚀 Run the Application
```bash
php artisan serve
```
### Open in browser:
