# ABREC Pacientes

A modern web application to help the ABREC an NGO register and manage patient information efficiently.

## Features

- **Patient Registration**: Comprehensive form to register new patients with personal data (name, CPF, date of birth, gender, address) and health metrics (weight, height, blood pressure, blood glucose, creatinine)
- **Health Conditions Tracking**: Monitor conditions including diabetes, hypertension, kidney problems, family DRC history, obesity, and fundoscopy exam results
- **Patient List Management**: View all registered patients in a paginated, searchable, and filterable table
- **Excel Export**: Export patient data to Excel for external analysis and reporting
- **Authentication**: Secure user authentication system powered by Laravel Fortify
- **Responsive Design**: Modern medical-themed UI that works on all devices

## Tech Stack

### Backend

- **Laravel 12** - PHP 8.2 framework
- **Laravel Fortify** - Authentication
- **Laravel Wayfinder** - Type-safe route generation for TypeScript
- **Maatwebsite Excel** - Excel export functionality

### Frontend

- **Vue 3** - Progressive JavaScript framework
- **Inertia.js v2** - Modern monolith approach with SPA benefits
- **Tailwind CSS v4** - Utility-first CSS framework
- **Reka UI** - Accessible UI components
- **Lucide Vue** - Beautiful icon library
- **VueUse** - Composition API utilities

### Development Tools

- **Pest 3** - Testing framework
- **Laravel Pint** - Code style formatter
- **ESLint** - JavaScript linter
- **Prettier** - Code formatter
- **Laravel Sail** - Docker development environment
- **Laravel Boost** - MCP server for enhanced development

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (or MySQL/PostgreSQL)

## Installation

### Quick Setup

```bash
# Clone the repository
git clone <repository-url>
cd abrec-pacientes

# Run the setup script
composer setup
```

The setup script will:

1. Install PHP dependencies
2. Copy `.env.example` to `.env`
3. Generate application key
4. Run database migrations
5. Install Node.js dependencies
6. Build frontend assets

### Manual Setup

```bash
# Install PHP dependencies
composer install

# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build
```

## Development

### Starting the Development Server

The easiest way to run the application is using the integrated development command:

```bash
composer run dev
```

This starts all necessary services concurrently:

- Laravel development server (http://127.0.0.1:8000)
- Queue worker for background jobs
- Laravel Pail for real-time log monitoring
- Vite development server with hot module replacement

### Individual Commands

If you prefer running services separately:

```bash
# Laravel development server
php artisan serve

# Vite dev server (frontend)
npm run dev

# Queue worker
php artisan queue:listen

# Log monitoring
php artisan pail
```

### Using Laravel Sail (Docker)

```bash
# Start containers
./vendor/bin/sail up -d

# Run migrations
./vendor/bin/sail artisan migrate

# Install dependencies
./vendor/bin/sail composer install
./vendor/bin/sail npm install

# Access the app at http://localhost
```

## Testing

The project uses Pest 3 for testing with comprehensive coverage:

```bash
# Run all tests
composer test

# Run tests with compact output
php artisan test --compact

# Run specific test file
php artisan test tests/Feature/PatientListTest.php

# Run tests with filter
php artisan test --filter=patient_can_be_created
```

## Code Quality

### Linting & Formatting

```bash
# PHP code style (Laravel Pint)
composer lint                 # Fix code style
composer test:lint           # Check code style

# JavaScript/Vue formatting (Prettier)
npm run format               # Fix formatting
npm run format:check        # Check formatting

# JavaScript/Vue linting (ESLint)
npm run lint                # Fix and lint
```

## Available Scripts

### Composer Scripts

- `composer setup` - Initial project setup
- `composer dev` - Start all development services
- `composer dev:ssr` - Start with server-side rendering
- `composer lint` - Format PHP code with Pint
- `composer test` - Run all tests with linting
- `composer test:lint` - Check PHP code style

### NPM Scripts

- `npm run dev` - Start Vite development server
- `npm run build` - Build production assets
- `npm run build:ssr` - Build with SSR support
- `npm run format` - Format code with Prettier
- `npm run format:check` - Check code formatting
- `npm run lint` - Lint and fix with ESLint

## Project Structure

```
.
├── app/
│   ├── Exports/           # Excel export classes
│   ├── Http/
│   │   ├── Controllers/   # Route controllers
│   │   └── Requests/      # Form request validation
│   └── Models/            # Eloquent models
├── bootstrap/             # Framework bootstrap files
├── config/                # Configuration files
├── database/
│   ├── factories/         # Model factories for testing
│   ├── migrations/        # Database migrations
│   └── seeders/          # Database seeders
├── doc/                   # Project documentation
├── public/                # Public assets
├── resources/
│   ├── css/              # Global styles
│   ├── js/
│   │   ├── components/   # Vue components
│   │   ├── layouts/      # Layout components
│   │   ├── lib/          # Utility functions
│   │   └── pages/        # Inertia pages
│   └── views/            # Blade templates
├── routes/               # Application routes
├── storage/              # Application storage
├── tests/
│   ├── Feature/          # Feature tests
│   └── Unit/             # Unit tests
└── vendor/               # Composer dependencies
```

## Design System

The application uses a medical-themed design system:

- **Primary Color**: `#D92D2D` (Red)
- **Primary Hover**: `#B91C1C`
- **Font Family**: Inter (300-700)
- **Light Theme**:
    - Background: `#F3F4F6`
    - Surfaces: `#FFFFFF`
    - Inputs: `#F9FAFB`
- **Dark Theme**:
    - Background: `#111827`
    - Surfaces: `#1F2937`
    - Inputs: `#374151`
- **Border Radius**: `0.5rem` (default)

## Environment Configuration

Key environment variables:

```env
APP_NAME="ABREC Pacientes"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
# Or use MySQL/PostgreSQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=abrec_pacientes
# DB_USERNAME=root
# DB_PASSWORD=
```

## Contributing

1. Create a new branch for your feature
2. Write tests for new functionality
3. Ensure all tests pass: `composer test`
4. Format your code: `composer lint && npm run format`
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

Built with ❤️ for NGOs helping patients in need.
