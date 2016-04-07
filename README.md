
![](http://i.imgur.com/B9Xa0VO.png)

This is probably only of use to me, but I have need of it in multiple apps so I packaged it up in case you want it too. :)

***

# Motherbox
A package generator for Laravel 5. I used to use "workbench" all the time in laravel 4 and after not liking the ones I found and getting tired of copying/pasting/manipulating a bunch of files after running a CRUD generator, I decided to make a full package generator.

## Overview
From a single line on the command prompt, Motherbox will generate an entire packagist.com ready package for laravel. You can use the provided stub files or you can customize the stub files to work the way you want. You can, optionally, create _(or not)_ any of the following files for your package :

* Composer.json file (with configurable options)
    * Vendor
    * Name
    * Author
    * Email
* Config
* Controller
* Facade
* License
* Middleware
* Migration
    * Migrate (if you just want to make the file but not run it, set "migrate" to "no")
* Model
    * Table
    * Primary Key
    * Fields
    * Fillable
    * Guarded
* Policy
* Requests
* Routes
* Seed
* Test
* Views _(create, show/edit and index)_

You can place the info for the config options you don't want to to type out in the motherbox config file. So if you make numerous packages and never need Migration files you can set your config file for <kbd>migration = 'no'</kbd> and never make one. If you decide, however, for one package you need a migration you can add the option of <kbd>--migration=yes</kbd> and it will create the file regardless. **Command line options always supercede config file options.**

## Installation

This page is intended for installation, please check out the [wiki](https://github.com/SmarchSoftware/motherbox/wiki) for more information about usage. (In progress)

#### :black_square_button: Composer

    composer require "smarch/motherbox"

#### :pencil: Service Provider

Motherbox is a Laravel atristan command but the views it generatesuses the [HTML Forms](https://laravelcollective.com/docs/5.1/html) package from the "Laravel Collective" for Html & Form rendering so composer will install that as well if you don't already have it installed _(you probably do...or should)_. Once composer has installed the necessary packages for Motherbox to function you need to open your laravel config page for service providers and add Motherbox _(and if necessary the Laravel Collective Html provider)_. To properly function you need to have both service providers referenced : [HTML Forms](https://laravelcollective.com/docs/5.1/html) and Motherbox.

*config/app.php*
       
       /*
        * Third Party Service Providers
        */
        Collective\Html\HtmlServiceProvider::class, // For Motherbox to function
        Smarch\Motherbox\MotherboxServiceProvider::class, // For Motherbox

#### :pencil: Facades
Next you will need to add the Forms Facades to your config app file. Motherbox has no facade as it is only an artisan command.

*config/app.php*

        /*
        * Third Party Service Providers
        */
        'Form'  => Collective\Html\FormFacade::class,	// required for Motherbox Forms
        'HTML'  => Collective\Html\HtmlFacade::class,	// required for Motherbox Forms

#### :card_index: Publishing Stub and config files

If you wish to use the motherbox config options or customize the stub files for your own needs you will need to publish the files. From your command prompt (wherever you run your artisan commands) enter the following command <kbd>php artisan vendor:publish --provider=Smarch\Motherbox\MotherboxServiceProvider</kbd>. This will create the Motherbox config file and puts the stubs files in <kbd>ROOT\resources\motherbox\stubs</kbd>.

    php artisan vendor:publish --provider=Smarch\Motherbox\MotherboxServiceProvider

#### :trident: Why "Motherbox"?
I've been a DC geek for over 30 years now and all my packages have a DC Universe name. Motherbox as an entire package generator will make sense to use DC folk. :smile: