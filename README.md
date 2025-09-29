# Laravel Stanbic

[![PHPStan](https://github.com/akikadigital/laravel-stanbic/actions/workflows/phpstan.yml/badge.svg)](https://github.com/akikadigital/laravel-stanbic/actions/workflows/phpstan.yml)
[![run-tests](https://github.com/akikadigital/laravel-stanbic/actions/workflows/run-tests.yml/badge.svg)](https://github.com/akikadigital/laravel-stanbic/actions/workflows/run-tests.yml)

An unofficial package for Stanbic bank; following the ISO20022 standard.

## Prerequisites

Configure `sftp` first before you proceed: https://laravel.com/docs/12.x/filesystem#sftp-driver-configuration

Add the SFTP configs in the `config/filesystem.php`. Below is the recommended set of configs. Ensure you set the corresponding env values in your `.env` as well:

```php
'sftp' => [
    'driver' => 'sftp',
    'host' => env('SFTP_HOST'),
    'port' => (int) env('SFTP_PORT', 22),
    'username' => env('SFTP_USERNAME'),
    'privateKey' => env('SFTP_PRIVATE_KEY'),
    'throw' => true,
],
```

## Installation

You can install the package via composer:

```bash
composer require akika/laravel-stanbic
```

### Optional Steps

You can skip thse optional steps.

In your `.env`, configure which filesystem disk we will read and write the payment files to. This is already preconfigured to pick the `sftp` disk.

```shell
STANBIC_FILESYSTEM_DISK=sftp
STANBIC_INPUT_ROOT="Input"
STANBIC_OUTPUT_ROOT="Output"
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-stanbic-config"
```

## Usage

### Credit Transfer Initiation with Pain00100103

This package provides a Laravel implementation for generating ISO 20022 PAIN.001.001.03 XML messages for credit transfers.

#### Basic Usage

```php
<?php

namespace App\Console\Commands;

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
use Illuminate\Console\Command;

class DemoSinglePaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:single-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a demo single payment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
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
        $filePath = Pain00100103::make()
            ->setGroupHeader($groupHeader)
            ->setPaymentInfo($paymentInfo)
            ->store(); // Returns the stored file path

        $this->line("Saved to: \n\t{$filePath}");
    }
}
```

-   The generated XML files follow the ISO 20022 PAIN.001.001.03 standard and are automatically stored with unique filenames.
-   Stanbic bank will pick the generated file from the configured `stanbic.disk` filesystem disk.

### Reading Stanbic Status Reports

This package provides automated reading of Stanbic bank status reports (PAIN.002.001.03) from your configured storage disk.

#### Manual Execution

The command automatically scans for XML files containing `pain.002.001.03` in the configured disk and processes each one by dispatching the event.

```bash
php artisan stanbic:read
```

#### Scheduled Execution

For Laravel 11 and above, add the command to your `routes/console.php`.

```php
// Run every 15 minutes
Schedule::command('stanbic:read')->everyFifteenMinutes();

// Or run hourly
Schedule::command('stanbic:read')->hourly();

// ...etc
```

For Laravel 10 and below, add the command to your `app/Console/Kernel.php` schedule:

```php
// Run every 15 minutes
$schedule->command('stanbic:read')->everyFifteenMinutes();

// Or run hourly
$schedule->command('stanbic:read')->hourly();

// ...etc
```

#### Event Handling

The command dispatches a `Pain00200103ReportReceived` event for each status report found. Hook into this event to process the reports:

##### Create an Event Listener

```bash
php artisan make:listener ProcessStatusReport
```

##### Register the Listener

For Laravel 11 and above, In your `AppServiceProvider.php`'s boot method:

```php
Event::listen(Pain00200103ReportReceived::class, ProcessStatusReport::class);
```

For Laravel 10 and below, In your `app/Providers/EventServiceProvider.php`:

```php
use Akika\LaravelStanbic\Events\Pain00200103ReportReceived;
use App\Listeners\ProcessStatusReport;

protected $listen = [
    Pain00200103ReportReceived::class => [
        ProcessStatusReport::class,
    ],
];
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Akika Digital](https://github.com/akika)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
