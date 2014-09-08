<?php

namespace Juit\PhpOdt\OdtCreator;

use FluentDOM\Document;
use FluentDOM\Element;
use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Element\Paragraph;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Style\TextStyle;

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
        $result = [];

        foreach ($paragraphNodes as $node) {
            /** @var Element $node */
            if ('p' !== $node->tagName) {
                throw new \InvalidArgumentException("Unsupported top level tag '{$node->tagName}'");
            }

            $paragraph = new Paragraph();
            foreach ($node->childNodes as $childNode) {
                $this->parseChildNode($childNode, $paragraph);
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
     * @param Style\TextStyle $style
     */
    private function parseChildNode($node, Paragraph $paragraph, TextStyle $style = null)
    {
        if ($node instanceof \FluentDOM\Text) {
            $paragraph->addContent(new Text($node->nodeValue, $style));

            return;
        }

        if ('br' === $node->tagName) {
            $paragraph->addContent(new LineBreak());

            return;
        }

        if (!$style) {
            $style = $this->styleFactory->createTextStyle();
        }
        switch ($node->tagName) {
            case 'strong':
                $style->setBold();
                break;
            case 'em':
                $style->setItalic();
                break;
        }

        foreach ($node->childNodes as $childNode) {
            $this->parseChildNode($childNode, $paragraph, $style);
        }
    }
} 
