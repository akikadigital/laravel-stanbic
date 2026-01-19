<?php

namespace Akika\LaravelStanbic\Tests\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Data\ValueObjects\Reports\GroupHeader;
use Akika\LaravelStanbic\Tests\HasSampleFiles;
use Akika\LaravelStanbic\Tests\TestCase;
use Carbon\Carbon;
use Saloon\XmlWrangler\XmlReader;

class GroupHeaderTest extends TestCase
{
    use HasSampleFiles;

    public XmlReader $xmlReader;

    protected function setUp(): void
    {
        parent::setUp();

        $xml = $this->acspReport();
        $this->xmlReader = XmlReader::fromString($xml);
    }

    public function test_can_parse_from_xml(): void
    {
        $groupHeader = GroupHeader::fromXmlReader($this->xmlReader);

        $root = '//CstmrPmtStsRpt/GrpHdr';
        $messageId = $this->xmlReader->xpathValue("{$root}/MsgId")->sole();
        $creationDateTime = $this->xmlReader->xpathValue("{$root}/CreDtTm")->sole();
        $initiatingPartyName = $this->xmlReader->xpathValue("{$root}/InitgPty/Nm")->sole();
        $initiatingPartyBicOrBei = $this->xmlReader->xpathValue("{$root}/InitgPty/Id/OrgId/BICOrBEI")->sole();

        $this->assertEquals($messageId, $groupHeader->messageId);
        $this->assertTrue(Carbon::parse($creationDateTime)->eq($groupHeader->creationDateTime));
        $this->assertEquals($initiatingPartyName, $groupHeader->initiatingPartyName);
        $this->assertEquals($initiatingPartyBicOrBei, $groupHeader->initiatingPartyBicOrBei);
    }
}
