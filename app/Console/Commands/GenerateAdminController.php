<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\File;

class GenerateAdminController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:adminController {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate Admin Controller';

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
        $ClassName = $this->argument('name');
        $this->createController($ClassName);
    }

    protected function createController($className)
    {
        $path = $this->getPath('Http/Controllers/' . $className . 'Controller');
        $this->line('Done create ' . $className . 'Controller.');
        $this->files->put($path, $this->buildStubs($className, __DIR__ . '/stubs/controller.stub'));

    }

    protected function buildStubs($className, $stub)
    {
        $stub = $this->files->get($stub);
        return $this
            ->replace($stub, 'DummyService', lcfirst($className.'Service'))
            ->replace($stub, 'DummyServ', $className.'Service')
            ->replace($stub, 'DummyCreateRequest', $className.'CreateRequest')
            ->replace($stub, 'DummyUpdateRequest', $className.'UpdateRequest')
            ->replace($stub, 'DummyDetailsRequest', $className.'DetailsRequest')
            ->replace($stub, 'DummyDeleteRequest', $className.'DeleteRequest')
            ->replace($stub, 'DummyClassName', lcfirst($className))
            ->replace($stub, 'DummyCapsClassName', $className)
            ->replaceNamespace($stub, $className)
            ->replaceClass($stub, $className.'Controller');
    }


    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

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
        return $this->replaceView($stub, 'DummyView', $name);
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
