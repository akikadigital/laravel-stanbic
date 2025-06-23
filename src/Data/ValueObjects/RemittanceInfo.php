<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

class RemittanceInfo extends XmlValueObject
{
    public function __construct(public string $unstructuredInfo) {}

    public function getName(): string
    {
        return 'RmtInf';
    }

    /** @return array<string, array<string, string>> */
    public function getElement(): array
    {
        return [$this->getName() => [
            'Ustrd' => $this->unstructuredInfo,
        ]];
    }
}
