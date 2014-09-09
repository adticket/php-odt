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

        $SUT->setItalic();
        $this->assertFalse($SUT->isItalic());

        $SUT->setUnderline();
        $this->assertFalse($SUT->isUnderline());
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

        $secondInstance = $firstInstance->setItalic();
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\HtmlParser\TextStyleConfig', $secondInstance);
        $this->assertNotSame($firstInstance, $secondInstance);

        $secondInstance = $firstInstance->setUnderline();
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\HtmlParser\TextStyleConfig', $secondInstance);
        $this->assertNotSame($firstInstance, $secondInstance);
    }

    /**
     * @test
     */
    public function it_should_copy_the_values_of_the_source_instance()
    {
        $SUT = new TextStyleConfig();
        $SUT = $SUT->setBold();
        $SUT = $SUT->setItalic();
        $SUT = $SUT->setUnderline();
        $SUT = $SUT->setBold();

        $this->assertTrue($SUT->isBold());
        $this->assertTrue($SUT->isItalic());
        $this->assertTrue($SUT->isUnderline());
    }
}
