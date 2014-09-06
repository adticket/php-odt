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

        $parseResult = $SUT->parse('<p>I am a plain text.</p>');

        $this->assertInternalType('array', $parseResult);
        $this->assertCount(1, $parseResult);
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Element\Paragraph', $parseResult[0]);

        $document = new Document();
        $document->loadXML('<?xml version="1.0" encoding="UTF-8"?><root></root>');
        $rootElement = $document->find('//root')->item(0);

        $parseResult[0]->renderToContent($document, $rootElement);

        $expectedXml = '<?xml version="1.0" encoding="UTF-8"?>
            <root>
                <text:p xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0">I am a plain text.</text:p>
            </root>';
        $this->assertXmlStringEqualsXmlString($expectedXml, $document->saveXML());
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

        $parseResult = $SUT->parse('<p>Some text</p><p>More text</p>');

        $this->assertInternalType('array', $parseResult);
        $this->assertCount(2, $parseResult);
        foreach ($parseResult as $object) {
            $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Element\Paragraph', $object);
        }

        $document = new Document();
        $document->loadXML('<?xml version="1.0" encoding="UTF-8"?><root></root>');
        $rootElement = $document->find('//root')->item(0);

        foreach ($parseResult as $object) {
            $object->renderToContent($document, $rootElement);
        }
        $expectedXml = '<?xml version="1.0" encoding="UTF-8"?>
            <root>
                <text:p xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0">Some text</text:p>
                <text:p xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0">More text</text:p>
            </root>';
        $this->assertXmlStringEqualsXmlString($expectedXml, $document->saveXML());
    }
}
