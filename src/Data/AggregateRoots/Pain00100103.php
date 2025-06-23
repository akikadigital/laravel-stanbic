<?php

namespace Akika\LaravelStanbic\Data\AggregateRoots;

use Akika\LaravelStanbic\Data\ValueObjects\CustomerCreditTransferInitiation;
use Akika\LaravelStanbic\Data\ValueObjects\Document;
use Saloon\XmlWrangler\XmlWriter;
use ValueError;

class Pain00100103
{
    private string $xmlns = 'urn:iso:std:iso:20022:tech:xsd:pain.001.001.03';

    private string $xmlnsXsi = 'http://www.w3.org/2001/XMLSchema-instance';

    public ?CustomerCreditTransferInitiation $customerCreditTransferInitiation = null;

    public function build(): string
    {
        if (! $this->customerCreditTransferInitiation) {
            throw new ValueError;
        }

        $document = new Document($this->xmlns, $this->xmlnsXsi);

        $xml = new XmlWriter;

        return $xml->write(
            $document->getElement(),
            $this->customerCreditTransferInitiation->getElement(),
        );
    }
}
