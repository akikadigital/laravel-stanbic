<?php

namespace Akika\LaravelStanbic\Commands;

use Akika\LaravelStanbic\Actions\ReadStatusReportsAction;
use Illuminate\Console\Command;

class ReadStatusReportsCommand extends Command
{
    public $signature = 'stanbic:read';

    public $description = "Read Stanbic banks's response status reports from the configured disk.";

    public function handle(): int
    {
        /** @var string */
        $disk = config('stanbic.disk');

        $this->comment("Reading status reports from {$disk}");

        (new ReadStatusReportsAction)->handle();

        return self::SUCCESS;
    }
}
