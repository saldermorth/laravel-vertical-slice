# Laravel Vertical Slice Architecture

This project demonstrates a vertical slice architecture implementation for Laravel 12+, organizing code by feature rather than technical concerns.

**GitHub Repository**: [https://github.com/saldermorth/laravel-vertical-slice](https://github.com/saldermorth/laravel-vertical-slice)

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

### Role of Generated Files

Each file in a slice has a specific role:

**Http Layer:**

-   **Controller (`CreateOrderController.php`)**: Handles incoming HTTP requests, delegates to the action handler
-   **Request (`CreateOrderRequest.php`)**: Validates incoming request data specific to this feature
-   **Routes (`routes.php`)**: Defines the routes specific to this feature

**Business Logic:**

-   **Action Handler (`CreateOrderHandler.php`)**: Contains the business logic for the feature, separating it from the controller

**Data Layer:**

-   **Model (`CreateOrder.php`)**: Feature-specific model that represents the data structure

**Presentation:**

-   **Views (`form.blade.php`)**: Contains the feature-specific templates and presentation logic

**Testing:**

-   **Tests (`CreateOrderTest.php`)**: Feature-specific tests to ensure the slice functions correctly

**Service Provider:**

-   **Provider (`CreateOrderServiceProvider.php`)**: Registers slice-specific routes, views, and other dependencies

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

### Slice Independence

Each slice is designed to be independent and self-contained. This means:

1. Each slice can be developed, tested, and maintained separately
2. Changes to one slice should not affect others
3. Dependencies between slices are explicit and minimized
4. Each slice can have its own dedicated models, even if they represent the same database table

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
6. **Cohesive file organization**: Everything related to a feature stays in its slice
7. **Use meaningful slice names**: Name slices after what they do (e.g., CreateOrder, ManageUsers)
8. **Slice-specific migrations**: Consider placing migrations in a Database directory within each slice

### When to Create a New Slice

Create a new slice when:

-   You're implementing a new user-facing feature
-   The feature has its own UI, business logic, and possibly data access
-   The feature could potentially be developed by a separate team member
-   The feature is relatively isolated from other parts of the application

### Example Workflow

1. Identify a feature needed in your application (e.g., "Create Order")
2. Run `php artisan make:slice CreateOrder --migration`
3. Implement the feature entirely within its slice
4. Access the feature through the automatically registered routes

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
