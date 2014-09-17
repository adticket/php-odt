<?php

namespace Juit\PhpOdt\OdtCreator\Content;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Style\TextFrameStyle;

class TextFrame implements Content
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var StyleFactory
     */
    private $styleFactory;

    /**
     * @var TextFrameStyle
     */
    private $style;

    /**
     * @param string $name
     * @param StyleFactory $styleFactory
     */
    public function __construct($name, StyleFactory $styleFactory)
    {
        $this->name = $name;
        $this->styleFactory = $styleFactory;
        $this->style = $styleFactory->createTextFrameStyle();
    }

    /**
     * @return TextFrameStyle
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param DOMDocument $content
     * @param DOMElement $parent
     * @return void
     */
    public function renderTo(DOMDocument $content, DOMElement $parent)
    {
        $frame = $content->createElement('draw:frame');
        $parent->appendChild($frame);
        $frame->setAttribute('draw:style-name', $this->style->getStyleName());
        $frame->setAttribute('draw:name', $this->name);
        $frame->setAttribute('text:anchor-type', 'paragraph');
        $frame->setAttribute('svg:width', '5cm'); // TODO
        $frame->setAttribute('svg:height', '5cm'); // TODO
        $frame->setAttribute('draw:z-index', '0');

        $textBox = $content->createElement('draw:text-box');
        $frame->appendChild($textBox);

        $text = $content->createElement('text:p');
        $textBox->appendChild($text);
        $text->setAttribute('text:style-name', "Frame_20_contents");
    }
}
