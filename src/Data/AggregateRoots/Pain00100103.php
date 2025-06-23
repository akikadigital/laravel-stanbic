<?php

namespace Akika\LaravelStanbic\Data\AggregateRoots;

use Akika\LaravelStanbic\Data\ValueObjects\CustomerCreditTransferInitiation;
use Akika\LaravelStanbic\Data\ValueObjects\Document;
use Akika\LaravelStanbic\Data\ValueObjects\GroupHeader;
use Saloon\XmlWrangler\XmlWriter;
use ValueError;

class Pain00100103
{
    private string $xmlns = 'urn:iso:std:iso:20022:tech:xsd:pain.001.001.03';

    private string $xmlnsXsi = 'http://www.w3.org/2001/XMLSchema-instance';

    public ?CustomerCreditTransferInitiation $customerCreditTransferInitiation = null;

    public ?GroupHeader $groupHeader;

    public function build(): string
    {
        if (! $this->groupHeader) {
            throw new ValueError;
        }

        $this->customerCreditTransferInitiation = new CustomerCreditTransferInitiation($this->groupHeader);

        $document = new Document($this->xmlns, $this->xmlnsXsi);

        return (new XmlWriter)->write(
            $document->getElement(),
            $this->customerCreditTransferInitiation->getElement(),
        );
    }

    public static function make(): self
    {
        return new Pain00100103;
    }

    public function setGroupHeader(GroupHeader $groupHeader): self
    {
        $this->groupHeader = $groupHeader;

        return $this;
    }
}
