<?php

namespace Akika\LaravelStanbic\Tests\Data\AggregateRoots;

use Akika\LaravelStanbic\Data\AggregateRoots\Pain00100103;
use Akika\LaravelStanbic\Data\ValueObjects\CustomerCreditTransferInitiation;
use Akika\LaravelStanbic\Tests\TestCase;

class Pain00100103Test extends TestCase
{
    public function test_can_build_xml(): void
    {
        $payment = new Pain00100103(new CustomerCreditTransferInitiation([]));

        $this->markTestIncomplete();
    }
}
