<?php

namespace OdtCreator\Test\Unit\ODTCreator\Value;

use OdtCreator\Value\Color;

class ColorTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateValueObjectWithHexCode()
    {
        $hexCode = '#01abCD';

        $sut = new Color($hexCode);

        $this->assertEquals($hexCode, $sut->getHexCode());
    }

    /**
     * @expectedException \OdtCreator\Style\StyleException
     */
    public function testCannotLeaveOutLeadingHash()
    {
        $hexCode = '000000';

        new Color($hexCode);
    }

    /**
     * @expectedException \OdtCreator\Style\StyleException
     */
    public function testCannotHaveCodeWithLessThanSixChars()
    {
        $hexCode = '#00000';

        new Color($hexCode);
    }

    /**
     * @expectedException \OdtCreator\Style\StyleException
     */
    public function testCannotHaveCodeWithMoreThanSixChars()
    {
        $hexCode = '#0000000';

        new Color($hexCode);
    }

    /**
     * @expectedException \OdtCreator\Style\StyleException
     */
    public function testCannotUseNonHexChars()
    {
        $hexCode = '#g000000';

        new Color($hexCode);
    }
}
