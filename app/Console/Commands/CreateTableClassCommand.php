<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class CreateTableClassCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:table {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Table class';

    protected function getStub()
    {
        return $this->resolveStubPath("/app/Console/Commands/stubs/table.stub");
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Tables';
    }

    protected function replaceClass($stub, $name)
    {
        $class = Str::plural($this->argument('name'));
        $model = Str::singular($this->argument('name'));

        $replacedStub = $stub;

        $replacedStub = str_replace('{{ namespace }}', "App\Tables", $replacedStub);
        $replacedStub = str_replace('{{ class }}', $class, $replacedStub);
        $replacedStub = str_replace('{{ model }}', $model, $replacedStub);
        
        return $replacedStub;
    }

    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
                        ? $customPath
                        : __DIR__.$stub;
    }
}
