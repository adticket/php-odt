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
        $result = [];

        foreach ($paragraphNodes as $node) {
            /** @var Element $node */
            if ('p' !== $node->tagName) {
                throw new \InvalidArgumentException("Unsupported top level tag '{$node->tagName}'");
            }

            $paragraph = new Paragraph();

            foreach ($node->childNodes as $childNode) {
                if ($childNode instanceof \FluentDOM\Text) {
                    $paragraph->addContent(new Text($childNode->nodeValue));
                } elseif ($childNode instanceof Element) {
                    switch ($childNode->tagName) {
                        case 'strong':
                            $style = $this->styleFactory->createTextStyle();
                            $style->setBold();
                            $paragraph->addContent(new Text($childNode->nodeValue, $style));
                            break;
                        case 'em':
                            $style = $this->styleFactory->createTextStyle();
                            $style->setItalic();
                            $paragraph->addContent(new Text($childNode->nodeValue, $style));
                            break;
                        case 'br':
                            $paragraph->addContent(new LineBreak());
                            break;
                    }
                }
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
} 
