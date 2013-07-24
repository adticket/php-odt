<?php

namespace ODT\Style;

use ODT\Common;
use ODT\ListLevelProp;

class ListStyle extends ContentAutoStyle
{
    const INDENT = 0.5;
    const LABEL_DISTANCE = 0.5;

    /**
     * @var \DOMElement[]
     */
    private $levels;

    public function __construct($name)
    {
        parent::__construct($name, 'text:list-style');
        $this->levels = array();
    }

    /**
     * Specifies that the list in this level is a numbered list.
     *
     * @param integer $level
     * @param null $numFormat
     * @param null $textstyle
     * @throws StyleException
     */
    function setNumberLevel($level, $numFormat = null, $textstyle = null)
    {
        if (Common::isNumeric($level, true)) {
            $this->levels[$level] = $this->contentDocument->createElement('text:list-level-style-number');
            $this->levels[$level]->setAttribute('text:level', $level);
            if ($numFormat != null) {
                $this->levels[$level]->setAttribute('style:num-format', $numFormat->getFormat());
                $this->levels[$level]->setAttribute('style:num-prefix', $numFormat->getPrefix());
                $this->levels[$level]->setAttribute('style:num-suffix', $numFormat->getSuffix());
            }
            if ($textstyle != null) {
                $this->levels[$level]->setAttribute('text:style-name', $textstyle->getStyleName());
            }

            $this->styleElement->appendChild($this->levels[$level]);

            $listLevelProp = new ListLevelProp();
            $listLevelProp->setIndent(($level * self::INDENT) . 'cm');
            $listLevelProp->setMinLabelDistance(self::LABEL_DISTANCE . 'cm');
            $this->setLevelProp($level, $listLevelProp);
        } else {
            throw new StyleException('Invalid level value');
        }
    }

    /**
     * Specifies that the list in this level is a bullet list.
     *
     * @param integer $level
     * @param string $bulletChar The character to use as the bullet, may be StyleConstants::(BULLET|BLACK_CIRCLE|CHECK_MARK|RIGHT_ARROW|RIGHT_ARROWHEAD)
     * @param string $prefix The characters to add before the bullet character.
     * @param string $suffix The characters to add behind the bullet character.
     * @param TextStyle|null $textstyle The style to use to format the list bullet.
     * @throws StyleException
     */
    function setBulletLevel($level, $bulletChar = StyleConstants::BULLET, $prefix = '', $suffix = '',
                            $textstyle = null)
    {
        if (Common::isNumeric($level, true)) {
            $this->levels[$level] = $this->contentDocument->createElement('text:list-level-style-bullet');
            $this->levels[$level]->setAttribute('text:level', $level);
            switch ($bulletChar) {
                case StyleConstants::BULLET:
                case StyleConstants::BLACK_CIRCLE:
                case StyleConstants::CHECK_MARK:
                case StyleConstants::RIGHT_ARROW:
                case StyleConstants::RIGHT_ARROWHEAD:
                    $this->levels[$level]->setAttribute('text:bullet-char', $bulletChar);
                    break;
                default:
                    throw new StyleException('Invalid bullet character value');
            }
            if ($textstyle != null) {
                $this->levels[$level]->setAttribute('text:style-name', $textstyle->getStyleName());
            }
            $this->levels[$level]->setAttribute('style:num-prefix', $prefix);
            $this->levels[$level]->setAttribute('style:num-suffix', $suffix);

            $this->styleElement->appendChild($this->levels[$level]);

            $listLevelProp = new ListLevelProp();
            $listLevelProp->setIndent(($level * self::INDENT) . 'cm');
            $listLevelProp->setMinLabelDistance(self::LABEL_DISTANCE . 'cm');
            $this->setLevelProp($level, $listLevelProp);
        } else {
            throw new StyleException('Invalid level value');
        }
    }

    /**
     * The list items in this level will be preceded by images.
     *
     * @param integer $level
     * @param string $image The image's location.
     * @param string $width
     * @param string $height
     * @throws StyleException
     */
    function setImageLevel($level, $image, $width = '.5cm', $height = '.5cm')
    {
        if (Common::isNumeric($level, true)) {
            $this->levels[$level] = $this->contentDocument->createElement('text:list-level-style-image');
            $this->levels[$level]->setAttribute('text:level', $level);
            $file = fopen($image, 'r');
            if (!$file) {
                throw new StyleException('Failed to open image file');
            }
            $dataImg = fread($file, filesize($image));
            $dateImgB64 = base64_encode($dataImg);
            fclose($file);
            $binaryElement = $this->contentDocument->createElement('office:binary-data', $dateImgB64);
            $this->levels[$level]->appendChild($binaryElement);

            $this->styleElement->appendChild($this->levels[$level]);

            $listLevelProp = new ListLevelProp();
            $listLevelProp->setIndent(($level * self::INDENT) . 'cm');
            $listLevelProp->setMinLabelDistance(self::LABEL_DISTANCE . 'cm');
            $listLevelProp->setImageWidth($width);
            $listLevelProp->setImageHeight($height);
            $this->setLevelProp($level, $listLevelProp);
        } else {
            throw new StyleException('Invalid level value');
        }
    }

    /**
     * Set some properties of the list level specified.
     *
     * @param integer $level
     * @param {@link ListLevelProp.html ListLevelProp} $levelProp
     */
    function setLevelProp($level, $levelProp)
    {
        if (Common::isNumeric($level, true)) {
            $element = $this->levels[$level];
            $prop = $this->contentDocument->createElement('style:list-level-properties');

            if ($levelProp->getAlign() != null) {
                $prop->setAttribute('fo:text-align', $levelProp->getAlign());
            }

            if ($levelProp->getIndent() == null) {
                $indent = ($level * 0.5) . 'cm';
            } else {
                $indent = $levelProp->getIndent();
            }
            $prop->setAttribute('text:space-before', $indent);

            if ($levelProp->getMinLabelWidth() != null) {
                $prop->setAttribute('text:min-label-width', $levelProp->getMinLabelWidth());
            }

            if ($levelProp->getMinLabelDistance() != null) {
                $prop->setAttribute('text:min-label-distance', $levelProp->getMinLabelDistance());
            }
            if ($levelProp->getVAlign() != null) {
                $prop->setAttribute('style:vertical-pos', $levelProp->getVAlign());
            }
            if ($levelProp->getImageWidth() != null) {
                $prop->setAttribute('fo:width', $levelProp->getImageWidth());
            }
            if ($levelProp->getImageHeight() != null) {
                $prop->setAttribute('fo:height', $levelProp->getImageHeight());
            }
            $element->appendChild($prop);
        }
    }
}
