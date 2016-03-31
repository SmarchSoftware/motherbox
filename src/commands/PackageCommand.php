<?php

namespace Smarch\Motherbox\Commands;

use File, Storage;
use Illuminate\Console\Command;

class PackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:generate
                            {name? : The name of the package.}
                            {vendor? : The vendor name of the package.}
                            {namespace? : Custom Namespace for your package?}
                            {path? : Path for all your package files?}
                            {--C|config= : Include a config file? [yes/no]}
                            {--M|migration= : Include a migration file? [yes/no]}
                            {--O|model= : Include a model file? [yes/no]}
                            {--R|routes= : Include a routes.php file in package? [yes/no]}
                            {--F|facade= : Include a laravel facade file? [yes/no]}
                            {--I|middleware= : Include a custom middleware file? [yes/no]}
                            {--P|policy= : Include a custom policy file? [yes/no]}
                            {--E|requests= : Include form request validation files in package? [yes/no]}
                            {--S|seed= : Include a database seed file? [yes/no]}
                            {--T|test= : Include PhpUnit Test file? [yes/no]}
                            {--fields= : Fields name for the form(s) & model.}
                            {--pk= : The name of the primary key.}
                            {--author= : Package author name for composer.}
                            {--email= : Package author email for composer.}
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
     * The base path to the packages
     *
     * @var string
     */
    protected $path = '';

    /**
     * The path for this package files
     *
     * @var string
     */
    protected $packagePath = '';

    /**
     * The path for this package src files
     *
     * @var string
     */
    protected $srcPath = '';

    /**
     * Command options that make files.
     *
     * @var array
     */
    protected $makeFiles = ['config','middleware','migration','model','routes','facade','policy','requests','seed','test'];

    /**
     * Stub strings to replace.
     *
     * @var array
     */
    protected $searchWords = ['{{package}}','{{vendor}}','{{name}}','{capVendor}}','{{capName}}','{{namespace}}','{{capNamespace}}'];

    /**
     * Stub replacement strings.
     *
     * @var array
     */
    protected $replaceWords = [];

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
        $this->makePaths();
        $this->getOptions();

        $this->makeReplaceWords();

        $this->makePackageDirectory();

        $this->makeComposer();

        $this->callOptions();
    }

    protected function makeReplaceWords()
    {
        $this->replaceWords = [$this->package, $this->vendor, $this->name, ucfirst($this->vendor), ucfirst($this->name), $this->namespace, $this->capNamespace];

    }

    protected function makePackageDirectory()
    {
        if ( ! File::isDirectory($this->packagePath) ) {
            $this->line($this->packagePath);
            mkdir($this->packagePath);
        }
    }

    protected function makeComposer()
    {
        $search = array_push($this->searchWords,'{{author}}','{{email}}');
        $replace = array_push($this->replaceWords,$this->author,$this->email);
        $composerStub = $this->stubPath . '\composer.json.stub';
        $composerFile = $this->packagePath . '\composer.json';

        Storage::put( $composerFile, str_replace( $search, $replace, Storage::get($composerStub) ) );
    }

    protected function getOptions()
    {
        foreach($this->option() as $k => $v) {
            $this->$k = ($v) ?: config('motherbox.'.$k);
            $this->line($k .'='.$this->$k);            
        }
    }

    protected function callOptions() 
    {
        $this->call('package:config', [ 'name' => $this->name, 'path' => $this->srcPath ]);
    }

    protected function makeVendorName($v='',$n='')
    {
        $this->vendor = ($v) ?: ( config('motherbox.vendor') ?: $this->ask('Name of the vendor for your composer package?') );
        $this->name = ($n) ?: ( config('motherbox.name') ?: $this->ask('Name of your composer package?') );
        $this->package = strtolower($this->vendor . '\\'. $this->name);
        
        if ($this->confirm("Please confirm you would like this vendor\\name combination : " . $this->package)) {
            return;
        }

        $this->makeVendorName();        
    }


    protected function makeNamespace()
    {
        $this->namespace = ($this->argument('namespace')) ?:  ( config('motherbox.namespace') ?: ucwords($this->vendor) . '\\'. ucwords($this->name) );
        $this->capNamespace = ucwords($this->namespace);
    }


    protected function makePaths()
    {
        $this->path = base_path() . '/' . ( ( $this->argument('path') ) ?: ( config('motherbox.path') ?: $this->package ) );
        $this->packagePath = $this->path . '\\' . $this->package;
        $this->srcPath = $this->packagePath . '/src';
        $this->stubPath = __DIR__.'/../stubs';
    }
}
