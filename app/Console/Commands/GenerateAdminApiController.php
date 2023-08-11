<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\File;

class GenerateAdminApiController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:apiController {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate apiController';

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
        $this->createApiController($ClassName);
    }

    protected function createApiController($className)
    {
        $path = $this->getPath('Http/Controllers/API/' . $className . 'ApiController');
        $this->files->put($path, $this->buildStubs($className,  __DIR__ . '/stubs/apiController.stub' . ''));
        $this->line('Done create Controller' . $className . 'Controller .');

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
            ->replace($stub, 'DummyClassName', $className)
            ->replace($stub, 'DummyCapsClassName', $className)
            ->replaceNamespace($stub, $className)
            ->replaceClass($stub, $className.'ApiController');
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
