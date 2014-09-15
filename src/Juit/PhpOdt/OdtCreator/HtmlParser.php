<?php

namespace Juit\PhpOdt\OdtCreator;

use FluentDOM\Document;
use FluentDOM\Element;
use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Element\Paragraph;
use Juit\PhpOdt\OdtCreator\HtmlParser\TextStyleConfig;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Value\FontSize;

class HtmlParser
{
    /**
     * @var StyleFactory
     */
    private $styleFactory;

    public function __construct(StyleFactory $styleFactory)
    {
        $this->styleFactory = $styleFactory;
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

            $paragraph = new Paragraph();
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
            $style = $this->styleFactory->createTextStyle();
            if ($styleConfig->isBold()) {
                $style->setBold();
            }
            if ($styleConfig->isItalic()) {
                $style->setItalic();
            }
            if ($styleConfig->isUnderline()) {
                $style->setUnderline();
            }
            if ($styleConfig->getFontSize()) {
                $style->setFontSize($styleConfig->getFontSize());
            }
            if ($styleConfig->getFontName()) {
                $style->setFontName($styleConfig->getFontName());
            }

            $content = $node->nodeValue;
            $content = str_replace("\xc2\xa0", ' ', $content); // Hex c2 a0 == &nbsp;
            $paragraph->createTextElement($content, $style);

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
} 
