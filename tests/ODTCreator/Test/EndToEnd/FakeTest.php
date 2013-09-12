<?php

namespace OdtCreator\Test\EndToEnd;

class FakeTest extends \PHPUnit_Framework_TestCase
{
    public function testRunExample()
    {
        require_once __DIR__ . '/ExampleBuilder.php';

        $outputDirInfo = new \SplFileInfo(__DIR__ . '/output');
        $builder = new ExampleBuilder($outputDirInfo);

        $builder->build();

        // To make PHPUnit collect code coverage for this "test", have a fake assertion
        // and comment the incomplete statement.
        // $this->assertTrue(true);
        $this->markTestIncomplete(
            "This does not test anything yet. You may inspect the created output at '{$outputDirInfo->getPathname()}'"
        );
    }
}
