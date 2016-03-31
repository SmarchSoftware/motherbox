<?php

namespace Smarch\Motherbox\Commands;

use Illuminate\Console\Command;

class PackageConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:config
                            {name : The name of the package config file.}
                            {path : Path to the package config file.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a config file for your package.';

    /**
     * Package name, used for config file name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Upper case name
     *
     * @var string
     */
    protected $ucName = '';

    /**
     * Package path
     *
     * @var string
     */
    protected $path = '';

    /**
     * Stub file
     *
     * @var string
     */
    protected $stub = '';

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
        $this->line('Config Name: '. $this->argument('name') );
        $this->line('Config Path: '. $this->argument('path') . '\\Config' );
        // $this->name = strtolower( $this->argument('name') );
        // $this->ucName = ucwords( $this->crudName );
        // $this->stub = ''
        // $this->path = app_path()

        // $newConfigFile = config_path() . '/'. $this->crudName .'.php';
        // if ( ! File::copy($this->stub, $newConfigFile)) {
        //     $this->info("failed to copy $stub...\n");
        // } else {
        //     File::put($newConfigFile, str_replace('%%crudNameLower%%', $this->crudName, File::get($newConfigFile)));
        //     File::put($newConfigFile, str_replace('%%crudNameUpper%%', $this->crudNameCap, File::get($newConfigFile)));

        //     $this->info('Config created successfully.');
        // }
    }
}
