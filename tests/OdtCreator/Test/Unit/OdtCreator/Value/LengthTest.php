<?php

namespace OdtCreator\Test\Unit\OdtCreator\Value;

use Juit\PhpOdt\OdtCreator\Value\Length;

class LengthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_accept_centimeters_as_input()
    {
        $SUT = new Length('1cm');
        $this->assertEquals('1cm', $SUT->getValue());
    }

    /**
     * @test
     */
    public function it_should_accept_points_as_input()
    {
        $SUT = new Length('1pt');
        $this->assertEquals('1pt', $SUT->getValue());
    }

    /**
     * @test
     */
    public function it_should_accept_decimal_input()
    {
        $SUT = new Length('1.50cm');
        $this->assertEquals('1.50cm', $SUT->getValue());
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_on_missing_unit()
    {
        $this->setExpectedException('InvalidArgumentException', "Unrecognized length value '1'");
        $SUT = new Length('1');
    }
} 
