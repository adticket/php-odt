<?php

namespace OdtCreator\Test\Unit\OdtCreator\Value;

use Juit\PhpOdt\OdtCreator\Value\Color;

class ColorTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateValueObjectWithHexCode()
    {
        $hexCode = '#01abCD';

        $sut = new Color($hexCode);

        $this->assertEquals($hexCode, $sut->getHexCode());
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotLeaveOutLeadingHash()
    {
        $hexCode = '000000';

        new Color($hexCode);
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotHaveCodeWithLessThanSixChars()
    {
        $hexCode = '#00000';

        new Color($hexCode);
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotHaveCodeWithMoreThanSixChars()
    {
        $hexCode = '#0000000';

        new Color($hexCode);
    }

    /**
     * @expectedException \Juit\PhpOdt\OdtCreator\Style\StyleException
     */
    public function testCannotUseNonHexChars()
    {
        $hexCode = '#g000000';

        new Color($hexCode);
    }

    /**
     * @test
     */
    public function it_should_create_a_valid_instance_from_rgb_values()
    {
        $sut = Color::fromRgb(1, 154, 239);

        $this->assertEquals(new Color('#019aef'), $sut);
    }

}
