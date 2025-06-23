<?php

namespace Akika\LaravelStanbic\Tests\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Data\ValueObjects\Reports\Pain00200103;
use Akika\LaravelStanbic\Enums\GroupStatusType;
use Akika\LaravelStanbic\Tests\TestCase;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class Pain00200103Test extends TestCase
{
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

    private function ackReport(): string
    {
        return $this->getFileContents('ack.xml');
    }

    private function nackReport(): string
    {
        return $this->getFileContents('nack.xml');
    }

    private function invalidAccountNoReport(): string
    {
        return $this->getFileContents('invalid_account_no.xml');
    }

    public function getFileContents(string $path): string
    {
        if (! $contents = file_get_contents(__DIR__."/samples/{$path}")) {
            throw new FileNotFoundException;
        }

        return $contents;
    }
}
