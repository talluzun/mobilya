# Mimarist Sandalye

Professional furniture catalog, quote management, and admin platform built with Laravel 12 and Filament.

## Project Overview

Mimarist Sandalye is a full-stack Laravel application for a furniture business that needs more than a static catalog. The platform combines a public product showcase, configurable product options, customer quote requests, custom order intake, project portfolio pages, and an internal admin panel for day-to-day operations.

The application is designed as a portfolio-ready business system: customers can browse products, filter the catalog, request tailored pricing, save favorites, submit custom furniture requests, and track quotes through an authenticated account area. Administrators manage products, categories, media, projects, customer messages, custom orders, and quotes from a polished Filament dashboard.

## Features

- Public furniture catalog with category, search, room type, material, color, and price sorting filters.
- Product detail pages with media, reference codes, configurable options, color selections, quantity, and quote request flow.
- Quote engine that snapshots product price, selected color, extra option prices, quantity, and customer details.
- Unique quote reference pages for customers after submitting a request.
- Authenticated account area for customer quotes and favorite products.
- Favorites system for signed-in users.
- Project portfolio pages with media and related products.
- Custom order form with measurements, quantity, color requests, description, and reference image upload.
- Contact message capture for sales inquiries.
- Filament admin panel for managing products, projects, quotes, custom orders, contact messages, and dashboard widgets.
- Email notification support for new quote requests.
- Legal and SEO-friendly pages including privacy, terms, KVKK, and sitemap XML.
- Docker Compose services for MySQL, Redis, and Mailpit.

## Tech Stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Admin Panel | Filament 3 |
| Database | MySQL 8.0 |
| Cache / Queue Support | Redis 7, Laravel database queues |
| Frontend Assets | Blade, Vite 7, Tailwind CSS 4 |
| Mail Testing | Mailpit |
| Tooling | Composer, NPM, Laravel Pint, PHPUnit |
| Local Infrastructure | Docker Compose |

## Architecture

```text
mobilya/
├── docker-compose.yml          # Local infrastructure: MySQL, Redis, Mailpit
└── backend/
    ├── app/
    │   ├── Filament/           # Admin resources and dashboard widgets
    │   ├── Http/Controllers/   # Public pages, product catalog, auth, quotes
    │   ├── Mail/               # Quote notification mailables
    │   └── Models/             # Domain models and relationships
    ├── database/
    │   └── migrations/         # Products, options, projects, quotes, users, jobs
    ├── resources/
    │   └── views/              # Blade views for public and account pages
    ├── routes/
    │   └── web.php             # Public, auth, account, quote, and page routes
    ├── composer.json           # PHP dependencies and Laravel scripts
    └── package.json            # Vite/Tailwind frontend tooling
```

### Domain Model

The application is organized around a practical furniture sales workflow:

- `Product`, `Category`, `ProductMedia`, `ProductOption`, and `ProductOptionValue` power the catalog and configurable product variants.
- `Quote` and `QuoteItem` preserve customer requests with price snapshots, selected options, customer information, and workflow status.
- `Project` and `ProjectMedia` support the portfolio section and can connect projects to related products.
- `CustomOrder` captures bespoke furniture requests with optional reference images.
- `ContactMessage` stores inbound contact form submissions.
- `User` owns customer account data, quotes, and favorite products.

## Docker Setup

The repository includes Docker Compose services for supporting infrastructure:

```yaml
mysql   # MySQL 8.0 on port 3306
redis   # Redis 7 on port 6379
mailpit # Mailpit SMTP on port 1025, UI on port 8025
```

Start the services from the repository root:

```bash
docker compose up -d
```

Useful service URLs:

- Application: `http://localhost:8000`
- Admin panel: `http://localhost:8000/admin`
- Mailpit inbox: `http://localhost:8025`
- MySQL: `127.0.0.1:3306`

Stop the services:

```bash
docker compose down
```

Remove local database volume when you need a clean database:

```bash
docker compose down -v
```

## Installation

### Prerequisites

- PHP 8.2 or newer
- Composer
- Node.js and NPM
- Docker and Docker Compose
- MySQL client tools, optional but useful for debugging

### Local Setup

1. Clone the repository and enter the project:

```bash
git clone <repository-url>
cd mobilya
```

2. Start local infrastructure:

```bash
docker compose up -d
```

3. Install backend dependencies:

```bash
cd backend
composer install
```

4. Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

5. Confirm these local database and mail settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mobilya
DB_USERNAME=mobilya
DB_PASSWORD=mobilya

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
QUOTE_NOTIFICATION_EMAIL=admin@mimaristsandalye.com
```

When running Laravel inside a container or another Docker network, use `mysql` and `mailpit` as hostnames instead of `127.0.0.1`.

6. Run migrations:

```bash
php artisan migrate
```

7. Create an admin user for Filament:

```bash
php artisan make:filament-user
```

8. Install frontend dependencies and build assets:

```bash
npm install
npm run build
```

9. Start the application:

```bash
php artisan serve
```

Visit `http://localhost:8000` for the public site and `http://localhost:8000/admin` for the admin panel.

### Development Mode

Run Laravel, Vite, queue listener, and logs together:

```bash
composer run dev
```

Run tests:

```bash
composer test
```

Format PHP code:

```bash
./vendor/bin/pint
```

## Roadmap

- Add seeders for demo products, categories, projects, options, and admin dashboard data.
- Add automated feature tests for quote requests, custom orders, favorites, and account authorization.
- Add richer quote status workflow with admin notes, customer notifications, and status history.
- Add product inventory or availability tracking.
- Add image optimization and responsive media conversions for catalog and project galleries.
- Add API endpoints for future mobile app or frontend decoupling.
- Add deployment documentation for production hosting, queues, scheduler, backups, and storage.
- Add analytics events for catalog filtering, product views, quote submissions, and conversion tracking.

## Developer Section

### Key Commands

```bash
# Start infrastructure
docker compose up -d

# Work in the Laravel app
cd backend

# Install PHP and JS dependencies
composer install
npm install

# Prepare app
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan make:filament-user

# Start full local development stack
composer run dev

# Build production assets
npm run build

# Run test suite
composer test

# Format PHP code
./vendor/bin/pint
```

### Admin Workflow

1. Start Docker services and the Laravel app.
2. Create a Filament user with `php artisan make:filament-user`.
3. Log in at `/admin`.
4. Manage catalog data through Filament resources:
   - Products and product media
   - Categories
   - Product options and option values
   - Projects
   - Quote requests
   - Custom orders
   - Contact messages

### Environment Notes

- The public application uses Turkish routes and localization settings.
- Quote notification emails are controlled by `QUOTE_NOTIFICATION_EMAIL`.
- Mailpit is available at `http://localhost:8025` for local email testing.
- The queue connection defaults to the database driver. Run the queue listener in development with `composer run dev` or `php artisan queue:listen`.
- Uploaded custom order reference images are stored on the `public` disk. In production, run `php artisan storage:link`.

## Portfolio Highlights

This project demonstrates practical Laravel development across public user flows, admin tooling, relational data modeling, form validation, transactional quote creation, email notifications, Dockerized local services, and maintainable MVC architecture. It is intentionally structured to look and behave like a real business application rather than a tutorial scaffold.
