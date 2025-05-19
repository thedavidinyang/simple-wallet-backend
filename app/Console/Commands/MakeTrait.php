<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeTrait extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait with support for folders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name')); // Ensure proper class naming
        $path = app_path('Traits/' . str_replace('\\', '/', $name) . '.php');

        if (file_exists($path)) {
            $this->error("Trait {$name} already exists!");
            return;
        }

        // Ensure directory exists
        (new Filesystem)->ensureDirectoryExists(dirname($path));

        // Generate the trait file
        file_put_contents($path, $this->generateTraitContent($name));

        $this->info("Trait {$name} created successfully.");
    }

    /**
     * Generate the content for the trait.
     */
    protected function generateTraitContent($name)
    {
        // Convert folder structure into a valid namespace
        $folder = trim(dirname($name), '.'); // Remove "." if no folder is present
        $namespace = 'App\Traits' . ($folder ? '\\' . Str::replace('/', '\\', $folder) : '');
        $namespace = trim($namespace, '\\'); // Remove trailing slash if present
        $className = class_basename($name);

        return <<<PHP
        <?php

        namespace {$namespace};

        trait {$className}
        {
            //
        }
        PHP;
    }
}
