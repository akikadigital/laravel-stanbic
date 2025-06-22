<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Saloon\XmlWrangler\Data\RootElement;

class Document
{
    public function __construct(public string $xmlns, public string $xmlnsXsi) {}

    public function getName(): string
    {
        return 'Document';
    }

    /** @return array<string, string> */
    public function getAttributes(): array
    {
        return [
            'xmlns' => $this->xmlns,
            'xmlns:xsi' => $this->xmlnsXsi,
        ];
    }

    public function getElement(): RootElement
    {
        return new RootElement(name: $this->getName(), attributes: $this->getAttributes());
    }
}
