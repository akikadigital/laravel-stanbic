<?php

namespace Akika\LaravelStanbic\Tests\Data\ValueObjects\Reports;

use Akika\LaravelStanbic\Data\ValueObjects\Reports\Pain00200103;
use Akika\LaravelStanbic\Enums\GroupStatusType;
use Akika\LaravelStanbic\Tests\TestCase;

class Pain00200103Test extends TestCase
{
    public function test_can_parse_xml(): void
    {
        $report = Pain00200103::fromXml(self::ACK);

        $expectedOrgMsgId = 'DPTS001';
        $orgMsgId = $report->xmlReader->value('CstmrPmtStsRpt.OrgnlGrpInfAndSts.OrgnlMsgId')->sole();
        $this->assertEquals($expectedOrgMsgId, $orgMsgId);
    }

    public function test_can_get_group_status(): void
    {
        $report = Pain00200103::fromXml(self::ACK);

        $this->assertEquals(GroupStatusType::Rcvd, $report->getGroupStatus());
    }

    public const ACK = "<?xml version='1.0' encoding='UTF-8' standalone='yes' ?>
<Document xmlns='urn:iso:std:iso:20022:tech:xsd:pain.002.001.03' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
  <CstmrPmtStsRpt>
    <GrpHdr>
      <MsgId>20027226</MsgId>
      <CreDtTm>2018-01-26T09:59:45</CreDtTm>
      <InitgPty>
        <Nm>Standard Bank SA</Nm>
        <Id>
          <OrgId>
            <BICOrBEI>SBZAZAJJXXX</BICOrBEI>

          </OrgId>
        </Id>
      </InitgPty>
    </GrpHdr>

    <OrgnlGrpInfAndSts>
      <OrgnlMsgId>DPTS001</OrgnlMsgId>

      <OrgnlMsgNmId>PAIN.001.001.03</OrgnlMsgNmId>
      <OrgnlCreDtTm>2018-01-26T18:40:59</OrgnlCreDtTm>
      <OrgnlNbOfTxs>1</OrgnlNbOfTxs>

      <OrgnlCtrlSum>10.0</OrgnlCtrlSum>
      <GrpSts>RCVD</GrpSts>
    </OrgnlGrpInfAndSts>

  </CstmrPmtStsRpt>
</Document>
    ";

    public const NACK = "<?xml version='1.0' encoding='UTF-8' standalone='yes' ?>
<Document xmlns='urn:iso:std:iso:20022:tech:xsd:pain.002.001.03' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
  <CstmrPmtStsRpt>
    <GrpHdr>
      <MsgId>20027490</MsgId>
      <CreDtTm>2018-01-26T11:00:04</CreDtTm>
      <InitgPty>
        <Nm>Standard Bank SA</Nm>
        <Id>
          <OrgId>
            <BICOrBEI>SBZAZAJJXXX</BICOrBEI>

          </OrgId>
        </Id>
      </InitgPty>
    </GrpHdr>

    <OrgnlGrpInfAndSts>
      <OrgnlMsgId>DPTS001</OrgnlMsgId>

      <OrgnlMsgNmId>PAIN.001.001.03</OrgnlMsgNmId>
      <OrgnlCreDtTm>2018-01-26T18:40:59</OrgnlCreDtTm>
      <OrgnlNbOfTxs>1</OrgnlNbOfTxs>

      <OrgnlCtrlSum>10.0</OrgnlCtrlSum>
      <GrpSts>RJCT</GrpSts>
      <StsRsnInf>
        <Rsn>
          <Cd>NARR</Cd>
        </Rsn>
        <AddtlInf>Duplicate File</AddtlInf>
      </StsRsnInf>
    </OrgnlGrpInfAndSts>
  </CstmrPmtStsRpt>
</Document>
";
}
