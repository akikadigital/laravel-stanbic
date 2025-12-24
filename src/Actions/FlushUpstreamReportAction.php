<?php

namespace Akika\LaravelStanbic\Actions;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class FlushUpstreamReportAction
{
    public string $disk;

    public bool $backupEnabled;

    public string $backupDisk;

    public string $backupRoot;

    public function __construct()
    {
        /** @var string */
        $disk = config('stanbic.disk');
        $this->disk = $disk;

        /** @var bool */
        $backupEnabled = config('stanbic.backup.enabled');
        $this->backupEnabled = $backupEnabled;

        /** @var string */
        $backupDisk = config('stanbic.backup.disk');
        $this->backupDisk = $backupDisk;

        /** @var string */
        $backupRoot = config('stanbic.backup.root');
        $this->backupRoot = $backupRoot;
    }

    public function handle(string $path): void
    {
        $contents = Storage::disk($this->disk)->get($path);
        if (! $contents) {
            throw new FileNotFoundException;
        }

        Storage::disk($this->disk)->delete($path);

        if (! $this->backupEnabled) {
            return;
        }

        $backupPath = "$this->backupRoot/".basename($path);
        Storage::disk($this->backupDisk)->put($backupPath, $contents);
    }
}
