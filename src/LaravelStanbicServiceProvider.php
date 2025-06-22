<?php

namespace Akika\LaravelStanbic;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Akika\LaravelStanbic\Commands\LaravelStanbicCommand;

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
            ->hasViews()
            ->hasCommand(LaravelStanbicCommand::class);
    }
}
