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
        $this->assertEquals('1.5cm', $SUT->getValue());
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_on_missing_unit()
    {
        $this->setExpectedException('InvalidArgumentException', "Unrecognized length value '1'");
        $SUT = new Length('1');
    }

    /**
     * @test
     */
    public function it_should_multiply_itself_by_an_integer()
    {
        $SUT = new Length('2cm');

        $this->assertEquals('4cm', $SUT->multiplyBy(2)->getValue());
    }

    /**
     * @test
     */
    public function it_should_multiply_itself_by_a_float_and_round_to_three_digits()
    {
        $SUT = new Length('2cm');

        $this->assertEquals('2.714cm', $SUT->multiplyBy(1.35678)->getValue());
    }

    /**
     * @test
     */
    public function it_should_respect_the_unit_when_multiplying()
    {
        $SUT = new Length('2pt');

        $this->assertEquals('2.714pt', $SUT->multiplyBy(1.35678)->getValue());
    }
}
