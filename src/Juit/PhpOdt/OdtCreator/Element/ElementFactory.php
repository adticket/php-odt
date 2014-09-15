<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use Juit\PhpOdt\OdtCreator\Style\ParagraphStyle;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Value\Length;

class ElementFactory
{
    /**
     * @var StyleFactory
     */
    private $styleFactory;

    /**
     * @var Paragraph[]
     */
    private $paragraphs = [];

    /**
     * @var Frame[]
     */
    private $frames = [];

    public function __construct(StyleFactory $styleFactory)
    {
        $this->styleFactory = $styleFactory;
    }

    /**
     * @param ParagraphStyle $style
     * @return Paragraph
     */
    public function createParagraph(ParagraphStyle $style = null)
    {
        if (null === $style) {
            $style = $this->styleFactory->createParagraphStyle();
        }

        if (count($this->paragraphs) === 0) {
            $style->setMasterPageName('First_20_Page');
        }

        $paragraph = new Paragraph($style);
        $this->paragraphs[] = $paragraph;

        return $paragraph;
    }

    public function createFrame(Length $xCoordinate, Length $yCoordinate, Length $width, Length $height)
    {
        $frame = new Frame(
            $this->styleFactory->createGraphicStyle(),
            $xCoordinate,
            $yCoordinate,
            $width,
            $height
        );
        $this->frames[] = $frame;

        return $frame;
    }

    /**
     * @return Element[]
     */
    public function getElements()
    {
        return array_merge($this->frames, $this->paragraphs);
    }
} 
