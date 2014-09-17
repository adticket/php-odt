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

    public function __construct(StyleFactory $styleFactory)
    {
        $this->styleFactory = $styleFactory;
    }

    /**
     * @return Paragraph
     */
    public function createParagraph()
    {
        return new Paragraph($this->styleFactory);
    }

    public function createFrame(Length $xCoordinate, Length $yCoordinate, Length $width, Length $height)
    {
        return new Frame(
            $this->styleFactory->createGraphicStyle(), $this->styleFactory, $xCoordinate, $yCoordinate, $width, $height
        );
    }
}
