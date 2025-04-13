# Laravel Vertical Slice Architecture

This project demonstrates a vertical slice architecture implementation for Laravel 12+, organizing code by feature rather than technical concerns.

## What is Vertical Slice Architecture?

Vertical slice architecture organizes code by feature or "slice" rather than by technical layer. Each slice contains everything needed for a specific feature:

-   Controllers
-   Requests
-   Models
-   Views
-   Actions/Handlers
-   Tests

This approach offers several benefits:

-   **Improved cohesion**: Related code stays together
-   **Reduced coupling**: Features are isolated from each other
-   **Easier maintenance**: Changes to one feature have minimal impact on others
-   **Better scalability**: Teams can work on different features independently

## Project Structure

```
app/
└── Slices/
    └── CreateOrder/
        ├── Http/
        │   ├── CreateOrderController.php
        │   ├── CreateOrderRequest.php
        │   └── routes.php
        ├── Actions/
        │   └── CreateOrderHandler.php
        ├── Models/
        │   └── CreateOrder.php
        ├── Views/
        │   └── form.blade.php
        ├── Tests/
        │   └── CreateOrderTest.php
        └── Providers/
            └── CreateOrderServiceProvider.php
```

## Getting Started

### Prerequisites

-   PHP 8.2+
-   Composer
-   Laravel 12+

### Installation

1. Clone the repository

```bash
git clone https://github.com/yourusername/laravel-vertical-slice.git
cd laravel-vertical-slice
```

2. Install dependencies

```bash
composer install
```

3. Copy environment file and set up your database

```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations

```bash
php artisan migrate
```

## Creating a New Slice

This project includes a custom Artisan command to generate new slices:

```bash
php artisan make:slice YourFeatureName
```

This will create all necessary files and directories for your feature slice.

To also generate a migration for your feature:

```bash
php artisan make:slice YourFeatureName --migration
```

## How It Works

### Service Provider Auto-Discovery

Service providers from each slice are automatically discovered and registered in `bootstrap/app.php`:

```php
// Dynamically discover slice service providers
$slicesDir = dirname(__DIR__) . '/app/Slices';
$providers = [];

if (is_dir($slicesDir)) {
    $slices = array_filter(glob($slicesDir . '/*'), 'is_dir');
    foreach ($slices as $slice) {
        $sliceName = basename($slice);
        $providerPath = "{$slice}/Providers/{$sliceName}ServiceProvider.php";
        if (file_exists($providerPath)) {
            $providers[] = "App\\Slices\\{$sliceName}\\Providers\\{$sliceName}ServiceProvider";
        }
    }
}
```

### Route Auto-Loading

Routes from each slice are automatically loaded in `bootstrap/app.php`:

```php
->withRouting(
    web: function() {
        require __DIR__ . '/../routes/web.php';

        // Load slice routes
        $slicesDir = dirname(__DIR__) . '/app/Slices';
        if (is_dir($slicesDir)) {
            $slices = array_filter(glob($slicesDir . '/*'), 'is_dir');
            foreach ($slices as $slice) {
                $routesFile = "{$slice}/Http/routes.php";
                if (file_exists($routesFile)) {
                    require $routesFile;
                }
            }
        }
    },
    // ...
)
```

## Best Practices

1. **Keep slices independent**: Minimize dependencies between slices
2. **Use interfaces for cross-slice communication**: Define clear contracts between slices
3. **Shared code**: Place shared functionality in a `Shared` slice
4. **Think in features**: Design your slices around user features, not technical concerns
5. **Test slice boundaries**: Write tests that verify each slice works correctly in isolation

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
