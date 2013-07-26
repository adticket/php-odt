<?php

namespace ODTCreator\Test\EndToEnd;

use ODTCreator\ODTCreator;
use ODTCreator\Paragraph;
use ODTCreator\ParagraphContent\Text;
use ODTCreator\Test\Unit\ODTCreator\File\MetaMock;

class HelloWorldWithMetaTest extends EndToEndTestCase
{
    protected function setUp()
    {
        $this->testName = 'hello_world_with_meta';
    }

    public function testHelloWorld()
    {
        ODTCreator::resetInstance();

        $odt = ODTCreator::getInstance();

        $meta = new MetaMock();
        // Inject the date that is stored in the meta.xml against which we compare
        // This is neccessary to get a meta.xml with a predictable date for testing
        $meta->setCreationDate(new \DateTime('2013-01-01 12:00:00'));
        $meta->setCreator('Tom Tester');
        $meta->setTitle('My Title');
        $meta->setDescription('Some description here');
        $meta->setSubject('My Subject');
        $meta->setKeywords(array('My first keyword', 'My second keyword'));
        $odt->setMeta($meta);

        $p = new Paragraph();
        $p->addText(new Text('Hello World!'));
        $odt->addParagraph($p);

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
