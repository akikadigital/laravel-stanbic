<?php

namespace Akika\LaravelStanbic;

use Akika\LaravelStanbic\Commands\LaravelStanbicCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelStanbicServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-stanbic')
            ->hasConfigFile()
            ->hasCommand(LaravelStanbicCommand::class);
    }
}
