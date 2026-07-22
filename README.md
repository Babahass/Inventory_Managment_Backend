# Inventory Manager — Backend (Laravel)

## Requirements
- PHP 8.2+
- XAMPP (MySQL running on port 3306)
- Composer

## Setup

**1. Create the database in phpMyAdmin or MySQL:**
```sql
CREATE DATABASE inventory_db;
```

**2. Install dependencies:**
```bash
composer install
```

**3. Configure `.env`** — already set for XAMPP defaults (root / no password). If your MySQL uses a password, update `DB_PASSWORD`.

**4. Run migrations and seed demo data:**
```bash
php artisan migrate --seed
```

**5. Start the server:**
```bash
php artisan serve
```

API runs at `http://localhost:8000/api`

## Demo credentials
- Email: `admin@inventory.com`
- Password: `password`

## API Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/register | Register |
| POST | /api/login | Login |
| POST | /api/logout | Logout (auth) |
| GET | /api/dashboard | Dashboard stats |
| CRUD | /api/products | Products |
| CRUD | /api/categories | Categories |
| CRUD | /api/suppliers | Suppliers |
| GET/POST | /api/transactions | Transactions |
