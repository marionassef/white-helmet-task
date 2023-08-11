<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\File;

class GenerateAdminApiTestController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:apiControllerTest {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate apiControllerTest';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ClassName = $this->argument('name');
        $this->createApiControllerTest($ClassName);
    }

    protected function createApiControllerTest($className)
    {
        $path = base_path('tests/Feature/Http/Controllers/API/' . $className . 'ApiControllerTest.php');
        $this->line('Done create ' . $className . 'ControllerTest .');
        $this->files->put($path, $this->buildStubs($className,  __DIR__ . '/stubs/apiControllerTest.stub' . ''));

    }

    protected function buildStubs($className, $stub)
    {
        $stub = $this->files->get($stub);
        return $this
            ->replace($stub, 'listRoute', lcfirst($className.'/list'))
            ->replace($stub, 'storeRoute', lcfirst($className.'/store'))
            ->replace($stub, 'detailsRoute', lcfirst($className.'/details'))
            ->replace($stub, 'updateRoute', lcfirst($className.'/update'))
            ->replace($stub, 'deleteRoute', lcfirst($className.'/delete'))
            ->replaceNamespace($stub, $className)
            ->replaceClass($stub, $className.'ApiControllerTest');
    }

    protected function buildClassController($ClassName, $controllerName, $modelName, $viewName, $repo, $transformer, $stub)
    {
        $stub = $this->files->get($stub);
        return $this->replace($stub, 'DummyModel', $modelName)
            ->replace($stub, 'DummyView', $viewName)
            ->replace($stub, 'DummyRepo', $repo)
            ->replace($stub, 'DummyTransformer', $transformer)
            ->replaceNamespace($stub, $ClassName)
            ->replaceClass($stub, $controllerName);
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
