# Codebase Guide for Newcomers

## What this repository is
This project is currently a **fresh Laravel 12 skeleton** with the default landing page, example routes, and starter tests. There is very little domain-specific code yet.

## High-level structure

- `app/` — Application PHP classes (controllers, models, providers). Right now it only contains `User`, base `Controller`, and `AppServiceProvider`.
- `bootstrap/` — App bootstrap and framework wiring. `bootstrap/app.php` configures routes, middleware, exceptions, and health endpoint.
- `config/` — Environment-driven configuration for app behavior (app, auth, cache, database, queue, session, logging, etc.).
- `database/` — Migrations, model factories, seeders.
- `public/` — Web server entrypoint (`index.php`) and public assets.
- `resources/` — Frontend assets and Blade templates (`resources/views/welcome.blade.php`).
- `routes/` — Route definitions for HTTP and console commands.
- `tests/` — PHPUnit tests (unit + feature).
- `composer.json` and `package.json` — PHP and JS dependency manifests and developer scripts.

## Request lifecycle (current setup)
1. HTTP requests hit `public/index.php`.
2. Laravel bootstraps through `bootstrap/app.php`.
3. Routes from `routes/web.php` are loaded.
4. The root route (`/`) returns `resources/views/welcome.blade.php`.

## Important things to know before editing

- **This is baseline scaffolding**: there are no custom controllers/services yet.
- **Provider registration**: service providers are listed in `bootstrap/providers.php`.
- **Local dev commands**:
  - `composer run dev` starts web server + queue listener + logs + Vite (via `concurrently`).
  - `composer test` clears config and runs tests.
- **Frontend build** uses Vite + Tailwind 4 through `resources/js/app.js` and `resources/css/app.css`.

## Good next steps to learn

1. **Routing + controllers**
   - Move the closure route in `routes/web.php` to a controller action.
2. **Data model basics**
   - Read migrations in `database/migrations/` and the `User` model.
3. **Config + environment**
   - Learn `.env`-driven behavior via files in `config/`.
4. **Testing workflow**
   - Expand `tests/Feature/ExampleTest.php` with actual route / auth / validation tests.
5. **Frontend pipeline**
   - Understand how Blade + Vite + Tailwind fit together from `resources/views/welcome.blade.php`, `resources/js/app.js`, and `vite.config.js`.

## Suggested first real feature
Build a simple "articles" module end-to-end:
- migration + model
- controller + routes
- Blade views
- feature tests

That exercise touches the core layers you'll use most in Laravel.
