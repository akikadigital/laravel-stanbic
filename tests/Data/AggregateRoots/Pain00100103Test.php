<?php

namespace Akika\LaravelStanbic\Tests\Data\AggregateRoots;

use Akika\LaravelStanbic\Data\AggregateRoots\Pain00100103;
use Akika\LaravelStanbic\Data\ValueObjects\CreditTransferTransactionInfo;
use Akika\LaravelStanbic\Data\ValueObjects\GroupHeader;
use Akika\LaravelStanbic\Data\ValueObjects\PaymentInfo;
use Akika\LaravelStanbic\Data\ValueObjects\PostalAddress;
use Akika\LaravelStanbic\Enums\ChargeBearerType;
use Akika\LaravelStanbic\Enums\CountryCode;
use Akika\LaravelStanbic\Enums\Currency;
use Akika\LaravelStanbic\Enums\InstructionPriority;
use Akika\LaravelStanbic\Tests\TestCase;

class Pain00100103Test extends TestCase
{
    public function test_can_build_xml_for_local_domestic_payments(): void
    {
        $groupHeader = GroupHeader::make()
            ->setMessageId(fake()->uuid())
            ->setCreationDate(now())
            ->setNumberOfTransactions(fake()->randomNumber())
            ->setControlSum(fake()->randomFloat())
            ->setInitiatingParty(fake()->company(), fake()->sentence());

        $transactionInfo = CreditTransferTransactionInfo::make()
            ->setPaymentId(fake()->uuid(), fake()->uuid())
            ->setAmount(fake()->randomNumber(), fake()->randomElement(Currency::cases()))
            ->setChargeBearer(ChargeBearerType::Debt)
            ->setCreditorAgent(fake()->randomNumber(), 'STANDARD BANK SA', new PostalAddress(countryCode: CountryCode::SouthAfrica))
            ->setCreditor(fake()->name(), new PostalAddress(streetName: fake()->streetName(), countryCode: CountryCode::SouthAfrica))
            ->setCreditorAccount(fake()->randomNumber())
            ->setRemittanceInfo('Domestic Payment');

        $paymentInfo = PaymentInfo::make()
            ->setPaymentInfoId(fake()->uuid())
            ->setBatchBooking(true)
            ->setPaymentTypeInfo(InstructionPriority::Norm, 63)
            ->setRequestedExecutionDate(now())
            ->setDebtor(fake()->company())
            ->setDebtorAccount(fake()->uuid(), fake()->randomElement(Currency::cases()))
            ->setCreditTransferTransactionInfo($transactionInfo);

        $payment = Pain00100103::make()
            ->setGroupHeader($groupHeader)
            ->setPaymentInfo($paymentInfo);

        // echo $payment->build();
        // dd($payment->build());

        $this->markTestIncomplete();
    }
}
