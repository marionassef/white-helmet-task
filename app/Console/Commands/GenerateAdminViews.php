<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\File;

class GenerateAdminViews extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:adminViews {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate Admin Views';

    /**
     * Create a new command instance.
     *
     * @return void
     */

//    public function __construct()
//    {
//        parent::__construct();
//    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $className = $this->argument('name');
        $this->createViews(strtolower($className));
    }

    protected function createViews($className)
    {
        $path = base_path('resources/views/admin/' . $className);

        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $this->CreateOnView('index',$className);
        $this->CreateOnView('create',$className);
        $this->CreateOnView('edit',$className);
        $this->CreateOnView('details',$className);
    }

    protected function CreateOnView($view,$className)
    {
        $path = base_path('resources/views/admin/' . $className . '/' . $view . '.blade.php');
        $this->line('Done create view .');
        $this->files->put($path, $this->buildView($className, __DIR__ . '/stubs/' . $view . '.stub'));
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/model.stub';
    }

    protected function replace(&$stub, $rep, $name)
    {
        $stub = str_replace(
            [$rep],
            $name,
            $stub
        );

        return $this;
    }

    protected function buildView($name, $stub)
    {
        $stub = $this->files->get($stub);
        return $this->replace($stub, 'DummyRoute', lcfirst($name))
            ->replaceView($stub, 'DummyView', ucwords($name));
    }

    protected function replaceView(&$stub, $rep, $name)
    {
        $stub = str_replace(
            [$rep],
            $name,
            $stub
        );
        return $stub;
    }
}
