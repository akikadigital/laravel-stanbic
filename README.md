# Laravel Stanbic

[![PHPStan](https://github.com/akikadigital/laravel-stanbic/actions/workflows/phpstan.yml/badge.svg)](https://github.com/akikadigital/laravel-stanbic/actions/workflows/phpstan.yml)
[![run-tests](https://github.com/akikadigital/laravel-stanbic/actions/workflows/run-tests.yml/badge.svg)](https://github.com/akikadigital/laravel-stanbic/actions/workflows/run-tests.yml)

An unofficial package for Stanbic bank; following the ISO20022 standard.

## Prerequisites

Configure `sftp` first before you proceed: https://laravel.com/docs/12.x/filesystem#sftp-driver-configuration

Add the SFTP configs in the `config/filesystem.php`. Below is the recommended set of configs. Ensure you set the corresponding env values in your `.env` as well:

```php
'stanbic_sftp' => [
    'driver' => 'sftp',
    'host' => env('STANBIC_SFTP_HOST'),
    'port' => (int) env('STANBIC_SFTP_PORT', 22),
    'username' => env('STANBIC_SFTP_USERNAME'),
    'privateKey' => env('STANBIC_SFTP_PRIVATE_KEY'),
    'throw' => true,
],
```

## Installation

You can install the package via composer:

```bash
composer require akika/laravel-stanbic
```

You can publish the config file with (optional):

```bash
php artisan vendor:publish --tag="laravel-stanbic-config"
```

### Environment Variables

Add the following environment variables to your `.env` file:

```bash
# SFTP Connection Settings
STANBIC_SFTP_HOST=your-sftp-host.example.com
STANBIC_SFTP_PORT=22
STANBIC_SFTP_USERNAME=your-username
STANBIC_SFTP_PRIVATE_KEY=/path/to/your/private/key

# File System Configuration
STANBIC_FILESYSTEM_DISK=stanbic_sftp          # The disk to use for SFTP operations
STANBIC_INPUT_ROOT=Inbox                      # Directory where incoming reports are read from
STANBIC_OUTPUT_ROOT=Outbox                    # Directory where outgoing files are uploaded to

# Output File Naming
STANBIC_OUTPUT_FILE_PREFIX=MY_COMPANYC2C_Pain001v3_GH_TST_  # Prefix for generated files (include environment suffix)

# Report Processing
STANBIC_REPORTS_CLEANUP_AFTER_PROCESSING=true  # Remove processed reports from SFTP server to prevent re-processing

# Backup Settings
STANBIC_REPORTS_BACKUP_ENABLED=true            # Enable local backup of processed reports
STANBIC_REPORTS_BACKUP_DISK=local              # Local disk for storing report backups
STANBIC_REPORTS_BACKUP_ROOT=stanbic/reports    # Directory path within backup disk for storing reports
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
    protected $signature = 'demo:single-payment';

    protected $description = 'Create a demo single payment';

    public function handle()
    {
        $messageId = fake()->regexify('MSG0[A-Z0-9]{5}');
        $companyName = 'MY.COMPANY/NAMEC2C';
        $companyAcNo = '1234567891234';

        // 1. Create group header
        $groupHeader = GroupHeader::make()
            ->setMessageId($messageId)
            ->setCreationDate(now())
            ->setInitiatingParty(null, $companyName);

        $filePath = Pain00100103::make()
            ->setGroupHeader($groupHeader)
            ->addPaymentInfo($this->getPaymentInfo($companyName, $companyAcNo))
            ->store();

        $this->line("Saved to: \n\t{$filePath}");
    }

    public function getPaymentInfo(string $companyName, string $companyAcNo): PaymentInfo
    {
        $debtorBankCode = '190101';
        $paymentInfoId = fake()->regexify('PMTINF0[A-Z0-9]{5}');

        $paymentInfo = PaymentInfo::make()
            ->setPaymentInfoId($paymentInfoId)
            ->setPaymentMethod(PaymentMethod::CreditTransfer)
            ->setBatchBooking(true)
            ->setPaymentTypeInfo(InstructionPriority::Norm)
            ->setRequestedExecutionDate(now())
            ->setDebtor($companyName, new PostalAddress(countryCode: CountryCode::Ghana))
            ->setDebtorAccount($companyAcNo, Currency::Cedi)
            ->setDebtorAgent($debtorBankCode)
            ->setChargeBearer(ChargeBearerType::Debt)
            ->addCreditTransferTransactionInfo($this->getCreditTransferTransactionInfo());

        return $paymentInfo;
    }

    public function getCreditTransferTransactionInfo(): CreditTransferTransactionInfo
    {
        $paymentId = fake()->regexify('PMT0[A-Z0-9]{5}');
        $instructionId = fake()->regexify('INST0[A-Z0-9]{5}');
        $amount = fake()->numberBetween(1_000, 1_999);

        $creditorBankCode = '190101';
        $bank = 'Stanbic Bank Ghana Ltd';
        $beneficiaryName = 'Darion Ferry';
        $beneficiaryAcNo = '4321987654321';

        $paymentDescription = fake()->words(3, true);

        return CreditTransferTransactionInfo::make()
            ->setPaymentId($paymentId, $instructionId)
            ->setAmount($amount, Currency::Cedi)
            ->setCreditorAgent($creditorBankCode, $bank, new PostalAddress(countryCode: CountryCode::Ghana))
            ->setCreditor($beneficiaryName, new PostalAddress(
                fake()->streetName(),
                fake()->buildingNumber(),
                fake()->postcode(),
                fake()->city(),
                CountryCode::Ghana,
            ))
            ->setCreditorAccount($beneficiaryAcNo)
            ->setRemittanceInfo($paymentDescription);
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
