<?php

namespace Akika\LaravelStanbic\Actions;

use Akika\LaravelStanbic\Data\ValueObjects\Reports\Pain00200103;
use Akika\LaravelStanbic\Events\Pain00200103ReportReceived;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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

    public function handle(): void
    {
        $this->reportPaths->each(function (string $path) {
            $contents = $this->getFileContents($path);

            $report = Pain00200103::fromXml($contents);

            Pain00200103ReportReceived::dispatch($report);
        });
    }

    /** @return Collection<int, string> */
    public function getValidReportPaths(): Collection
    {
        /** @var array<int, string> */
        $allFiles = Storage::disk($this->disk)->allFiles();

        /** @var Collection<int, string> */
        $validReportPaths = collect($allFiles)
            ->filter(fn (string $path) => str_contains(strtolower($path), '.xml'))
            ->filter(function (string $path) {
                $contents = $this->getFileContents($path);

                return str_contains($contents, 'pain.002.001.03');
            })
            ->flatten();

        return $validReportPaths;
    }

    public function getFileContents(string $path): string
    {
        $contents = Storage::disk($this->disk)->get($path);
        if (! $contents) {
            throw new FileNotFoundException;
        }

        return $contents;
    }
}
