<?php

namespace ODTCreator\Test\Unit\ODTCreator\Value;

use ODTCreator\Value\FontSize;

class FontSizeTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateObjectFromInteger()
    {
        $value = 20;

        $sut = new FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromFloat()
    {
        $value = 20.5;

        $sut = new FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromStringWithPt()
    {
        $value = '20pt';

        $sut = new FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromStringWithCm()
    {
        $value = '20cm';

        $sut = new FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromStringWithMm()
    {
        $value = '20mm';

        $sut = new FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromStringWithPercent()
    {
        $value = '50%';

        $sut = new FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    /**
     * @expectedException \ODTCreator\Style\StyleException
     */
    public function testCannotCreateObjectFromStringWithBlank()
    {
        $value = '20 pt';

        $sut = new FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    /**
     * @expectedException \ODTCreator\Style\StyleException
     */
    public function testCannotCreateObjectFromStringWithPercentWithBlank()
    {
        $value = '50 %';

        $sut = new FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }
}
