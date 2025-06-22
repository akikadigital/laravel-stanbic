<?php

namespace Akika\LaravelStanbic\Data\AggregateRoots;

use Akika\LaravelStanbic\Data\ValueObjects\CustomerCreditTransferInitiation;
use Akika\LaravelStanbic\Data\ValueObjects\Document;
use Akika\LaravelStanbic\Data\ValueObjects\GroupHeader;
use Saloon\XmlWrangler\XmlWriter;

class Pain00100103
{
    private string $xmlns = 'urn:iso:std:iso:20022:tech:xsd:pain.001.001.03';

    private string $xmlnsXsi = 'http://www.w3.org/2001/XMLSchema-instance';

    public ?CustomerCreditTransferInitiation $customerCreditTransferInitiation;

    public ?GroupHeader $groupHeader;

    public function __construct() {}

    public function build(): string
    {
        $xml = new XmlWriter;

        $document = new Document($this->xmlns, $this->xmlnsXsi);
        $customerCreditTransferInitiation = new CustomerCreditTransferInitiation([]);

        return $xml->write(
            $document->getElement(),
            $customerCreditTransferInitiation->children,
        );
    }
}
