<?php

namespace Akika\LaravelStanbic\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Akika\LaravelStanbic\LaravelStanbic
 */
class LaravelStanbic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Akika\LaravelStanbic\LaravelStanbic::class;
    }
}
