# Laravel Ticket Booking System

This is a complete backend system for a Ticket Booking System developed using the Laravel framework with PHP and MySQL.

## Technology Stack:
- Backend: Laravel (PHP framework)
- Database: MySQL (cPanel compatible)
- Authentication: Laravel Sanctum for API authentication
- Payment: SSLCommerz integration (placeholder for actual integration)
- Testing: PHPUnit for TDD
- Documentation: Swagger/OpenAPI (using L5-Swagger)
- Architecture: MVC with proper separation of concerns

## Folder Architecture:
```
ticket-booking-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── BookingController.php
│   │   │   │   ├── PaymentController.php
│   │   │   │   └── AdminController.php
│   │   │   └── Controller.php
│   │   ├── Middleware/
│   │   │   ├── Authenticate.php
│   │   │   ├── EncryptCookies.php
│   │   │   ├── RedirectIfAuthenticated.php
│   │   │   ├── TrimStrings.php
│   │   │   ├── TrustProxies.php
│   │   │   ├── VerifyCsrfToken.php
│   │   │   └── RoleMiddleware.php
│   │   ├── Requests/
│   │   │   ├── Auth/
│   │   │   ├── Booking/
│   │   │   └── Payment/
│   │   ├── Resources/
│   │   │   ├── UserResource.php
│   │   │   ├── BookingResource.php
│   │   │   └── PaymentResource.php
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Booking.php
│   │   ├── Payment.php
│   │   └── Pnr.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── BroadcastServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   ├── Console/
│   │   └── Kernel.php
│   ├── Exceptions/
│   │   └── Handler.php
│   └── Helpers/
│       └── helpers.php
├── bootstrap/
│   ├── app.php
│   └── cache/
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── broadcasting.php
│   ├── cache.php
│   ├── cors.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   ├── session.php
│   ├── sslcommerz.php
│   └── view.php
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_create_bookings_table.php
│   │   ├── 2024_01_01_000002_create_payments_table.php
│   │   └── 2024_01_01_000003_create_pnrs_table.php
│   └── seeds/
│       ├── DatabaseSeeder.php
│       ├── UsersTableSeeder.php
│       └── BookingsTableSeeder.php
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── uploads/
├── resources/
│   ├── lang/
│   └── views/
├── routes/
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   └── web.php
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
├── tests/
│   ├── Feature/
│   │   ├── AuthTest.php
│   │   ├── BookingTest.php
│   │   ├── PaymentTest.php
│   │   └── AdminTest.php
│   └── Unit/
│       ├── Models/
│       └── Controllers/
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
└── README.md
```

## Logger Setup:
- Implemented logging using Laravel's built-in logging system.
- Configured in `config/logging.php` to use daily files and terminal output.
- Logs errors, debug information, and important events.
- Logs are stored in `storage/logs` and also output to the terminal when running locally.

## Error Handling:
- Customized the exception handler in `app/Exceptions/Handler.php` to log detailed error information.
- In the log, includes:
  - Timestamp
  - Error level (error, warning, info, etc.)
  - Error message
  - Stack trace
  - Request details (URL, method, parameters)
  - User information (if authenticated)
- For API responses, returns structured error messages with appropriate HTTP status codes.

## Setup Instructions:
1.  **Prerequisites:**
    -   PHP 7.4 or higher
    -   MySQL 5.7 or higher
    -   Composer
    -   Laravel (via composer)

2.  **Installation:**
    ```bash
    composer create-project --prefer-dist laravel/laravel ticket-booking-system
    cd ticket-booking-system
    ```

3.  **Environment Configuration:**
    -   Copy `.env.example` to `.env`
    -   Set database credentials, app URL, and other environment variables
    -   Generate application key: `php artisan key:generate`

4.  **Database Setup:**
    -   Create a MySQL database via cPanel or command line
    -   Run migrations: `php artisan migrate:fresh` (This will drop all existing tables and re-create them)
    -   Seed the database (optional): `php artisan db:seed`

5.  **Install Dependencies:**
    ```bash
    composer require laravel/sanctum
    composer require "swagger-api/swagger-ui"
    composer require darkaonline/l5-swagger
    ```

6.  **Sanctum Configuration:**
    -   Run: `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
    -   Run migrations: `php artisan migrate`

7.  **SSLCommerz Integration:**
    -   Create a service provider for SSLCommerz (already created `config/sslcommerz.php`)
    -   Configure in `config/sslcommerz.php` (add your SSLCommerz credentials to `.env` file)

8.  **Run the Application:**
    ```bash
    php artisan serve
    ```

9.  **Testing:**
    ```bash
    php artisan test
    ```

## Deployment on cPanel (without SSH):
1.  **Upload Files:**
    -   Compress the project folder and upload via cPanel File Manager or FTP
    -   Extract in the desired directory (e.g., `public_html/ticket-booking-system`)

2.  **Set Up Database:**
    -   Create a MySQL database via cPanel's "MySQL Database Wizard"
    -   Import the database structure (if not using migrations) or run migrations via a web-based endpoint (see below)

3.  **Update .env File:**
    -   Edit the `.env` file via File Manager to set the database credentials and other environment variables

4.  **Set Permissions:**
    -   Set the `storage` directory to writable (755 or 777 if necessary)

5.  **Run Migrations (without SSH):**
    -   Create a web-based migration runner (e.g., a route that triggers migrations when accessed with a secret key)
    -   Example: Add a route in `routes/web.php`:
        ```php
        Route::get('/run-migrations', function () {
            if (request('key') !== env('MIGRATION_KEY')) {
                abort(403);
            }
            Artisan::call('migrate');
            return Artisan::output();
        });
        ```
    -   Then access: `https://yourdomain.com/ticket-booking-system/run-migrations?key=YOUR_SECRET_KEY`

6.  **Set Up Document Root:**
    -   Point the domain or subdomain to the `public` directory of the project

7.  **Test the Application:**
    -   Access the API endpoints to ensure everything is working

## API Documentation (Swagger/OpenAPI):
-   After running `php artisan l5-swagger:generate`, you can access the API documentation at `https://yourdomain.com/api/documentation` (or `http://localhost:8000/api/documentation` if running locally).

## Postman Collection:
-   A Postman collection for API testing can be generated from the Swagger documentation. You can import the generated `swagger.json` file into Postman.
- 
