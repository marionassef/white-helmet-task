<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\File;

class GenerateAdminModule extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:adminModule {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'will tell u later, punk';

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
//        $this->callSilent('generate:adminModel', [
//            'name' => $className
//        ]);
        $this->callSilent('generate:adminController', [
            'name' => $className
        ]);
        $this->callSilent('generate:adminRepository', [
            'name' => $className
        ]);

        $this->callSilent('generate:adminViews', [
            'name' => $className
        ]);
        $this->callSilent('generate:adminRoutes', [
            'name' => $className
        ]);

        $this->callSilent('make:request', [
            'name' => $className.'/'.$className."CreateRequest"
        ]);

        $this->callSilent('make:request', [
            'name' => $className.'/'.$className."DetailsRequest"
        ]);

        $this->callSilent('make:request', [
            'name' => $className.'/'.$className."UpdateRequest"
        ]);

        $this->callSilent('make:request', [
            'name' => $className.'/'.$className."DeleteRequest"
        ]);

        $this->callSilent('generate:apiController', [
            'name' => $className
        ]);

        $this->callSilent('generate:adminService', [
            'name' => $className
        ]);
        $this->line('Done create Admin module');

    }

    protected function createModel($className)
    {
        $path = $this->getPath($className);
        $this->line('Done create Model ' . $className . ' .');
        $this->files->put($path, $this->buildClass($className));
    }

    protected function createController($className)
    {
        $controllerName = $className. 'Controller';
        $repo =$className . 'Repo';
        $transformer = $className . 'Resource';
        $modelName = $className;
        $viewName = $className;
        $path = $this->getPath('Http/Controllers/' . $className . 'Controller');
        $this->line('Done create Controller' . $className . 'Controller .');
        $this->files->put($path, $this->buildClassController($className, $controllerName, $modelName, $viewName, $repo, $transformer, __DIR__ . '/stubs/controller.stub' . ''));

    }

    protected function createRepo($className)
    {
        if (!file_exists(app_path('Repos'))) { mkdir(app_path('Repos'), 0777, true); }
        $controllerName = $className. 'Controller';
        $repo = $className . 'Repo';
        $transformer = $className . 'Resource';
        $modelName = $className;
        $viewName = $className;
        $path = $this->getPath('Repos/' . $className . 'Repo');
        $this->line('Done create Controller  at Application Transformer admin ' .$className . 'Repo .');
        $this->files->put($path, $this->buildClassController($className, $controllerName,
            $modelName, $viewName, $repo, $transformer, __DIR__ . '/stubs/repo.stub'));
    }

    protected function createTransformer($className)
    {
        if (!file_exists(app_path('Http/Resources'))) { mkdir(app_path('Http/Resources'), 0777, true); }
        $controllerName = $className. 'Controller';
        $repo = $className . 'Repo';
        $transformer = $className . 'Resource';
        $modelName = $className;
        $viewName = $className;
        $path = $this->getPath('Http/Resources/' . $className . 'Resource');
        $this->line('Done create Resource ' . $className );
//        $this->files->put($path, $this->buildTransformer( $name ,  $transformer  , __DIR__.'/stub/transformer.stub'));
        $this->files->put($path, $this->buildClassController($className, $controllerName, $modelName, $viewName, $repo, $transformer, __DIR__ . '/stubs/transformer.stub'));
    }

    protected function createViews($className)
    {
        $path = base_path('resources/views/' . $className);

        if (!file_exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $this->CreateOnView('index',$className);
        $this->CreateOnView('edit',$className);
        $this->CreateOnView('view',$className);
    }

    protected function appendRoutes($className)
    {
        $path = base_path('routes/web.php');
        $this->line('Done append routes .');
        $this->files->append($path, $this->buildRoute($className, __DIR__ . '/stubs/routes.stub'));
    }


    protected function CreateOnView($view,$className)
    {
        $path = base_path('resources/views/' . $className . '/
        ' . $view . '.blade.php');
        $this->line('Done create view .');
        $this->files->put($path, $this->buildView($className, __DIR__ . '/stubs/' . $view . '.stub'));
    }

    protected function buildRoute($className, $stub)
    {
        $stub = $this->files->get($stub);
        return $this->replace($stub, 'DummyRoute', $className)
            ->replaceView($stub, 'DummyView', $className);
    }


    protected function buildClassController($className, $controllerName, $modelName, $viewName, $repo, $transformer, $stub)
    {
        $stub = $this->files->get($stub);
        return $this->replace($stub, 'DummyModel', $modelName)
            ->replace($stub, 'DummyView', $viewName)
            ->replace($stub, 'DummyRepo', $repo)
            ->replace($stub, 'DummyTransformer', $transformer)
            ->replaceNamespace($stub, $className)
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
