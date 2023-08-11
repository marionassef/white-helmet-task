<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\File;

class GenerateAdminApiModule extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:adminApiModule {name}';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $className = $this->argument('name');

        $this->callSilent('generate:adminRepository', [
            'name' => $className
        ]);

        $this->callSilent('generate:apiRoutes', [
            'name' => $className
        ]);

        $this->callSilent('make:request', [
            'name' => $className.'/Create'.$className."Request"
        ]);

        $this->callSilent('make:request', [
            'name' => $className.'/Details'.$className."Request"
        ]);

        $this->callSilent('make:request', [
            'name' => $className.'/Update'.$className."Request"
        ]);

        $this->callSilent('make:request', [
            'name' => $className.'/Delete'.$className."Request"
        ]);

        $this->callSilent('generate:apiController', [
            'name' => $className
        ]);

        $this->callSilent('generate:adminService', [
            'name' => $className
        ]);

        $this->callSilent('make:model', [
            'name' => $className
        ]);

        $this->callSilent('make:resource', [
            'name' => $className."Resource"
        ]);

        $this->callSilent('generate:apiControllerTest', [
            'name' => $className."Test"
        ]);
        $this->line('Done create Api module');
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

    protected function appendRoutes($className)
    {
        $path = base_path('routes/web.php');
        $this->line('Done append routes .');
        $this->files->append($path, $this->buildRoute($className, __DIR__ . '/stubs/routes.stub'));
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
