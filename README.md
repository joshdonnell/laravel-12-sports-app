# Sports Demo

[![tests](https://github.com/joshdonnell/laravel-12-sports-app/actions/workflows/tests.yml/badge.svg)](https://github.com/FrameworkDesign/laravel-starter-kit/actions/workflows/tests.yml)

A **Laravel 12**, **Inertia 2.0**, **Vue**, and **TypeScript** ultra-strict, type-safe Laravel boilerplate.
This opinionated starter kit enforces rigorous development standards through meticulous tooling configuration and
architectural decisions that prioritise type safety, immutability, and fail-fast principles.

With features such as end-to-end type safety and automatic Vue component/composable imports, the starter kit follows
industry best practices for both Laravel and Vue.

---

## Features

- Laravel 12
- Automatic Vue imports for core helpers, components, and composables
- Advanced PHP tooling including Peck, Pint, and PHPStan
- TS/CSS formatting via Prettier and ESLint
- 100% Pest test coverage
- Example component and store tests via Vitest
- ...and much more

---

## Installation

> Requires PHP 8.4+, pnpm, Xdebug, and Aspell

If you don’t have any of the above, visit the FAQs section to learn how to download and configure the required
dependencies.

### Initial Setup

```bash
git clone git@github.com:joshdonnell/laravel-12-sports-app.git
cd netpi

# Setup a copy of .env.example to .env and connect to a local db

composer install
php artisan key:generate
php artisan migrate --seed
pnpm install
pnpm run build
```

### Verify Installation

This runs the projects test suite, informing the user of any errors with their setup.

```bash
composer test
```

### Compiling via Vite

```bash
# To watch changes with HMR
pnpm run dev

# Building for production
pnpm run build
```

---

## Available Tooling

### Development

- `composer dev` — Starts the Laravel server, queue worker, log monitor, and Vite dev server.

### Code Quality

- `composer lint` — Runs Rector, Pint, and Prettier/ESLint.
- `composer test:lint` — Dry-run mode for CI/CD pipelines.

### Testing

- `composer test:typos` — Checks spelling via Peck.
- `composer test:type-coverage` — Ensures 100% type coverage with Pest.
- `composer test:types` — Runs PHPStan and checks TypeScript types.
- `composer test:unit` — Runs Pest tests.
- `composer test:vitest` — Runs Vitest tests.
- `composer test` — Runs the full suite.

### Maintenance

- `composer update:requirements` — Updates all PHP dependencies.

---

## FAQ

### Install Aspell on macOS

```bash
brew install aspell
aspell check filename.txt
```

### Enable Xdebug for Laravel Herd on macOS

1. In Laravel Herd, open **php.ini** from your selected PHP version.
2. Add:
   ```
   zend_extension="xdebug.so"
   [xdebug]
   xdebug.mode=coverage
   xdebug.start_with_request=yes
   xdebug.client_host=127.0.0.1
   xdebug.client_port=9003
   ```
3. Restart PHP in Herd.

### Database

See [database/README.md](database/README.md) for database design documentation.
