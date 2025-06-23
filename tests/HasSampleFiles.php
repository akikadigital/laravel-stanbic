<?php

namespace Akika\LaravelStanbic\Tests;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

trait HasSampleFiles
{
    public function ackReport(): string
    {
        return $this->getFileContents('ack.xml');
    }

    public function nackReport(): string
    {
        return $this->getFileContents('nack.xml');
    }

    public function invalidAccountNoReport(): string
    {
        return $this->getFileContents('invalid_account_no.xml');
    }

    public function basePath(string $path = ''): string
    {
        $orchestraPath = '/vendor/orchestra/testbench-core/laravel';
        $orchestraPathWin = '\vendor\orchestra\testbench-core\laravel';

        $root = base_path();
        $root = str_replace($orchestraPath, '', $root);
        $root = str_replace($orchestraPathWin, '', $root);

        return $root."/{$path}";
    }

    public function getFileContents(string $path): string
    {
        $fullPath = $this->basePath("tests/samples/{$path}");

        if (! $contents = file_get_contents($fullPath)) {
            throw new FileNotFoundException;
        }

        return $contents;
    }
}
