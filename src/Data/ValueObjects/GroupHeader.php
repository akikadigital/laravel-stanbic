<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use ValueError;

class GroupHeader extends XmlValueObject
{
    public ?MessageId $messageId = null;

    public ?CreationDateTime $creationDateTime = null;

    public ?NumberOfTransactions $numberOfTransactions = null;

    public ?ControlSum $controlSum = null;

    public ?InitiatingParty $initiatingParty = null;

    public function __construct() {}

    public function getName(): string
    {
        return 'GrpHdr';
    }

    /** @return array<string, array<string, mixed>> */
    public function getElement(): array
    {
        if (
            ! $this->messageId ||
            ! $this->creationDateTime ||
            ! $this->numberOfTransactions ||
            ! $this->controlSum ||
            ! $this->initiatingParty
        ) {
            throw new ValueError;
        }

        return [
            $this->getName() => [
                ...$this->messageId->getElement(),
                ...$this->creationDateTime->getElement(),
                ...$this->numberOfTransactions->getElement(),
                ...$this->controlSum->getElement(),
                ...$this->initiatingParty->getElement(),
            ],
        ];
    }
}
