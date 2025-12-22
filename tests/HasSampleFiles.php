<?php

namespace Akika\LaravelStanbic\Tests;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Saloon\XmlWrangler\XmlReader;

trait HasSampleFiles
{
    public function ackReport(): string
    {
        return $this->getFileContents('ack.xml');
    }

    public function nackReport(): string
    {
        return $this->getFileContents('nack.xml');
    }

    public function invalidAccountNoReport(): string
    {
        return $this->getFileContents('invalid_account_no.xml');
    }

    public function acspReport(): string
    {
        return $this->getFileContents('multiple/acsp/n_pmt_1_tx/MY_COMPANY_Pain001v3_GH_TST_20251107114753482.xml_FINAUDTST_251107140021390.xml');
    }

    /** @return array<int, XmlReader> */
    public function getAllXmlReaders(): array
    {
        return array_map(
            fn (string $path) => XmlReader::fromString($this->getFileContents($path)),
            $this->reports,
        );
    }

    public function basePath(string $path = ''): string
    {
        $orchestraPath = '/vendor/orchestra/testbench-core/laravel';
        $orchestraPathWin = '\vendor\orchestra\testbench-core\laravel';

        $root = base_path();
        $root = str_replace($orchestraPath, '', $root);
        $root = str_replace($orchestraPathWin, '', $root);

        return $root."/{$path}";
    }

    public function getFileContents(string $path): string
    {
        $fullPath = $this->basePath("tests/samples/{$path}");

        if (! $contents = file_get_contents($fullPath)) {
            throw new FileNotFoundException;
        }

        return $contents;
    }

    public array $reports = [
        'ack.xml',
        'nack.xml',
        'invalid_account_no.xml',
        // multiple/acsp/1_pmt_n_tx
        'multiple/acsp/1_pmt_n_tx/MY_COMPANY_Pain001v3_GH_TST_20251111023955215.xml_ACK_20251111044022679.xml',
        'multiple/acsp/1_pmt_n_tx/MY_COMPANY_Pain001v3_GH_TST_20251111023955215.xml_FINAUDTST_251111081517516.xml',
        'multiple/acsp/1_pmt_n_tx/MY_COMPANY_Pain001v3_GH_TST_20251111023955215.xml_INTAUDTST_251111044027581.xml',
        // multiple/acsp/n_pmt_1_tx
        'multiple/acsp/n_pmt_1_tx/MY_COMPANY_Pain001v3_GH_TST_20251107114753482.xml_ACK_20251107134815842.xml',
        'multiple/acsp/n_pmt_1_tx/MY_COMPANY_Pain001v3_GH_TST_20251107114753482.xml_FINAUDTST_251107140021390.xml',
        'multiple/acsp/n_pmt_1_tx/MY_COMPANY_Pain001v3_GH_TST_20251107114753482.xml_INTAUDTST_251107134826404.xml',
        // multiple/part/n_pmt_1_tx
        'multiple/part/n_pmt_1_tx/MY_COMPANY_Pain001v3_GH_TST_20251107134349060.xml_ACK_20251107154408179.xml',
        'multiple/part/n_pmt_1_tx/MY_COMPANY_Pain001v3_GH_TST_20251107134349060.xml_FINAUDTST_251107160019805.xml',
        'multiple/part/n_pmt_1_tx/MY_COMPANY_Pain001v3_GH_TST_20251107134349060.xml_INTAUDTST_251107154418940.xml',
        // multiple/pdng/1_pmt_n_tx
        'multiple/pdng/1_pmt_n_tx/MY_COMPANY_Pain001v3_GH_TST_20251111023955215.xml_ACK_20251111044022679.xml',
        'multiple/pdng/1_pmt_n_tx/MY_COMPANY_Pain001v3_GH_TST_20251111023955215.xml_INTAUDTST_251111044027581.xml',
        // single/acsp
        'single/acsp/MY_COMPANY_Pain001v3_GH_TST_20251106211257012.xml_ACK_20251106231317487.xml',
        'single/acsp/MY_COMPANY_Pain001v3_GH_TST_20251106211257012.xml_FINAUDTST_251107081515820.xml',
        'single/acsp/MY_COMPANY_Pain001v3_GH_TST_20251106211257012.xml_INTAUDTST_251106231340050.xml',
    ];
}
