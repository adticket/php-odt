<?php

namespace OdtCreator\Test\Unit\OdtCreator\Value;

use Juit\PhpOdt\OdtCreator\Value\BorderStyle;
use Juit\PhpOdt\OdtCreator\Value\Color;
use Juit\PhpOdt\OdtCreator\Value\Length;

class BorderStyleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_a_valid_instance()
    {
        $SUT = new BorderStyle(new Length('0.06pt'), new Color('#000000'));

        $this->assertEquals('0.06pt solid #000000', $SUT->getValue());
    }
}
