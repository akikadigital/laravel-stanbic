<?php

namespace Akika\LaravelStanbic\Tests\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Data\ValueObjects\Reports\OriginalGroupInfoAndStatus;
use Akika\LaravelStanbic\Enums\GroupStatusType;
use Akika\LaravelStanbic\Tests\HasSampleFiles;
use Akika\LaravelStanbic\Tests\TestCase;
use Carbon\Carbon;

class OriginalGroupInfoAndStatusTest extends TestCase
{
    use HasSampleFiles;

    public function test_can_parse_from_xml(): void
    {
        $root = '//CstmrPmtStsRpt/OrgnlGrpInfAndSts';

        foreach ($this->getAllXmlReaders() as $xmlReader) {

            $originalGroupInfoAndStatus = OriginalGroupInfoAndStatus::fromXmlReader($xmlReader);

            $originalMessageId = $xmlReader->xpathValue("{$root}/OrgnlMsgId")->sole();
            $originalMessageNameId = $xmlReader->xpathValue("{$root}/OrgnlMsgNmId")->sole();
            $originalCreditDateTime = $xmlReader->xpathValue("{$root}/OrgnlCreDtTm")->sole();
            $originalNumberOfTransactions = $xmlReader->xpathValue("{$root}/OrgnlNbOfTxs")->sole();
            $originalControlSum = $xmlReader->xpathValue("{$root}/OrgnlCtrlSum")->sole();
            $groupStatus = $xmlReader->xpathValue("{$root}/GrpSts")->sole();
            /** @var \Illuminate\Support\Collection<int, string> */
            $infos = $xmlReader->xpathValue("{$root}/StsRsnInf/AddtlInf")->collect();

            $this->assertEquals($originalMessageId, $originalGroupInfoAndStatus->originalMessageId);
            $this->assertEquals($originalMessageNameId, $originalGroupInfoAndStatus->originalMessageNameId);
            $this->assertTrue(Carbon::parse($originalCreditDateTime)->eq($originalGroupInfoAndStatus->originalCreditDateTime));
            $this->assertEquals($originalNumberOfTransactions, $originalGroupInfoAndStatus->originalNumberOfTransactions);
            $this->assertEquals($originalControlSum, $originalGroupInfoAndStatus->originalControlSum);
            $this->assertEquals(GroupStatusType::from($groupStatus), $originalGroupInfoAndStatus->groupStatus);
            $this->assertEquals($infos->all(), $originalGroupInfoAndStatus->statusReasonInfos->infos->all());
        }
    }
}
