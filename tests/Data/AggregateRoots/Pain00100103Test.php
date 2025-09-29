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
use Akika\LaravelStanbic\Enums\PaymentMethod;
use Akika\LaravelStanbic\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class Pain00100103Test extends TestCase
{
    public function test_can_build_xml_for_local_domestic_payments(): void
    {
        /** @var string */
        $disk = config('stanbic.disk');
        Storage::fake($disk);

        $messageId = fake()->uuid();
        $companyName = fake()->company();
        $companyAcNo = fake()->randomNumber(8, true);
        $amount = fake()->numberBetween(10_000, 99_999);

        $paymentId = fake()->uuid();
        $instructionId = fake()->uuid();
        $bankCode = '190101';
        $bank = 'Stanbic Bank Ghana Ltd';
        $beneficiaryName = fake()->name();
        $beneficiaryAcNo = fake()->randomNumber(8, true);
        $paymentDescription = fake()->words(3, true);

        $paymentInfoId = fake()->uuid();
        $companyAcNo = fake()->randomNumber(8, true);

        // 1. Create group header
        $groupHeader = GroupHeader::make()
            ->setMessageId($messageId)
            ->setCreationDate(now())
            ->setNumberOfTransactions(1)
            ->setControlSum($amount)
            ->setInitiatingParty($companyName, $companyAcNo);

        // 2. Create transaction info
        $transactionInfo = CreditTransferTransactionInfo::make()
            ->setPaymentId($paymentId, $instructionId)
            ->setAmount($amount, Currency::Cedi)
            ->setCreditorAgent($bankCode, $bank, new PostalAddress(countryCode: CountryCode::Ghana))
            ->setCreditor($beneficiaryName, new PostalAddress(
                fake()->streetName(),
                fake()->buildingNumber(),
                fake()->postcode(),
                fake()->city(),
                CountryCode::Ghana,
            ))
            ->setCreditorAccount($beneficiaryAcNo)
            ->setRemittanceInfo($paymentDescription);

        // 3. Create payment info
        $paymentInfo = PaymentInfo::make()
            ->setPaymentInfoId($paymentInfoId)
            ->setPaymentMethod(PaymentMethod::CreditTransfer)
            ->setBatchBooking(true)
            ->setPaymentTypeInfo(InstructionPriority::Norm)
            ->setRequestedExecutionDate(now())
            ->setDebtor($companyName, new PostalAddress(countryCode: CountryCode::Ghana))
            ->setDebtorAccount($companyAcNo, Currency::Cedi)
            ->setDebtorAgent($bankCode)
            ->setChargeBearer(ChargeBearerType::Debt)
            ->setCreditTransferTransactionInfo($transactionInfo);

        // 4. Generate and store XML
        $path = Pain00100103::make()
            ->setGroupHeader($groupHeader)
            ->setPaymentInfo($paymentInfo)
            ->store(); // Returns the stored file path

        Storage::disk($disk)->assertExists($path);
    }
}
