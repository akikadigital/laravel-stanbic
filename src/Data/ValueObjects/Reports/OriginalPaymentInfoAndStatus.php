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

        $additionalStatusInfos = StatusReasonInfos::fromXmlReader($reader, $root);

        $transactionInfoAndStatus = TransactionInfoAndStatus::fromXmlReader($reader);

        return new self($originalPaymentInfoId, $additionalStatusInfos, $transactionInfoAndStatus);
    }
}
