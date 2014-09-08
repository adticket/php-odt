<?php

namespace OdtCreator\Test\Unit\ODTCreator;

use FluentDOM\Document;
use Juit\PhpOdt\OdtCreator\HtmlParser;

class HtmlParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_parse_a_single_paragraph_with_plain_text()
    {
        $SUT = new HtmlParser();

        $paragraphs = $SUT->parse('<p>I am a plain text.</p>');

        $this->assertCount(1, $paragraphs);
        $paragraph = $paragraphs[0];
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Element\Paragraph', $paragraph);

        $contents = $this->createParagraphReflection()->getValue($paragraph);
        $this->assertCount(1, $contents);
        $this->assertEquals('I am a plain text.', $this->createTextReflection()->getValue($contents[0]));
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_on_unexpected_top_level_tags()
    {
        $SUT = new HtmlParser();

        $this->setExpectedException('\InvalidArgumentException', "Unsupported top level tag 'h1'");
        $SUT->parse('<h1>Some text</h1>');
    }

    /**
     * @test
     */
    public function it_should_parse_two_paragraphs_with_plain_text()
    {
        $SUT = new HtmlParser();

        $paragraphs = $SUT->parse('<p>Some text</p><p>More text</p>');

        $this->assertCount(2, $paragraphs);
        foreach ($paragraphs as $paragraph) {
            $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Element\Paragraph', $paragraph);
        }

        $contents = $this->createParagraphReflection()->getValue($paragraphs[0]);
        $this->assertCount(1, $contents);
        $this->assertEquals('Some text', $this->createTextReflection()->getValue($contents[0]));

        $contents = $this->createParagraphReflection()->getValue($paragraphs[1]);
        $this->assertCount(1, $contents);
        $this->assertEquals('More text', $this->createTextReflection()->getValue($contents[0]));
    }

    /**
     * @test
     */
    public function it_should_handle_line_breaks()
    {
        $SUT = new HtmlParser();

        $paragraphs = $SUT->parse('<p>A text<br>with a line break</p>');

        $contents = $this->createParagraphReflection()->getValue($paragraphs[0]);
        $textContent = $this->createTextReflection();

        $this->assertCount(3, $contents);

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\Text', $contents[0]);
        $this->assertEquals('A text', $textContent->getValue($contents[0]));

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\LineBreak', $contents[1]);

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\Text', $contents[2]);
        $this->assertEquals('with a line break', $textContent->getValue($contents[2]));
    }

    /**
     * @return \ReflectionProperty
     */
    private function createParagraphReflection()
    {
        $paragraphContents = new \ReflectionProperty('\Juit\PhpOdt\OdtCreator\Element\Paragraph', 'contents');
        $paragraphContents->setAccessible(true);

        return $paragraphContents;
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
}
