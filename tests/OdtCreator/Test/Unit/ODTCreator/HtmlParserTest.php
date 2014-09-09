<?php

namespace OdtCreator\Test\Unit\ODTCreator;

use Juit\PhpOdt\OdtCreator\HtmlParser;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;

class HtmlParserTest extends HtmlParserTestCase
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
     * @test
     */
    public function it_should_handle_font_style_underline()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>A text with an <u>underlined</u> word</p>');

        $this->assertCount(1, $paragraphs);

        $contents = $this->getContentsOfParagraph($paragraphs[0]);
        $this->assertCount(3, $contents);

        $actual = $contents[0];
        $this->assertTextWithContent('A text with an ', $actual);
        $this->assertNotUnderline($actual);

        $actual = $contents[1];
        $this->assertTextWithContent('underlined', $actual);
        $this->assertUnderline($actual);

        $actual = $contents[2];
        $this->assertTextWithContent(' word', $actual);
        $this->assertNotUnderline($actual);
    }

    /**
     * @test
     */
    public function it_should_handle_cascaded_font_styles()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>A text with <strong><em>strong and emphasized</em></strong> words</p>');

        $this->assertCount(1, $paragraphs);

        $contents = $this->getContentsOfParagraph($paragraphs[0]);
        $this->assertCount(3, $contents);

        $actual = $contents[0];
        $this->assertTextWithContent('A text with ', $actual);
        $this->assertNotBold($actual);
        $this->assertNotItalic($actual);

        $actual = $contents[1];
        $this->assertTextWithContent('strong and emphasized', $actual);
        $this->assertBold($actual);
        $this->assertItalic($actual);

        $actual = $contents[2];
        $this->assertTextWithContent(' words', $actual);
        $this->assertNotBold($actual);
        $this->assertNotItalic($actual);
    }

    /**
     * @test
     */
    public function it_should_handle_more_complex_style_cascadings()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>A text with <em>emphasized and <strong>strong</strong></em> words</p>');

        $this->assertCount(1, $paragraphs);

        $contents = $this->getContentsOfParagraph($paragraphs[0]);
        $this->assertCount(4, $contents);

        $actual = $contents[0];
        $this->assertTextWithContent('A text with ', $actual);
        $this->assertNotBold($actual);
        $this->assertNotItalic($actual);

        $actual = $contents[1];
        $this->assertTextWithContent('emphasized and ', $actual);
        $this->assertNotBold($actual);
        $this->assertItalic($actual);

        $actual = $contents[2];
        $this->assertTextWithContent('strong', $actual);
        $this->assertBold($actual);
        $this->assertItalic($actual);

        $actual = $contents[3];
        $this->assertTextWithContent(' words', $actual);
        $this->assertNotBold($actual);
        $this->assertNotItalic($actual);
    }

    /**
     * @test
     */
    public function it_should_handle_font_sizes()
    {
        $SUT = $this->SUT;

        $paragraphs = $SUT->parse('<p>A text with <span style="font-size: 20px;">some bigger</span> words</p>');

        $this->assertCount(1, $paragraphs);

        $contents = $this->getContentsOfParagraph($paragraphs[0]);
        $this->assertCount(3, $contents);

        $actual = $contents[0];
        $this->assertTextWithContent('A text with ', $actual);
        $this->assertNoFontSize($actual);

        $actual = $contents[1];
        $this->assertTextWithContent('some bigger', $actual);
        $this->assertFontSize('20pt', $actual);

        $actual = $contents[2];
        $this->assertTextWithContent(' words', $actual);
    }
}
