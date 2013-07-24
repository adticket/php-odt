<?php

namespace ODT\Tests\EndToEnd;

use ODT\ODT;
use ODT\Paragraph;
use ODT\Style\TextStyle;

class HelloWorldStyledTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $testName;

    protected function setUp()
    {
        $this->testName = 'hello_world_styled';

        mkdir($this->getOutputUnzipPath());
    }

    public function testHelloWorldStyled()
    {
        $odt = ODT::getInstance();
        // This is the date that is stored in the meta.xml against which we compare
        $odt->setCreationDate(new \DateTime('2013-01-01 12:00:00'));


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

    protected function tearDown()
    {
        unlink($this->getOutputFilePath());
        exec("rm -fr " . $this->getOutputUnzipPath());
    }

    /**
     * @return string
     */
    private function getOutputPath()
    {
        return __DIR__ . '/output';
    }

    /**
     * @return string
     */
    private function getOutputFilePath()
    {
        return $this->getOutputPath() . '/' . $this->testName . '.odt';
    }

    /**
     * @return string
     */
    private function getOutputUnzipPath()
    {
        return $this->getOutputPath() . '/' . $this->testName;
    }

    /**
     * @return string
     */
    private function getFixturePath()
    {
        return __DIR__ . '/expected_output/' . $this->testName;
    }
}
