<?php

namespace OdtCreator\Test\Unit\OdtCreator\HtmlParser;

use Juit\PhpOdt\OdtCreator\HtmlParser\TextStyleConfig;
use Juit\PhpOdt\OdtCreator\Value\FontSize;

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
        $this->assertNull($SUT->getFontSize());
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

        $SUT->setFontSize(new FontSize('20pt'));
        $this->assertNull($SUT->getFontSize());
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

        $secondInstance = $firstInstance->setFontSize(new FontSize('20pt'));
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\HtmlParser\TextStyleConfig', $secondInstance);
        $this->assertNotSame($firstInstance, $secondInstance);
    }

    /**
     * @test
     */
    public function it_should_take_a_font_size()
    {
        $SUT = new TextStyleConfig();

        $this->assertNull($SUT->getFontSize());

        $SUT = $SUT->setFontSize(new FontSize('20pt'));
        $this->assertEquals(new FontSize('20pt'), $SUT->getFontSize());
    }

    /**
     * @test
     */
    public function it_should_take_a_font_name()
    {
        $SUT = new TextStyleConfig();

        $this->assertNull($SUT->getFontName());

        $SUT = $SUT->setFontName('Times New Roman');
        $this->assertEquals('Times New Roman', $SUT->getFontName());
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
        $SUT = $SUT->setFontSize(new FontSize('20pt'));
        $SUT = $SUT->setFontName('Times New Roman');
        $SUT = $SUT->setBold();

        $this->assertTrue($SUT->isBold());
        $this->assertTrue($SUT->isItalic());
        $this->assertTrue($SUT->isUnderline());
        $this->assertEquals(new FontSize('20pt'), $SUT->getFontSize());
        $this->assertEquals('Times New Roman', $SUT->getFontName());
    }
}
