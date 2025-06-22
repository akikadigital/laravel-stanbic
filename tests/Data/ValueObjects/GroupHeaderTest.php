<?php

namespace Akika\LaravelStanbic\Tests\Data\ValueObjects;

use Akika\LaravelStanbic\Data\ValueObjects\ControlSum;
use Akika\LaravelStanbic\Data\ValueObjects\GroupHeader;
use Akika\LaravelStanbic\Tests\TestCase;

class GroupHeaderTest extends TestCase
{
    public function test_element_has_correct_properties(): void
    {
        $header = new GroupHeader;
        $header->controlSum = new ControlSum(100);

        $this->assertEquals('GrpHdr', $header->getName());

        $expected = ['GrpHdr' => [
            'CtrlSum' => 100,
        ]];
        $this->assertEquals($expected, $header->getElement());
    }
}
