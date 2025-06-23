<?php

namespace Akika\LaravelStanbic\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ReadStatusReportsAction
{
    /** @var Collection<int, string> */
    public Collection $reportPaths;

    public string $disk;

    public function __construct()
    {
        /** @var string */
        $disk = config('stanbic.disk');
        $this->disk = $disk;

        $this->reportPaths = $this->getValidReportPaths();
    }

    public function handle(): void {}

    /** @return Collection<int, string> */
    public function getValidReportPaths(): Collection
    {
        /** @var array<int, string> */
        $allFiles = Storage::disk($this->disk)->allFiles();

        /** @var Collection<int, string> */
        $validReportPaths = collect($allFiles)
            ->filter(fn (string $path) => str_contains(strtolower($path), '.xml'))
            ->filter(function (string $path) {
                $contents = Storage::disk($this->disk)->get($path);
                if (! $contents) {
                    return false;
                }

                return str_contains($contents, 'pain.002.001.03');
            })
            ->flatten();

        return $validReportPaths;
    }
}
