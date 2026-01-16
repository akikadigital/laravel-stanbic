<?php

namespace Akika\LaravelStanbic\Tests\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Data\ValueObjects\Reports\Pain00200103;
use Akika\LaravelStanbic\Tests\HasSampleFiles;
use Akika\LaravelStanbic\Tests\TestCase;

class Pain00200103Test extends TestCase
{
    use HasSampleFiles;

    public function test_can_parse_xml(): void
    {
        $report = new Pain00200103($this->ackReport());

        $expectedOrgMsgId = 'DPTS001';
        $this->assertEquals($expectedOrgMsgId, $report->originalGroupInfoAndStatus->originalMessageId);
    }

    public function test_can_get_original_payment_info(): void
    {
        $report = new Pain00200103($this->invalidAccountNoReport());

        $record = $report->originalPaymentInfoAndStatuses->first();
        $this->assertEquals('1000075215', $record->originalPaymentInfoId);
    }

    public function test_can_get_all_nested_errors(): void
    {
        $report = new Pain00200103($this->invalidAccountNoReport());

        $expected = [
            'Status : Processing failed',
            'Status : Processing Failed',
            'Status : Failed - Invalid Account Number',
            'Error Code : !%',
            'Error Description : ACCOUNT NO INVALID',
        ];

        $this->assertEquals($expected, $report->getAllStatusReasons()->toArray());
    }
}
