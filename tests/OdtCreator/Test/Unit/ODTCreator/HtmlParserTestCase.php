<?php

namespace OdtCreator\Test\Unit\ODTCreator;

use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Element\Paragraph;
use Juit\PhpOdt\OdtCreator\Style\TextStyle;

class HtmlParserTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @param Paragraph $actual
     */
    protected function assertParagraph($actual)
    {
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Element\Paragraph', $actual);
    }

    /**
     * @param string $expected
     * @param Text $actual
     */
    protected function assertTextWithContent($expected, $actual)
    {
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\Text', $actual);

        $textContent = new \ReflectionProperty('\Juit\PhpOdt\OdtCreator\Content\Text', 'content');
        $textContent->setAccessible(true);

        $this->assertEquals($expected, $textContent->getValue($actual));
    }

    /**
     * @param LineBreak $actual
     */
    protected function assertLineBreak($actual)
    {
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Content\LineBreak', $actual);
    }

    /**
     * @param Paragraph $paragraph
     * @return array
     */
    protected function getContentsOfParagraph(Paragraph $paragraph)
    {
        $paragraphContents = new \ReflectionProperty('\Juit\PhpOdt\OdtCreator\Element\Paragraph', 'contents');
        $paragraphContents->setAccessible(true);

        return $paragraphContents->getValue($paragraph);
    }

    /**
     * @param Text $text
     */
    protected function assertBold($text)
    {
        $style = $this->getStyleOfText($text);

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style);
        $this->assertPropertyTrue('isBold', $style);
    }

    /**
     * @param Text $text
     */
    protected function assertNotBold($text)
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
    protected function assertItalic($text)
    {
        $style = $this->getStyleOfText($text);

        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style);
        $this->assertPropertyTrue('isItalic', $style);
    }

    /**
     * @param Text $text
     */
    protected function assertNotItalic($text)
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
