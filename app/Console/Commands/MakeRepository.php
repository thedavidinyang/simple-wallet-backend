<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository  {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Repository  class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name')); 
        $path = app_path('Repositories/' . str_replace('\\', '/', $name) . '.php');

        if (file_exists($path)) {
            $this->error("Repository {$name} already exists!");
            return;
        }

        (new Filesystem)->ensureDirectoryExists(dirname($path));

        file_put_contents($path, $this->generateRepositoryContent($name));

        $this->info("Repository {$name} created successfully.");
    }


    protected function generateRepositoryContent($name)
    {
        $folder = trim(dirname($name), '.'); 
        $namespace = 'App\Repositories' . ($folder ? '\\' . Str::replace('/', '\\', $folder) : '');
        $namespace = trim($namespace, '\\'); 
        $className = class_basename($name);

        return <<<PHP
        <?php

        namespace {$namespace};

        class {$className}
        {
            //
        }
        PHP;
    }
}
