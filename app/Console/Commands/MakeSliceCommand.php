<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeSliceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:slice {name} {--migration : Generate a migration file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new vertical slice structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $basePath = app_path("Slices/{$name}");

        if (File::exists($basePath)) {
            $this->error("Slice '{$name}' already exists.");
            return;
        }

        // Create directories
        $dirs = [
            "{$basePath}/Http",
            "{$basePath}/Actions",
            "{$basePath}/Models",
            "{$basePath}/Views",
            "{$basePath}/Tests",
            "{$basePath}/Providers",
        ];

        foreach ($dirs as $dir) {
            File::makeDirectory($dir, 0755, true);
        }

        // Create files
        File::put("{$basePath}/Http/{$name}Controller.php", $this->controllerStub($name));
        File::put("{$basePath}/Http/{$name}Request.php", $this->requestStub($name));
        File::put("{$basePath}/Http/routes.php", $this->routeStub($name));
        File::put("{$basePath}/Actions/{$name}Handler.php", $this->handlerStub($name));
        File::put("{$basePath}/Models/{$name}.php", $this->modelStub($name));
        File::put("{$basePath}/Providers/{$name}ServiceProvider.php", $this->providerStub($name));
        File::put("{$basePath}/Views/form.blade.php", $this->viewStub());
        File::put("{$basePath}/Tests/{$name}Test.php", $this->testStub($name));

        // Create migration if requested
        if ($this->option('migration')) {
            $this->createMigration($name);
        }

        $this->info("Slice '{$name}' created successfully!");
    }

    protected function controllerStub($name)
    {
        return <<<PHP
<?php

namespace App\Slices\\$name\Http;

use App\Http\Controllers\Controller;
use App\Slices\\$name\Actions\\{$name}Handler;
use App\Slices\\$name\Http\\{$name}Request;

class {$name}Controller extends Controller
{
    public function handle({$name}Request \$request)
    {
        \$handler = new {$name}Handler();
        return \$handler->handle(\$request->validated());
    }
}
PHP;
    }

    protected function requestStub($name)
    {
        return <<<PHP
<?php

namespace App\Slices\\$name\Http;

use Illuminate\Foundation\Http\FormRequest;

class {$name}Request extends FormRequest
{
    public function rules(): array
    {
        return [
            // Add your validation rules here
        ];
    }
}
PHP;
    }

    protected function routeStub($name)
    {
        $routeName = Str::kebab($name);
        return <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use App\Slices\\$name\Http\\{$name}Controller;

Route::post('/$routeName', [{$name}Controller::class, 'handle'])->name('$routeName');
PHP;
    }

    protected function handlerStub($name)
    {
        return <<<PHP
<?php

namespace App\Slices\\$name\Actions;

use App\Slices\\$name\Models\\$name;

class {$name}Handler
{
    public function handle(array \$data)
    {
        // Create a new record
        \$item = {$name}::create(\$data);

        return response()->json([
            'success' => true,
            'id' => \$item->id
        ]);
    }
}
PHP;
    }

    protected function modelStub($name)
    {
        $table = Str::snake(Str::pluralStudly($name));

        return <<<PHP
<?php

namespace App\Slices\\$name\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected \$table = '$table';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected \$fillable = [
        // Add your fillable attributes here
    ];
}
PHP;
    }

    protected function providerStub($name)
    {
        return <<<PHP
<?php

namespace App\Slices\\$name\Providers;

use Illuminate\Support\ServiceProvider;

class {$name}ServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register any bindings for this slice
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load routes
        \$this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        // Load views with a specific namespace
        \$this->loadViewsFrom(__DIR__ . '/../Views', '$name');
    }
}
PHP;
    }

    protected function viewStub()
    {
        return <<<BLADE
<!-- form.blade.php -->
<form method="POST" action="{{ route('example') }}">
    @csrf
    <!-- Your form inputs here -->
    <button type="submit">Submit</button>
</form>
BLADE;
    }

    protected function testStub($name)
    {
        $routeName = Str::kebab($name);
        return <<<PHP
<?php

namespace App\Slices\\$name\Tests;

use Tests\TestCase;

class {$name}Test extends TestCase
{
    public function test_can_handle_request()
    {
        \$response = \$this->post('/$routeName', []);
        \$response->assertStatus(200);
    }
}
PHP;
    }

    protected function createMigration($name)
    {
        $tableName = Str::snake(Str::pluralStudly($name));
        $migrationName = "create_{$tableName}_table";
        $timestamp = date('Y_m_d_His');
        $filename = $timestamp . "_" . $migrationName . ".php";
        $path = database_path("migrations/{$filename}");

        $stub = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('$tableName', function (Blueprint \$table) {
            \$table->id();
            // Add your columns here
            \$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('$tableName');
    }
};
PHP;

        File::put($path, $stub);
        $this->info("Migration created: {$filename}");
    }
}
