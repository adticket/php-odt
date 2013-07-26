<?php

namespace ODTCreator\Test\EndToEnd;

use ODTCreator\ODTCreator;
use ODTCreator\Paragraph;
use ODTCreator\ParagraphContent\StyledText;
use ODTCreator\Style\TextStyle;
use ODTCreator\Value\Color;
use ODTCreator\Value\FontSize;

class HelloWorldStyledTest extends EndToEndTestCase
{
    protected function setUp()
    {
        $this->testName = 'hello_world_styled';
    }

    public function testHelloWorldStyled()
    {
        ODTCreator::resetInstance();

        $odt = ODTCreator::getInstance();

        $textStyle = new TextStyle('t1');
        $textStyle->setColor(new Color('#ff0000'));
        $textStyle->setBold();
        $textStyle->setFontSize(new FontSize(20));
        $odt->addTextStyle($textStyle);

        $p = new Paragraph();
        $p->addContent(new StyledText('Hello World!', $textStyle));
        $odt->addParagraph($p);

        $odt->save($this->getOutputFileInfo());
        exec("unzip {$this->getOutputFileInfo()->getPathname()} -d {$this->getOutputUnzipDirInfo()->getPathname()}");


        $expectedFile = $this->getFixtureDirInfo()->getPathname() . '/content.xml';
        $actualFile = $this->getOutputUnzipDirInfo()->getPathname() . '/content.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);

        // Testing the meta.xml content is in the scope of other tests
        // Because of the varying creation date, we ignore it here
        $this->assertFileExists($this->getOutputUnzipDirInfo()->getPathname() . '/meta.xml');

        $expectedFile = $this->getFixtureDirInfo()->getPathname() . '/styles.xml';
        $actualFile = $this->getOutputUnzipDirInfo()->getPathname() . '/styles.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);

        $expectedFile = $this->getFixtureDirInfo()->getPathname() . '/META-INF/manifest.xml';
        $actualFile = $this->getOutputUnzipDirInfo()->getPathname() . '/META-INF/manifest.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);
    }
}
