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

        $message = 'Failed asserting that text is bold.';
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style, $message);
        $this->assertPropertyTrue('isBold', $style, $message);
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

        $message = 'Failed asserting that text is not bold.';
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style, $message);
        $this->assertPropertyFalsy('isBold', $style, $message);
    }

    /**
     * @param Text $text
     */
    protected function assertItalic($text)
    {
        $style = $this->getStyleOfText($text);

        $message = 'Failed asserting that text is italic.';
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style, $message);
        $this->assertPropertyTrue('isItalic', $style, $message);
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

        $message = 'Failed asserting that text is not italic.';
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style, $message);
        $this->assertPropertyFalsy('isItalic', $style, $message);
    }

    protected function assertUnderline($text)
    {
        $style = $this->getStyleOfText($text);

        $message = 'Failed asserting that text is underlined.';
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style, $message);
        $this->assertPropertyTrue('isUnderline', $style, $message);
    }

    /**
     * @param Text $text
     */
    protected function assertNotUnderline($text)
    {
        $style = $this->getStyleOfText($text);

        if (null === $style) {
            return;
        }

        $message = 'Failed asserting that text is not underlined.';
        $this->assertInstanceOf('\Juit\PhpOdt\OdtCreator\Style\TextStyle', $style, $message);
        $this->assertPropertyFalsy('isUnderline', $style, $message);
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
     * @param string $message
     */
    private function assertPropertyTrue($propertyName, $object, $message = '')
    {
        $reflection = new \ReflectionClass($object);
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $this->assertTrue($property->getValue($object), $message);
    }

    /**
     * @param string $propertyName
     * @param object $object
     * @param string $message
     */
    private function assertPropertyFalsy($propertyName, $object, $message = '')
    {
        $reflection = new \ReflectionClass($object);
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $value = $property->getValue($object);
        if (null === $value) {
            return;
        }
        $this->assertFalse($value, $message);
    }
}
