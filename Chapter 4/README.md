# Laravel Admin Starter Kit

- Laravel 7
- Cork (DARK) HTML Template https://designreset.com/cork/documentation/
- Default language is Bahasa Indonesia
- Default timezone is Asia/Jakarta
- Theme integration
    - Auth (Login, Register, Reset Password)
    - Home
    - Pagination
    - Errors page
- Supports
    - `@stack('styles')`
    - `@stack('scripts')`
    - Bugsnag error reporting
    - Auto generate json language file with `php artisan translatable:export id,en` or update with `php artisan translatable:inspect-translations id --export-first` use this package https://github.com/kkomelin/laravel-translatable-string-exporter
- Included
    - Fontawesome PRO
    - Repository pattern
    - User Profile Management
    - User logs (Login, Logout, Reset Password & Update Profile)
        - The log will be deleted from the database if it is more than or equal to **3 months** (via Laravel Scheduler)
        - Thats way u should add `php artisan schedule:run` into your cronjob
- Custom styling use sass & webpack


## Installation

- Clone
- `composer install`
- `npm install`
- `npm run production`
- `cp .env.example .env` and change database value with ur own
- `php artisan key:generate`
- `php artisan migrate`
