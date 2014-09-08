<?php

namespace Juit\PhpOdt\OdtCreator;

use FluentDOM\Document;
use FluentDOM\Element;
use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Element\Paragraph;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;

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
     * @param array $styles
     */
    private function parseNode($node, Paragraph $paragraph, array $styles = [])
    {
        if (isset($node->tagName) && 'br' === $node->tagName) {
            $paragraph->addContent(new LineBreak());

            return;
        }

        if ($node instanceof \FluentDOM\Text) {
            $style = $this->styleFactory->createTextStyle();
            if (isset($styles['bold'])) {
                $style->setBold();
            }
            if (isset($styles['italic'])) {
                $style->setItalic();
            }

            $paragraph->addContent(new Text($node->nodeValue, $style));

            return;
        }

        switch ($node->tagName) {
            case 'strong':
                $styles['bold'] = true;
                break;
            case 'em':
                $styles['italic'] = true;
                break;
        }

        foreach ($node->childNodes as $childNode) {
            $this->parseNode($childNode, $paragraph, $styles);
        }
    }
} 
