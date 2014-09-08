<?php

namespace OdtCreator\Test\Unit\ODTCreator;

use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Element\Paragraph;
use Juit\PhpOdt\OdtCreator\HtmlParser;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Style\TextStyle;

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
        $this->assertParagraph($paragraph);

        $contents = $this->getContentsOfParagraph($paragraph);
        $this->assertCount(1, $contents);
        $this->assertTextWithContent('I am a plain text.', $contents[0]);
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
            $this->assertParagraph($paragraph);
        }

        $contents = $this->getContentsOfParagraph($paragraphs[0]);
        $this->assertCount(1, $contents);
        $this->assertTextWithContent('Some text', $contents[0]);

        $contents = $this->getContentsOfParagraph($paragraphs[1]);
        $this->assertCount(1, $contents);
        $this->assertTextWithContent('More text', $contents[0]);
    }

    /**
     * @test
     */
    public function it_should_handle_line_breaks()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>A text<br>with a line break</p>');

        $this->assertCount(1, $paragraphs);

        $contents = $this->getContentsOfParagraph($paragraphs[0]);
        $this->assertCount(3, $contents);

        $this->assertTextWithContent('A text', $contents[0]);
        $this->assertLineBreak($contents[1]);
        $this->assertTextWithContent('with a line break', $contents[2]);
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

        $actual = $contents[0];
        $this->assertTextWithContent('A text with a ', $actual);
        $this->assertNotBold($actual);

        $actual = $contents[1];
        $this->assertTextWithContent('bold', $actual);
        $this->assertBold($actual);

        $actual = $contents[2];
        $this->assertTextWithContent(' word', $actual);
        $this->assertNotBold($actual);
    }

    /**
     * @test
     */
    public function it_should_handle_font_style_italic()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>A text with an <em>emphasized</em> word</p>');

        $this->assertCount(1, $paragraphs);

        $contents = $this->getContentsOfParagraph($paragraphs[0]);
        $this->assertCount(3, $contents);

        $actual = $contents[0];
        $this->assertTextWithContent('A text with an ', $actual);
        $this->assertNotItalic($actual);

        $actual = $contents[1];
        $this->assertTextWithContent('emphasized', $actual);
        $this->assertItalic($actual);

        $actual = $contents[2];
        $this->assertTextWithContent(' word', $actual);
        $this->assertNotItalic($actual);
    }

    /**
     * @param Paragraph $actual
     */
    private function assertParagraph($actual)
    {
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Element\Paragraph', $actual);
    }

    /**
     * @param string $expected
     * @param Text $actual
     */
    private function assertTextWithContent($expected, $actual)
    {
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\Text', $actual);

        $textContent = new \ReflectionProperty('\Juit\PhpOdt\OdtCreator\Content\Text', 'content');
        $textContent->setAccessible(true);

        $this->assertEquals($expected, $textContent->getValue($actual));
    }

    /**
     * @param LineBreak $actual
     */
    private function assertLineBreak($actual)
    {
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\LineBreak', $actual);
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
     * @param Text $text
     */
    private function assertBold($text)
    {
        $style = $this->getStyleOfText($text);

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style);
        $this->assertPropertyTrue('isBold', $style);
    }

    /**
     * @param Text $text
     */
    private function assertNotBold($text)
    {
        $style = $this->getStyleOfText($text);

        if (null === $style) {
            return;
        }

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style);
        $this->assertPropertyFalsy('isBold', $style);
    }

    /**
     * @param Text $text
     */
    private function assertItalic($text)
    {
        $style = $this->getStyleOfText($text);

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style);
        $this->assertPropertyTrue('isItalic', $style);
    }

    /**
     * @param Text $text
     */
    private function assertNotItalic($text)
    {
        $style = $this->getStyleOfText($text);

        if (null === $style) {
            return;
        }

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style);
        $this->assertPropertyFalsy('isItalic', $style);
    }

    /**
     * @param Text $text
     * @return TextStyle|null
     */
    private function getStyleOfText(Text $text)
    {
        $textStyle = new \ReflectionProperty('\Juit\PhpOdt\OdtCreator\Content\Text', 'style');
        $textStyle->setAccessible(true);

        return $textStyle->getValue($text);
    }

    /**
     * @param string $propertyName
     * @param object $object
     */
    private function assertPropertyTrue($propertyName, $object)
    {
        $reflection = new \ReflectionClass($object);
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $this->assertTrue($property->getValue($object));
    }

    /**
     * @param string $propertyName
     * @param object $object
     */
    private function assertPropertyFalsy($propertyName, $object)
    {
        $reflection = new \ReflectionClass($object);
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $value = $property->getValue($object);
        if (null === $value) {
            return;
        }
        $this->assertFalse($value);
    }
}
