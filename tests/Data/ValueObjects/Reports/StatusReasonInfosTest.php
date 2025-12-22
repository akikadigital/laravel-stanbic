<?php

namespace Akika\LaravelStanbic\Tests\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Data\ValueObjects\Reports\StatusReasonInfos;
use Akika\LaravelStanbic\Tests\HasSampleFiles;
use Akika\LaravelStanbic\Tests\TestCase;
use Saloon\XmlWrangler\XmlReader;

class StatusReasonInfosTest extends TestCase
{
    use HasSampleFiles;

    public XmlReader $xmlReader;

    protected function setUp(): void
    {
        parent::setUp();

        $xml = $this->invalidAccountNoReport();
        $this->xmlReader = XmlReader::fromString($xml);
    }

    public function test_can_parse_from_xml(): void
    {
        $roots = [
            '//CstmrPmtStsRpt/OrgnlGrpInfAndSts',
            '//CstmrPmtStsRpt/OrgnlPmtInfAndSts/TxInfAndSts',
        ];

        foreach ($roots as $root) {
            $statusReasonInfos = StatusReasonInfos::fromXmlReader($this->xmlReader, $root);

            /** @var \Illuminate\Support\Collection<int, string> */
            $infos = $this->xmlReader->xpathValue("{$root}/StsRsnInf/AddtlInf")->collect();

            $this->assertEquals($infos->all(), $statusReasonInfos->additionalInfos->all());
        }
    }
}
