# Velasco LabAct (Group2_Elec3c)

A Laravel application for managing events, eco-spaces, reviews, and user profiles used by the Velasco lab front-end project.

This repository contains the application code (Laravel 10+), frontend assets built with Vite/Tailwind, and tests.

## Quick Start

Prerequisites:
- PHP 8.1+ (matching project's platform requirements)
- Composer
- Node.js 16+ and npm (or pnpm)
- A running database (MySQL, PostgreSQL, or SQLite)

Basic setup:

```bash
# install PHP dependencies
composer install

# copy env and generate app key
cp .env.example .env
php artisan key:generate

# configure .env with DB credentials

# run migrations and (optional) seeders
php artisan migrate --seed

# install frontend dependencies and build assets (development):
npm install
npm run dev

# or build for production
npm run build

# serve the app locally
php artisan serve
```

Notes:
- If you use Docker, you can adapt the commands above to your containers.
- To serve storage assets, run `php artisan storage:link` if required.

## Running Tests

Run the automated test suite with:

```bash
php artisan test
```

## Common Commands

- `php artisan migrate` — run database migrations
- `php artisan migrate:rollback` — rollback last migration batch
- `php artisan db:seed` — run seeders
- `npm run dev` — start Vite dev server
- `npm run build` — build production assets

## Project Structure (high level)

- `app/Models` — Eloquent models (Event, EcoSpace, Review, User, etc.)
- `app/Http/Controllers` — HTTP controllers
- `resources/views` — Blade templates
- `resources/js` & `resources/css` — front-end source (Vite + Tailwind)
- `database/migrations` — migrations
- `tests` — feature and unit tests

## Contributing

1. Fork the repository and create a feature branch.
2. Run the test suite and ensure linting passes.
3. Open a pull request with a clear description of changes.

Please follow PSR-12 and existing code style when contributing.

## License

This project uses the MIT license. See `LICENSE` for details (if present).

## Maintainers / Contact

If you need help, open an issue in this repository or contact the project maintainer.

---
If you'd like, I can also add a short `CONTRIBUTING.md` and a minimal `LICENSE` file next.
