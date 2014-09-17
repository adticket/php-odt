<?php

namespace Juit\PhpOdt\OdtCreator;

use FluentDOM\Document;
use FluentDOM\Element;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Element\ElementFactory;
use Juit\PhpOdt\OdtCreator\Element\Paragraph;
use Juit\PhpOdt\OdtCreator\HtmlParser\TextStyleConfig;
use Juit\PhpOdt\OdtCreator\Value\Color;
use Juit\PhpOdt\OdtCreator\Value\FontSize;

class HtmlParser
{
    /**
     * @var ElementFactory
     */
    private $elementFactory;

    public function __construct(ElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    /**
     * @param string $input
     * @throws \InvalidArgumentException
     * @return Paragraph[]
     */
    public function parse($input)
    {
        $paragraphNodes = $this->parseTopLevelNodes($input);
        $result         = [];

        foreach ($paragraphNodes as $node) {
            /** @var Element $node */
            if ('p' !== $node->tagName) {
                throw new \InvalidArgumentException("Unsupported top level tag '{$node->tagName}'");
            }

            $paragraph = $this->elementFactory->createParagraph();
            foreach ($node->childNodes as $childNode) {
                $this->parseNode($childNode, $paragraph);
            }

            $result[] = $paragraph;
        }

        return $result;
    }

    /**
     * @param string $input
     * @return \DOMNodeList
     */
    private function parseTopLevelNodes($input)
    {
        $document = new Document();
        $document->loadHTML('<?xml encoding="UTF-8"><!DOCTYPE html><html><body>' . $input . '</body></html>');
        $body = $document->find('//body')->item(0);

        return $body->childNodes;
    }

    /**
     * @param Text|Element $node
     * @param Paragraph $paragraph
     * @param TextStyleConfig $styleConfig
     */
    private function parseNode($node, Paragraph $paragraph, TextStyleConfig $styleConfig = null)
    {
        if (isset($node->tagName) && 'br' === $node->tagName) {
            $paragraph->createLineBreak();

            return;
        }

        if (!$styleConfig) {
            $styleConfig = new TextStyleConfig();
        }

        if ($node instanceof \FluentDOM\Text) {
            $content = $node->nodeValue;
            $content = str_replace("\xc2\xa0", ' ', $content); // Hex c2 a0 == &nbsp;
            $textElement = $paragraph->createTextElement($content);

            if ($styleConfig->isBold()) {
                $textElement->getStyle()->setBold();
            }
            if ($styleConfig->isItalic()) {
                $textElement->getStyle()->setItalic();
            }
            if ($styleConfig->isUnderline()) {
                $textElement->getStyle()->setUnderline();
            }
            if ($styleConfig->getFontSize()) {
                $textElement->getStyle()->setFontSize($styleConfig->getFontSize());
            }
            if ($styleConfig->getFontName()) {
                $textElement->getStyle()->setFontName($styleConfig->getFontName());
            }
            if ($styleConfig->getFontColor()) {
                $textElement->getStyle()->setColor($styleConfig->getFontColor());
            }

            return;
        }

        switch ($node->tagName) {
            case 'strong':
                $styleConfig = $styleConfig->setBold();
                break;
            case 'em':
                $styleConfig = $styleConfig->setItalic();
                break;
            case 'u':
                $styleConfig = $styleConfig->setUnderline();
                break;
            case 'span':
                if ($fontSize = $this->parseFontSize($node)) {
                    $styleConfig = $styleConfig->setFontSize($fontSize);
                }
                if ($fontName = $this->parseFontName($node)) {
                    $styleConfig = $styleConfig->setFontName($fontName);
                }
                if ($fontColor = $this->parseFontColor($node)) {
                    $styleConfig = $styleConfig->setFontColor($fontColor);
                }
                break;
        }

        foreach ($node->childNodes as $childNode) {
            $this->parseNode($childNode, $paragraph, $styleConfig);
        }
    }

    /**
     * @param Element $node
     * @return FontSize|null
     */
    private function parseFontSize(Element $node)
    {
        $node           = FluentDOM($node);
        $styleAttribute = $node->attr('style');

        if (!$styleAttribute) {
            return null;
        }

        $matches = [];
        if (preg_match('/\bfont-size\s?:\s?(\d+)px\b/', $styleAttribute, $matches)) {
            return new FontSize($matches[1] . 'pt');
        }

        return null;
    }

    /**
     * @param Element $node
     * @return string|null
     */
    private function parseFontName(Element $node)
    {
        $node = FluentDOM($node);
        $styleAttribute = $node->attr('style');

        if (!$styleAttribute) {
            return null;
        }

        $matches = [];
        if (preg_match('/\bfont-family\s?:\s?(\'?)(?P<fontName>.*)(\'?)\b/', $styleAttribute, $matches)) {
            return $matches['fontName'];
        }

        return null;
    }

    /**
     * @param Element $node
     * @return Color|null
     */
    private function parseFontColor(Element $node)
    {
        $node = FluentDOM($node);
        $styleAttribute = $node->attr('style');

        if (!$styleAttribute) {
            return null;
        }

        $matches = [];
        if (preg_match('/\bcolor\s?:\s?rgb\((?P<red>\d+), (?P<green>\d+), (?P<blue>\d+)\)/', $styleAttribute, $matches)) {
            $red = $matches['red'];
            $green = $matches['green'];
            $blue = $matches['blue'];

            return Color::fromRgb($red, $green, $blue);
        }

        return null;
    }
} 
