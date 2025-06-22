<?php

namespace Akika\LaravelStanbic\Commands;

use Illuminate\Console\Command;

class LaravelStanbicCommand extends Command
{
    public $signature = 'laravel-stanbic';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
