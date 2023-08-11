<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\File;

class GenerateAdminService extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:adminService {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate Admin Service';

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
        $this->createService($className);
    }

    protected function createService($className)
    {
        if (!file_exists(app_path('Services'))) { mkdir(app_path('Services'), 0777, true); }
        $path = $this->getPath('Services/' . $className . 'Service');
        $this->line('Done create Service for ' .$className);
        $this->files->put($path, $this->buildStubs($className, __DIR__ . '/stubs/service.stub'));
    }

    protected function createRepo($className)
    {
        if (!file_exists(app_path('Repositories'))) { mkdir(app_path('Repositories'), 0777, true); }
        $path = $this->getPath('Repositories/' . $className . 'Repository');
        $this->line('Done create Controller  at Application Transformer admin ' .$className . 'Repo .');
        $this->files->put($path, $this->buildStubs($className, __DIR__ . '/stubs/repository.stub'));
    }

    protected function buildStubs($className, $stub)
    {
        $stub = $this->files->get($stub);
        return $this
            ->replace($stub, 'DummyRepository', $className.'Repository')
            ->replace($stub, 'DummyRepo', lcfirst($className.'Repository'))
            ->replace($stub, 'DummyClassName', $className.'Service')
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
