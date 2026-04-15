# Worksuite Installation Guide

## Prerequisites
- PHP 8.3 with extensions: `mbstring`, `pdo`, `pdo_mysql`, `pdo_sqlite`, `sqlite3`, `dom`, `curl`, `zip`, `bcmath`, `intl`, `gd`, `exif`, `fileinfo`
- Composer 2.x
- Node.js 18.x and npm
- MySQL 8.x (for production-like installs) or SQLite (for local smoke/testing)

## 1) Install dependencies
```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
npm ci --prefer-offline
npm run production
```

## 2) Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

Set required `.env` values:
- `APP_URL`
- `DB_CONNECTION` (`mysql` or `sqlite`)
- For MySQL: `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- For SQLite: set `DB_DATABASE` to an absolute `.sqlite` file path

Recommended local-safe runtime values:
```env
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=array
```

## 3) Initialize database
```bash
php artisan migrate --force --no-interaction
```

## 4) Smoke checks
Run these after migrations to verify bootstrap and routing:
```bash
php artisan migrate:fresh --force --no-interaction
php artisan route:list > /tmp/route-list.txt
tail -5 /tmp/route-list.txt
php artisan about
```

## 5) Run tests
```bash
php artisan test
```

## Notes
- If `composer install` reports lock drift, run:
  ```bash
  composer update --no-interaction --prefer-dist --no-scripts
  composer install --no-interaction --prefer-dist --optimize-autoloader
  ```
- Use MySQL for production parity; SQLite is best for fast local migration/test smoke runs.
