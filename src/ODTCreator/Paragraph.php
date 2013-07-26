<?php

namespace ODTCreator;

use ODTCreator\ParagraphContent\ParagraphContent;
use ODTCreator\ParagraphContent\Text;
use ODTCreator\Style\ParagraphStyle;
use ODTCreator\Style\RubyStyle;
use ODTCreator\Style\StyleConstants;
use ODTCreator\Style\TextStyle;

class Paragraph
{
    /**
     * @var Style\ParagraphStyle
     */
    private $style;

    /**
     * @var ParagraphContent[]
     */
    private $contents = array();

    /**
     * @var \DOMDocument
     */
    private $documentContent;

    /**
     * @var \DOMElement
     */
    private $pElement;

    public function __construct(ParagraphStyle $style = null)
    {
        $this->style = $style;
    }

    /**
     * @param ParagraphContent $content
     */
    public function addContent(ParagraphContent $content)
    {
        $this->contents[] = $content;
    }

    /**
     * Add an hyperlink
     * @param string $text The text that will be displayed
     * @param string $url The URL for the target location of the link
     * @param string $title A short accessible description for hint text
     */
    public function addHyperlink($text, $url, $title = '')
    {
        // TODO: Refactor elements to separate classes
        $link = $this->documentContent->createElement('text:a', $text);
        $link->setAttribute('office:title', $title);
        $link->setAttribute('xlink:href', $url);
        $this->pElement->appendChild($link);
    }

    /**
     * Add an image to the pararaph.
     * @param string $image The path to the image
     * @param string $width The width of the image (not in pixels)
     * @param string $height The height of the image (not in pixels)
     * @throws ODTException
     */
    public function addImage($image, $width, $height)
    {
        $file = fopen($image, 'r');
        if (!$file) {
            throw new ODTException('Cannot open image');
        }
        $dataImg = fread($file, filesize($image));
        $dateImgB64 = base64_encode($dataImg);
        fclose($file);
        $binaryElement = $this->documentContent->createElement('office:binary-data', $dateImgB64);
        $drawImage = $this->documentContent->createElement('draw:image');
        $drawImage->setAttribute('svg:width', $width);
        $drawImage->setAttribute('svg:height', $height);
        $drawImage->setAttribute('text:anchor-type', 'as-char');
        $drawImage->appendChild($binaryElement);
        $drawFrame = $this->documentContent->createElement('draw:frame');
        $drawFrame->appendChild($drawImage);
        $this->pElement->appendChild($drawFrame);
    }

    /**
     * Add a line break
     */

    public function addLineBreak()
    {
        $this->pElement->appendChild($this->documentContent->createElement('text:line-break'));
    }

    /**
     * Create a bookmark
     *
     * @param string $name The name of the bookmark
     */

    public function addBookmark($name)
    {
        $bookmark = $this->documentContent->createElement('text:bookmark');
        $bookmark->setAttribute('text:name', $name);
        $this->pElement->appendChild($bookmark);
    }

    /**
     * Create a reference to a bookmark
     * @param string $name The name of the bookmark to reference
     * @param string $refText The text to display for the reference
     */

    public function addBookmarkRef($name, $refText)
    {
        $ref = $this->documentContent->createElement('text:bookmark-ref');
        $ref->setAttribute('text:ref-name', $name);
        $ref->setAttribute('text:reference-format', 'text');
        $ref->appendChild($this->documentContent->createTextNode($refText));
        $this->pElement->appendChild($ref);
    }

    /**
     * A note represents text notes which are attached to a certain text position.
     * A common implementation of this concept are the footnotes and endnotes found in most word processors.
     *
     * @param string $body The note's content
     * @param string $noteClass The type of the note, either FOOTNOTE (In the footer of the page), or ENDNOTE(The end of the document)
     */

    public function addNote($body, $noteClass = StyleConstants::FOOTNOTE)
    {
        $note = $this->documentContent->createElement('text:note');
//		if ($citation != null) {
//			$note_citation = $this->documentContent->createElement('text:note-citation');
//			$note_citation->setAttribute('text:label', $citation);
//			$note->appendChild($note_citation);
//		}
        $note_body = $this->documentContent->createElement('text:note-body');
        $p = new Paragraph(null, FALSE);
        $p->addContent($body);
        $note_body->appendChild($p->getDOMElement());
        $note->appendChild($note_body);
        switch ($noteClass) {
            case StyleConstants::FOOTNOTE:
                $noteClass = 'footnote';
                break;
            case StyleConstants::ENDNOTE:
                $noteClass = 'endnote';
                break;
        }
        $note->setAttribute('text:note-class', $noteClass);
        $this->pElement->appendChild($note);
    }

    /**
     * A ruby is additional text that is displayed above or below some base text.
     * The purpose of ruby is to annotate the base text or provide information about its pronunciation.
     *
     * @param string $base The text that will be annotated
     * @param string $text The annotation text
     * @param TextStyle $textRubyStyle The style to apply to the annotation text
     * @param RubyStyle $rubyStyle The style to apply to this ruby
     */

    function addRuby($base, $text, $textRubyStyle = null, $rubyStyle = null)
    {
        $ruby = $this->documentContent->createElement('text:ruby');
        $ruby_base = $this->documentContent->createElement('text:ruby-base');
        $ruby_base->appendChild($this->documentContent->createTextNode($base));
        $ruby->appendChild($ruby_base);
        $ruby_text = $this->documentContent->createElement('text:ruby-text');
        if ($textRubyStyle != null) {
            $ruby_text->setAttribute('text:style-name', $textRubyStyle->getStyleName());
        }
        $ruby_text->appendChild($this->documentContent->createTextNode($text));
        $ruby->appendChild($ruby_text);
        if ($rubyStyle != null) {
            $ruby->setAttribute('text:style-name', $rubyStyle->getStyleName());
        }
        $this->pElement->appendChild($ruby);
    }

    /**
     * Get the DOMElement representing this paragraph
     * @return \DOMElement
     */
    public function getDOMElement()
    {
        return $this->pElement;
    }

    public function appendTo(\DOMDocument $domDocument)
    {
        $domElement = $domDocument->createElement('text:p');
        if ($this->style != null) {
            $domElement->setAttribute('text:style-name', $this->style->getStyleName());
        }

        foreach ($this->contents as $text) {
            $text->appendTo($domElement, $domDocument);
        }

        $domDocument->getElementsByTagName('office:text')->item(0)->appendChild($domElement);
    }
}

