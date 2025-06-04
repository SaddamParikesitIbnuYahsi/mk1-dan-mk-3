<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeSwaggerSchema extends Command
{
    protected $signature = 'make:swagger-schema {name}';
    protected $description = 'Generate a Swagger schema class for OpenAPI documentation';

    public function handle()
    {
        $name = $this->argument('name');
        $className = ucfirst($name);
        $directory = app_path('Swagger/Schemas');
        $filePath = "$directory/{$className}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($filePath)) {
            $this->error("File $filePath already exists!");
            return;
        }

        $stub = <<<EOT
<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="$className",
 *     title="$className",
 *     description="$className model",
 *     type="object",
 *     required={"id", "name"},
 *     
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="$className Name")
 * )
 */
class $className {}

EOT;

        File::put($filePath, $stub);
        $this->info("Swagger schema for $className created at $filePath.");
    }
}
