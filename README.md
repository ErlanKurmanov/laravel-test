# Laravel CRM – Ticket Management System

A modern ticket management system built with **Laravel 12**, featuring a customer-facing widget and an admin dashboard.

---

## Features

### Customer Widget
- Submit tickets via a simple form
- Attach up to 5 files (max 10MB each; allowed types: jpg, png, pdf, doc, docx, zip)
- Rate limiting: one ticket per email or phone number per 24 hours
- Responsive design

### Admin Panel
- View and filter all tickets
- Detailed ticket view
- Manage ticket statuses: `new`, `in_progress`, `processed`
- Statistics: tickets created today, this week, this month
- View customer information
- Download attached files

### Technical Features
- Repository pattern + service layer
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v5/introduction) for roles
- [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary/v10/introduction) for file handling
- SQLite by default (MySQL supported)
- Docker-ready
- PHPUnit tests
- RESTful API

---

## Requirements

### With Docker (recommended)
- Docker 20.10+
- Docker Compose 2.0+
- Git

### Without Docker
- PHP 8.4+
- Composer 2.x
- Node.js 20+ & NPM
- SQLite3 or MySQL 8.0+

---

## Quick Start

```bash
git clone https://github.com/ErlanKurmanov/laravel-test.git
cd laravel-test
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link
docker compose exec app php artisan migrate --seed
docker compose exec app npm run build
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose restart app
```

### Credentials to enter admin
- login: admin@example.com
- password: admin
