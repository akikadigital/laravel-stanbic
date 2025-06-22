<?php

namespace Akika\LaravelStanbic\Tests\ValueObjects;

use Akika\LaravelStanbic\Tests\TestCase;
use Akika\LaravelStanbic\ValueObjects\Document;

class DocumentTest extends TestCase
{
    public function test_returns_correct_name(): void
    {
        $document = new Document(
            xmlns: 'urn:iso:std:iso:20022:tech:xsd:pain.001.001.03',
            xmlnsXsi: 'http://www.w3.org/2001/XMLSchema-instance',
        );

        $this->assertEquals('Document', $document->getName());
    }

    public function test_returns_correct_attributes(): void
    {
        $document = new Document(
            xmlns: 'urn:iso:std:iso:20022:tech:xsd:pain.001.001.03',
            xmlnsXsi: 'http://www.w3.org/2001/XMLSchema-instance',
        );

        $expected = [
            'xmlns' => 'urn:iso:std:iso:20022:tech:xsd:pain.001.001.03',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
        ];

        $this->assertEquals($expected, $document->getAttributes());
    }

    public function test_can_get_xml_from_body(): void
    {
        $document = new Document(
            xmlns: 'urn:iso:std:iso:20022:tech:xsd:pain.001.001.03',
            xmlnsXsi: 'http://www.w3.org/2001/XMLSchema-instance',
        );

        $rootElement = $document->getElement();

        $this->assertEquals($document->getName(), $rootElement->getName());
        $this->assertEquals($document->getAttributes(), $rootElement->getAttributes());
    }
}
