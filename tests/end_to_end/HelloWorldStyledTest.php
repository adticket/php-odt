<?php

namespace ODTCreator\Tests\EndToEnd;

use ODTCreator\ODTCreator;
use ODTCreator\Paragraph;
use ODTCreator\Style\TextStyle;

require_once __DIR__ . '/EndToEndTestCase.php';

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

        // Hack: Inject the date that is stored in the meta.xml against which we compare
        // This is neccessary to get a meta.xml with a predictable date for testing
        $reflectionClass = new \ReflectionClass('ODTCreator\ODTCreator');
        $creationDateProperty = $reflectionClass->getProperty('creationDate');
        $creationDateProperty->setAccessible(true);
        $creationDateProperty->setValue($odt, new \DateTime('2013-01-01 12:00:00'));


        $odt->setCreator('Tom Tester');
        $odt->setTitle('My Title');
        $odt->setDescription('Some description here');
        $odt->setSubject('My Subject');
        $odt->setKeywords(array('My first keyword', 'My second keyword'));

        $textStyle = new TextStyle('t1');
        $textStyle->setColor('#ff0000');
        $textStyle->setBold();
        $textStyle->setFontSize(20);

        $p = new Paragraph();
        $p->addText('Hello World!', $textStyle);

        $odt->output($this->getOutputFilePath());
        exec("unzip {$this->getOutputFilePath()} -d {$this->getOutputUnzipPath()}");


        $expectedFile = $this->getFixturePath() . '/content.xml';
        $actualFile = $this->getOutputUnzipPath() . '/content.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);

        $expectedFile = $this->getFixturePath() . '/meta.xml';
        $actualFile = $this->getOutputUnzipPath() . '/meta.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);

        $expectedFile = $this->getFixturePath() . '/styles.xml';
        $actualFile = $this->getOutputUnzipPath() . '/styles.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);

        $expectedFile = $this->getFixturePath() . '/META-INF/manifest.xml';
        $actualFile = $this->getOutputUnzipPath() . '/META-INF/manifest.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);
    }
}
