<?php

namespace Akika\LaravelStanbic\Tests\Commands;

use Akika\LaravelStanbic\Events\Pain00200103ReportReceived;
use Akika\LaravelStanbic\Tests\HasSampleFiles;
use Akika\LaravelStanbic\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

class ReadStatusReportsCommmandTest extends TestCase
{
    use HasSampleFiles;

    protected function setUp(): void
    {
        parent::setUp();

        $disk = config('stanbic.disk');

        Storage::fake($disk);
        Storage::disk($disk)->put('REPORT_01.xml', $this->ackReport());
        Storage::disk($disk)->put('REPORT_02.xml', $this->nackReport());
        Storage::disk($disk)->put('REPORT_03.xml', $this->invalidAccountNoReport());
    }

    public function test_dispatches_received_reports(): void
    {
        Event::fake();

        $this->artisan('stanbic:read')
            ->expectsOutputToContain(config('stanbic.disk'))
            ->assertSuccessful();

        Event::assertDispatchedTimes(Pain00200103ReportReceived::class, 3);
    }
}
