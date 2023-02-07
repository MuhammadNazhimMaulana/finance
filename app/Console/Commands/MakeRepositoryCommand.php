<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeRepositoryCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->createInterface();

        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "'.$this->getNameInput().'" is reserved by PHP.');

            return false;
        }

        $name = $this->qualifyClass($this->getNameInput()).'Repository';

        $path = $this->getPath($name);

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
             ! $this->option('force')) &&
             $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info($this->type.' created successfully.');
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $name = Str::studly(class_basename($this->argument('name')));

        $interfaceClassName = $this->qualifyClass($this->getNameInput());
        $interfaceClassName = str_replace('Repositories', 'Interfaces', $interfaceClassName);
        $interfaceClassName = $interfaceClassName.'Interface';

        $stub = str_replace('{{ interfacenamespace }}', $interfaceClassName, $stub);
        $stub = str_replace('{{ interfacename }}', "{$name}Interface", $stub);

        $this->updateRepositoryProvider($interfaceClassName,$this->qualifyClass($this->getNameInput().'Repository'), $name);

        return str_replace('{{ class }}', "{$name}Repository", $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return app_path().'/Console/Commands/Stubs/repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the interface'],
        ];
    }

    /**
     * Create a interface.
     *
     * @return void
     */
    protected function createInterface()
    {
        $this->call('make:interface', [
            'name' => $this->argument('name'),
        ]);
    }

    protected function updateRepositoryProvider($interface, $repository, $comment)
    {
        $data = "//{$comment}\n\t\t".'$this->app->bind("'.$interface.'","'.$repository.'");';
        $filecontent = file_get_contents(app_path().'/Providers/RepositoryProvider.php');

        $pos = strpos($filecontent, '//DO_NOT_REMOVE_THIS_COMMENT');
        if ($pos !== false) {
            $filecontent = substr($filecontent, 0, $pos)."\r\n \t\t".$data."\r\n \t\t".substr($filecontent, $pos);

            file_put_contents(app_path().'/Providers/RepositoryProvider.php', $filecontent);
        }
    }
}
