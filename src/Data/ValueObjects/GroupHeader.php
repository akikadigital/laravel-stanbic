<?php

namespace Akika\LaravelStanbic\Data\ValueObjects;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use ValueError;

class GroupHeader extends XmlValueObject
{
    public ?MessageId $messageId = null;

    public ?CreationDateTime $creationDateTime = null;

    public ?NumberOfTransactions $numberOfTransactions = null;

    public ?ControlSum $controlSum = null;

    public ?InitiatingParty $initiatingParty = null;

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

    public static function make(): self
    {
        return new self;
    }

    public function setMessageId(string $messageId): self
    {
        $this->messageId = new MessageId($messageId);

        return $this;
    }

    public function setCreationDate(Carbon|CarbonImmutable $dateTime): self
    {
        $this->creationDateTime = new CreationDateTime($dateTime);

        return $this;
    }

    public function setNumberOfTransactions(int $count): self
    {
        $this->numberOfTransactions = new NumberOfTransactions($count);

        return $this;
    }

    public function setControlSum(float $sum): self
    {
        $this->controlSum = new ControlSum($sum);

        return $this;
    }

    /**
     * @param  string  $name  Name of the initiating party
     * @param  string  $id  Identification of the initiating party
     */
    public function setInitiatingParty(?string $name, ?string $id): self
    {
        $this->initiatingParty = new InitiatingParty($name, $id);

        return $this;
    }
}
