<?php

namespace Akika\LaravelStanbic\Tests\Actions;

use Akika\LaravelStanbic\Actions\ReadStatusReportsAction;
use Akika\LaravelStanbic\Events\Pain00200103ReportReceived;
use Akika\LaravelStanbic\Tests\HasSampleFiles;
use Akika\LaravelStanbic\Tests\TestCase;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

class ReadStatusReportsActionTest extends TestCase
{
    use HasSampleFiles;

    public string $root;

    protected function setUp(): void
    {
        parent::setUp();

        $disk = config('stanbic.disk');
        $this->root = config('stanbic.input_root');

        Storage::fake($disk);
        Storage::disk($disk)->put("{$this->root}/REPORT_01.xml", $this->ackReport());
        Storage::disk($disk)->put("{$this->root}/REPORT_02.xml", $this->nackReport());
        Storage::disk($disk)->put("{$this->root}/REPORT_03.xml", $this->invalidAccountNoReport());
        Storage::disk($disk)->put("{$this->root}/RANDOM_FILE_01.xml", "<Document xmlns='urn:iso:std:iso:20022:tech:xsd:pain.001.001.03'></Document>");
        Storage::disk($disk)->put("{$this->root}/RANDOM_FILE_02.txt", fake()->sentence());
    }

    public function test_get_only_valid_report_paths(): void
    {
        $action = new ReadStatusReportsAction;

        $expected = [
            "{$this->root}/REPORT_01.xml",
            "{$this->root}/REPORT_02.xml",
            "{$this->root}/REPORT_03.xml",
        ];

        $this->assertEquals($expected, $action->reportPaths->toArray());
    }

    public function test_dispatches_received_reports(): void
    {
        Event::fake();

        $action = new ReadStatusReportsAction;

        $action->handle();

        Event::assertDispatchedTimes(Pain00200103ReportReceived::class, 3);
    }

    public function test_dispatches_received_reports_with_valid_payload(): void
    {
        Event::fake();

        $action = new ReadStatusReportsAction;
        $action->reportPaths = collect(["{$this->root}/REPORT_01.xml"]);

        $action->handle();

        /** @var Pain00200103ReportReceived */
        $event = null;
        Event::assertDispatched(function (Pain00200103ReportReceived $receivedEvent) use (&$event) {
            $event = $receivedEvent;

            return true;
        });

        $this->assertEquals('20027226', $event->report->groupHeader->messageId);
    }

    public function test_can_get_file_from_disk(): void
    {
        $action = new ReadStatusReportsAction;

        $contents = $action->getFileContents("{$this->root}/REPORT_01.xml");

        $this->assertNotNull($contents);
    }

    public function test_throw_exception_if_file_cannot_be_read(): void
    {
        $action = new ReadStatusReportsAction;

        $this->expectException(FileNotFoundException::class);

        $action->getFileContents(fake()->uuid().'.xml');
    }
}
