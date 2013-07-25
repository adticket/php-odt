<?php

namespace ODTCreator\Test\EndToEnd;

use ODTCreator\ODTCreator;
use ODTCreator\Paragraph;

class HelloWorldTest extends EndToEndTestCase
{
    protected function setUp()
    {
        $this->testName = 'hello_world';
    }

    public function testHelloWorld()
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

        $p = new Paragraph();
        $p->addText('Hello World!');

        $odt->save($this->getOutputFileInfo());
        exec("unzip {$this->getOutputFileInfo()->getPathname()} -d {$this->getOutputUnzipDirInfo()->getPathname()}");


        $expectedFile = $this->getFixtureDirInfo()->getPathname() . '/content.xml';
        $actualFile = $this->getOutputUnzipDirInfo()->getPathname() . '/content.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);

        $expectedFile = $this->getFixtureDirInfo()->getPathname() . '/meta.xml';
        $actualFile = $this->getOutputUnzipDirInfo()->getPathname() . '/meta.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);

        $expectedFile = $this->getFixtureDirInfo()->getPathname() . '/styles.xml';
        $actualFile = $this->getOutputUnzipDirInfo()->getPathname() . '/styles.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);

        $expectedFile = $this->getFixtureDirInfo()->getPathname() . '/META-INF/manifest.xml';
        $actualFile = $this->getOutputUnzipDirInfo()->getPathname() . '/META-INF/manifest.xml';
        $this->assertXmlFileEqualsXmlFile($expectedFile, $actualFile);
    }
}
