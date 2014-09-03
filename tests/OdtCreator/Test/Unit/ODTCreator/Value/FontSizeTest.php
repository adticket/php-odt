<?php

namespace OdtCreator\Test\Unit\ODTCreator\Value;

use Juit\PhpOdt\OdtCreator\Value\FontSize;

class FontSizeTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateObjectFromInteger()
    {
        $value = 20;

        $sut = new \Juit\PhpOdt\OdtCreator\Value\FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromFloat()
    {
        $value = 20.5;

        $sut = new \Juit\PhpOdt\OdtCreator\Value\FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromStringWithPt()
    {
        $value = '20pt';

        $sut = new \Juit\PhpOdt\OdtCreator\Value\FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromStringWithCm()
    {
        $value = '20cm';

        $sut = new \Juit\PhpOdt\OdtCreator\Value\FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromStringWithMm()
    {
        $value = '20mm';

        $sut = new \Juit\PhpOdt\OdtCreator\Value\FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    public function testCanCreateObjectFromStringWithPercent()
    {
        $value = '50%';

        $sut = new \Juit\PhpOdt\OdtCreator\Value\FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotCreateObjectFromStringWithBlank()
    {
        $value = '20 pt';

        $sut = new \Juit\PhpOdt\OdtCreator\Value\FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotCreateObjectFromStringWithPercentWithBlank()
    {
        $value = '50 %';

        $sut = new \Juit\PhpOdt\OdtCreator\Value\FontSize($value);

        $this->assertEquals($value, $sut->getValue());
    }
}
