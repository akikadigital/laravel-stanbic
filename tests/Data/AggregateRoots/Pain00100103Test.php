<?php

namespace Akika\LaravelStanbic\Tests\Data\AggregateRoots;

use Akika\LaravelStanbic\Data\AggregateRoots\Pain00100103;
use Akika\LaravelStanbic\Data\ValueObjects\GroupHeader;
use Akika\LaravelStanbic\Data\ValueObjects\PaymentInfo;
use Akika\LaravelStanbic\Enums\Currency;
use Akika\LaravelStanbic\Enums\InstructionPriority;
use Akika\LaravelStanbic\Tests\TestCase;

class Pain00100103Test extends TestCase
{
    public function test_can_build_xml(): void
    {
        $groupHeader = GroupHeader::make()
            ->setMessageId(fake()->uuid())
            ->setCreationDate(now())
            ->setNumberOfTransactions(fake()->randomNumber())
            ->setControlSum(fake()->randomFloat())
            ->setInitiatingParty(fake()->company(), fake()->sentence());

        $paymentInfo = PaymentInfo::make()
            ->setPaymentInfoId(fake()->uuid())
            ->setBatchBooking(true)
            ->setPaymentTypeInfo(InstructionPriority::Norm, 63)
            ->setRequestedExecutionDate(now())
            ->setDebtor(fake()->company())
            ->setDebtorAccount(fake()->uuid(), Currency::Rand);

        $payment = Pain00100103::make()
            ->setGroupHeader($groupHeader)
            ->setPaymentInfo($paymentInfo);

        dd($payment->build());

        $this->markTestIncomplete();
    }
}
