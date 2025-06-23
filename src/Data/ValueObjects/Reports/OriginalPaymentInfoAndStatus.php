<?php

namespace Akika\LaravelStanbic\Data\ValueObjects\Reports;

use Saloon\XmlWrangler\XmlReader;

class OriginalPaymentInfoAndStatus
{
    public function __construct(
        public string $originalPaymentInfoId,
        public StatusReasonInfos $statusReasonInfos,
        public TransactionInfoAndStatus $transactionInfoAndStatus,
    ) {}

    public static function fromXmlReader(XmlReader $reader): ?self
    {
        $root = 'CstmrPmtStsRpt.OrgnlPmtInfAndSts';

        if (! $reader->value($root)->first()) {
            return null;
        }

        /** @var string */
        $originalPaymentInfoId = $reader->value("{$root}.OrgnlPmtInfId")->sole();

        /** @var \Illuminate\Support\Collection<int, string> */
        $infos = $reader->value("{$root}.StsRsnInf.AddtlInf")->collect();
        $additionalStatusInfos = new StatusReasonInfos($infos);

        $transactionInfoAndStatus = TransactionInfoAndStatus::fromXmlReader($reader);

        return new self($originalPaymentInfoId, $additionalStatusInfos, $transactionInfoAndStatus);
    }
}
