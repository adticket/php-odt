<?php

namespace Juit\PhpOdt\OdtCreator\Element;

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
     * @return Paragraph
     */
    public function createParagraph()
    {
        $paragraph = new Paragraph($this->styleFactory);

        if (count($this->paragraphs) === 0) {
            $paragraph->getStyle()->setMasterPageName('First_20_Page');
        }

        $this->paragraphs[] = $paragraph;

        return $paragraph;
    }

    public function createFrame(Length $xCoordinate, Length $yCoordinate, Length $width, Length $height)
    {
        $frame = new Frame(
            $this->styleFactory->createGraphicStyle(), $this->styleFactory, $xCoordinate, $yCoordinate, $width, $height
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
