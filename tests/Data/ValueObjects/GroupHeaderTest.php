<?php

namespace Akika\LaravelStanbic\Tests\Data\ValueObjects;

use Akika\LaravelStanbic\Data\ValueObjects\ControlSum;
use Akika\LaravelStanbic\Data\ValueObjects\CreationDateTime;
use Akika\LaravelStanbic\Data\ValueObjects\GroupHeader;
use Akika\LaravelStanbic\Data\ValueObjects\InitiatingParty;
use Akika\LaravelStanbic\Data\ValueObjects\MessageId;
use Akika\LaravelStanbic\Data\ValueObjects\NumberOfTransactions;
use Akika\LaravelStanbic\Tests\TestCase;

class GroupHeaderTest extends TestCase
{
    public function test_element_has_correct_properties(): void
    {
        $header = new GroupHeader;
        $header->messageId = new MessageId(fake()->uuid());
        $header->creationDateTime = new CreationDateTime(now());
        $header->numberOfTransactions = new NumberOfTransactions(1);
        $header->controlSum = new ControlSum(100);
        $header->initiatingParty = new InitiatingParty(fake()->word(), fake()->uuid());

        $this->assertEquals('GrpHdr', $header->getName());

        $expected = ['GrpHdr' => [
            ...$header->messageId->getElement(),
            ...$header->creationDateTime->getElement(),
            ...$header->numberOfTransactions->getElement(),
            ...$header->controlSum->getElement(),
            ...$header->initiatingParty->getElement(),
        ]];

        $this->assertEquals($expected, $header->getElement());
    }
}
