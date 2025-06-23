<?php

namespace Akika\LaravelStanbic\Tests\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Data\ValueObjects\Reports\Pain00200103;
use Akika\LaravelStanbic\Enums\GroupStatusType;
use Akika\LaravelStanbic\Tests\HasSampleFiles;
use Akika\LaravelStanbic\Tests\TestCase;

class Pain00200103Test extends TestCase
{
    use HasSampleFiles;

    public function test_can_parse_xml(): void
    {
        $report = Pain00200103::fromXml($this->ackReport());

        $expectedOrgMsgId = 'DPTS001';
        $orgMsgId = $report->xmlReader->value('CstmrPmtStsRpt.OrgnlGrpInfAndSts.OrgnlMsgId')->sole();
        $this->assertEquals($expectedOrgMsgId, $orgMsgId);
    }

    public function test_can_get_group_status(): void
    {
        $report = Pain00200103::fromXml($this->nackReport());

        $this->assertEquals(GroupStatusType::Rjct, $report->getGroupStatus());
    }

    public function test_can_get_original_payment_info(): void
    {
        $report = Pain00200103::fromXml($this->invalidAccountNoReport());

        $this->assertEquals('1000075215', $report->originalPaymentInfoAndStatus->originalPaymentInfoId);
    }
}
