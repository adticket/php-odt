<?php

namespace OdtCreator\Test\Unit\ODTCreator;

use Juit\PhpOdt\OdtCreator\Element\Paragraph;
use Juit\PhpOdt\OdtCreator\HtmlParser;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;

class HtmlParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HtmlParser
     */
    private $SUT;

    protected function setUp()
    {
        $this->SUT = new HtmlParser(new StyleFactory());
    }

    /**
     * @test
     */
    public function it_should_parse_a_single_paragraph_with_plain_text()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>I am a plain text.</p>');

        $this->assertCount(1, $paragraphs);
        $paragraph = $paragraphs[0];
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Element\Paragraph', $paragraph);

        $contents = $this->getContentsOfParagraph($paragraph);
        $this->assertCount(1, $contents);
        $this->assertEquals('I am a plain text.', $this->createTextReflection()->getValue($contents[0]));
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_on_unexpected_top_level_tags()
    {
        $SUT = $this->SUT;

        $this->setExpectedException('\InvalidArgumentException', "Unsupported top level tag 'h1'");
        $SUT->parse('<h1>Some text</h1>');
    }

    /**
     * @test
     */
    public function it_should_parse_two_paragraphs_with_plain_text()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>Some text</p><p>More text</p>');

        $this->assertCount(2, $paragraphs);
        foreach ($paragraphs as $paragraph) {
            $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Element\Paragraph', $paragraph);
        }

        $contents = $this->getContentsOfParagraph($paragraphs[0]);
        $this->assertCount(1, $contents);
        $this->assertEquals('Some text', $this->createTextReflection()->getValue($contents[0]));

        $contents = $this->getContentsOfParagraph($paragraphs[1]);
        $this->assertCount(1, $contents);
        $this->assertEquals('More text', $this->createTextReflection()->getValue($contents[0]));
    }

    /**
     * @test
     */
    public function it_should_handle_line_breaks()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>A text<br>with a line break</p>');

        $this->assertCount(1, $paragraphs);

        $contents    = $this->getContentsOfParagraph($paragraphs[0]);
        $textContent = $this->createTextReflection();

        $this->assertCount(3, $contents);

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\Text', $contents[0]);
        $this->assertEquals('A text', $textContent->getValue($contents[0]));

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\LineBreak', $contents[1]);

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\Text', $contents[2]);
        $this->assertEquals('with a line break', $textContent->getValue($contents[2]));
    }

    /**
     * @test
     */
    public function it_should_handle_font_style_bold()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>A text with a <strong>bold</strong> word</p>');

        $this->assertCount(1, $paragraphs);

        $contents = $this->getContentsOfParagraph($paragraphs[0]);
        $this->assertCount(3, $contents);

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\Text', $contents[0]);
        $this->assertEquals('A text with a ', $this->createTextReflection()->getValue($contents[0]));
        $this->assertNull($this->createTextStyleReflection()->getValue($contents[0]));

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\Text', $contents[1]);
        $this->assertEquals('bold', $this->createTextReflection()->getValue($contents[1]));
        $textStyle = $this->createTextStyleReflection()->getValue($contents[1]);
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $textStyle);

        $styleReflection = new \ReflectionClass($textStyle);
        $styleReflectionBold = $styleReflection->getProperty('isBold');
        $styleReflectionBold->setAccessible(true);
        $this->assertTrue($styleReflectionBold->getValue($textStyle));

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\Text', $contents[2]);
        $this->assertEquals(' word', $this->createTextReflection()->getValue($contents[2]));
        $this->assertNull($this->createTextStyleReflection()->getValue($contents[2]));
    }

    /**
     * @param Paragraph $paragraph
     * @return array
     */
    private function getContentsOfParagraph(Paragraph $paragraph)
    {
        $paragraphContents = new \ReflectionProperty('\Juit\PhpOdt\OdtCreator\Element\Paragraph', 'contents');
        $paragraphContents->setAccessible(true);

        return $paragraphContents->getValue($paragraph);
    }

    /**
     * @return \ReflectionProperty
     */
    private function createTextReflection()
    {
        $textContent = new \ReflectionProperty('\Juit\PhpOdt\OdtCreator\Content\Text', 'content');
        $textContent->setAccessible(true);

        return $textContent;
    }

    /**
     * @return \ReflectionProperty
     */
    private function createTextStyleReflection()
    {
        $textStyle = new \ReflectionProperty('\Juit\PhpOdt\OdtCreator\Content\Text', 'style');
        $textStyle->setAccessible(true);

        return $textStyle;
    }
}
