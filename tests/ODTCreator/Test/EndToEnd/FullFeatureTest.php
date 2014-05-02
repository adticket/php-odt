<?php

namespace OdtCreator\Test\EndToEnd;

use RuntimeException;
use SplFileInfo;
use Symfony\Component\Process\Process;

class FullFeatureTest extends \PHPUnit_Framework_TestCase
{
    private $baseDir;

    protected function setUp()
    {
        $this->baseDir = ROOT_DIR . "/tmp/odtcreator_tests";
        $this->runShellCommand("rm -fr {$this->baseDir} && mkdir {$this->baseDir}");
    }

    protected function tearDown()
    {
        $this->runShellCommand("rm -fr {$this->baseDir}");
    }

    public function testRunExample()
    {
        $builder = new ExampleBuilder(new SplFileInfo(__DIR__ . '/output'));
        $builder->build();

        $fixturePath = __DIR__ . '/fixtures/example.pdf';
        $expectedPath = "{$this->baseDir}/expected_%02d.pdf";
        $this->runShellCommand("cd {$this->baseDir} && pdftk {$fixturePath} burst output {$expectedPath}");

        $outputPath = __DIR__ . '/output/example.pdf';
        $actualPath = "{$this->baseDir}/actual_%02d.pdf";
        $this->runShellCommand("cd {$this->baseDir} && pdftk {$outputPath} burst output {$actualPath}");

        $expected = "{$this->baseDir}/expected_01.pdf";
        $actual = "{$this->baseDir}/actual_01.pdf";
        $diff = "{$this->baseDir}/diff_01.pdf";
        $diffImg = "{$this->baseDir}/diff_01.bmp";
        $this->runShellCommand("compare {$expected} {$actual} -compose src {$diff}");
        $this->runShellCommand("gs -o {$diffImg} -r72 -g595x842 -sDEVICE=bmp256 {$diff}");

        $result = $this->runShellCommand("md5sum {$diffImg}");
        if (!preg_match('/^(?P<hash>\S+) /', $result, $matches)) {
            $this->fail('Could not get hash of diff image');
        }
        $md5OfDiff = $matches['hash'];
        $this->assertEquals(
            '74ab373396b8c6dd4ca9322fd6edae66',
            $md5OfDiff,
            'Unexpected hash of diff image, PDFs seem to be different'
        );
    }

    /**
     * @param string $command
     * @return string
     * @throws RuntimeException
     */
    private function runShellCommand($command)
    {
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
