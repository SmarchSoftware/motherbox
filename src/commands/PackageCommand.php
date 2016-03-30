<?php

namespace Smarch\Motherbox\Commands;

use Illuminate\Console\Command;

class PackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:package
                            {name? : The name of the package.}
                            {vendor? : The vendor name of the package.}
                            {namespace? : Custom Namespace for your package?}
                            {path? : Path for all your package files?}
                            {--C|config=yes : Include a config file? [yes/no]}
                            {--M|migration=yes : Include a migration file? [yes/no]}
                            {--O|model=yes : Include a model file? [yes/no]}
                            {--R|routes=yes : Include a routes.php file in package? [yes/no]}
                            {--F|facade=no : Include a laravel facade file? [yes/no]}
                            {--I|middleware=no : Include a custom middleware file? [yes/no]}
                            {--P|policy=no : Include a custom policy file? [yes/no]}
                            {--E|requests=no : Include form request validation files in package? [yes/no]}
                            {--S|seed=no : Include a database seed file? [yes/no]}
                            {--T|test=no : Include PhpUnit Test file? [yes/no]}
                            {--fields= : Fields name for the form(s) & model.}
                            {--pk=id : The name of the primary key.}
                            {--author=vendor : Package author name for composer.}
                            {--email= name@vendor.com : Package author email for composer.}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a laravel 5.1 composer package';

    /**
     * The composer package name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The composer package vendor
     *
     * @var string
     */
    protected $vendor = '';

    /**
     * The composer full package name (vendor/name)
     *
     * @var string
     */
    protected $package = '';

    /**
     * The composer full package name (vendor/name)
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * The path for the package files
     *
     * @var string
     */
    protected $path = '';

    /**
     * Command options that make files.
     *
     * @var array
     */
    protected $makeFiles = ['config','middleware','migration','model','routes','facade','policy','requests','seed','test'];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->makeVendorName( $this->argument('vendor'), $this->argument('name') );
        $this->makeNamespace();
        $this->makePath();

        $this->callOptions();

        $this->line('Package name is '.$this->package);
        $this->line('Namespace is '.$this->namespace);
        $this->line('Path is '.$this->path);
    }

    protected function makeVendorName($v='',$n='')
    {
        $this->vendor = ($v) ?: ( config('motherbox.vendor') ?: $this->ask('Name of the vendor for your composer package?') );
        $this->name = ($n) ?: $this->ask('Name of your composer package?');;
        $this->package = strtolower($this->vendor . '\\'. $this->name);
        
        if ($this->confirm("Please confirm you would like this vendor\\name combination : " . $this->package)) {
            return;
        }

        $this->makeVendorName();        
    }


    protected function makeNamespace()
    {
        $this->namespace = ($this->argument('namespace')) ?: ucwords($this->vendor) . '\\'. ucwords($this->name);        
    }


    protected function makePath()
    {
        $this->path = ($this->argument('path')) ?: (config('motherbox.path') ?: '\\'.$this->package);
    }

    protected function callOptions() 
    {
        foreach($this->option() as $k => $v) {
            if ( in_array($k,$this->makeFiles) && $v === 'yes' )
                $this->line($k);            
        }
    }
}
