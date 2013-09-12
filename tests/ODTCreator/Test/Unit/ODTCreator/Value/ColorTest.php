<?php

namespace OdtCreator\Test\Unit\ODTCreator\Value;

use Juit\PhpOdt\OdtCreator\Value\Color;

class ColorTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateValueObjectWithHexCode()
    {
        $hexCode = '#01abCD';

        $sut = new \Juit\PhpOdt\OdtCreator\Value\Color($hexCode);

        $this->assertEquals($hexCode, $sut->getHexCode());
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotLeaveOutLeadingHash()
    {
        $hexCode = '000000';

        new \Juit\PhpOdt\OdtCreator\Value\Color($hexCode);
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotHaveCodeWithLessThanSixChars()
    {
        $hexCode = '#00000';

        new \Juit\PhpOdt\OdtCreator\Value\Color($hexCode);
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotHaveCodeWithMoreThanSixChars()
    {
        $hexCode = '#0000000';

        new \Juit\PhpOdt\OdtCreator\Value\Color($hexCode);
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotUseNonHexChars()
    {
        $hexCode = '#g000000';

        new \Juit\PhpOdt\OdtCreator\Value\Color($hexCode);
    }
}
