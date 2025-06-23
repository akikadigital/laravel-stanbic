<?php

namespace Akika\LaravelStanbic\Tests\Actions;

use Akika\LaravelStanbic\Actions\ReadStatusReportsAction;
use Akika\LaravelStanbic\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class ReadStatusReportsActionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $disk = config('stanbic.disk');

        Storage::fake($disk);
        Storage::disk($disk)->put('REPORT_01.xml', "<Document xmlns='urn:iso:std:iso:20022:tech:xsd:pain.002.001.03'></Document>");
        Storage::disk($disk)->put('REPORT_01.xml', "<Document xmlns='urn:iso:std:iso:20022:tech:xsd:pain.002.001.03'></Document>");
        Storage::disk($disk)->put('REPORT_02.xml', "<Document xmlns='urn:iso:std:iso:20022:tech:xsd:pain.002.001.03'></Document>");
        Storage::disk($disk)->put('REPORT_03.xml', "<Document xmlns='urn:iso:std:iso:20022:tech:xsd:pain.002.001.03'></Document>");
        Storage::disk($disk)->put('RANDOM_FILE_01.xml', "<Document xmlns='urn:iso:std:iso:20022:tech:xsd:pain.001.001.03'></Document>");
        Storage::disk($disk)->put('RANDOM_FILE_02.txt', fake()->sentence());
    }

    public function test_get_only_valid_report_paths(): void
    {
        $action = new ReadStatusReportsAction;

        $expected = [
            'REPORT_01.xml',
            'REPORT_02.xml',
            'REPORT_03.xml',
        ];

        $this->assertEquals($expected, $action->reportPaths->toArray());
    }
}
