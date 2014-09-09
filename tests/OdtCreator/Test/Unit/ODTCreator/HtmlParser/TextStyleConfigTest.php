<?php

namespace OdtCreator\Test\Unit\ODTCreator\HtmlParser;

use Juit\PhpOdt\OdtCreator\HtmlParser\TextStyleConfig;

class TextStyleConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_be_configured_as_plain_by_default()
    {
        $SUT = new TextStyleConfig();

        $this->assertFalse($SUT->isBold());
        $this->assertFalse($SUT->isItalic());
        $this->assertFalse($SUT->isUnderline());
    }

    /**
     * @test
     */
    public function it_should_be_immutable()
    {
        $SUT = new TextStyleConfig();

        $SUT->setBold();

        $this->assertFalse($SUT->isBold());
    }

    /**
     * @test
     */
    public function it_should_create_a_new_instance_if_a_setter_is_called()
    {
        $firstInstance = new TextStyleConfig();

        $secondInstance = $firstInstance->setBold();

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\HtmlParser\TextStyleConfig', $secondInstance);
        $this->assertNotSame($firstInstance, $secondInstance);
    }

    /**
     * @test
     */
    public function it_should_copy_the_values_of_the_source_instance()
    {
        $firstInstance = new TextStyleConfig();
        $secondInstance = $firstInstance->setBold();
        $thirdInstance = $secondInstance->setItalic();
        $fourthInstance = $thirdInstance->setUnderline();

        $this->assertTrue($fourthInstance->isBold());
        $this->assertTrue($fourthInstance->isItalic());
        $this->assertTrue($fourthInstance->isUnderline());
    }
}
