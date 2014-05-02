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
        $this->baseDir = "/var/www/tests/odtcreator_tests";
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
        $expectedPath = "{$this->baseDir}/example_expected_page_%03d.pdf";
        $this->runShellCommand("cd {$this->baseDir} && pdftk $fixturePath burst output $expectedPath");

        $outputPath = __DIR__ . '/output/example.pdf';
        $this->runShellCommand("cd {$this->baseDir} && pdftk $outputPath burst output {$this->baseDir}/example_actual_page_%03d.pdf");

        $command = "compare ";
        $command .= "{$this->baseDir}/example_expected_page_001.pdf ";
        $command .= "{$this->baseDir}/example_actual_page_001.pdf ";
        $command .= "-compose src {$this->baseDir}/example_diff_page_001.pdf ";
        $this->runShellCommand($command);

        $command = "gs ";
        $command .= "-o {$this->baseDir}/example_diff_page_001.bmp ";
        $command .= "-r72 ";
        $command .= "-g595x842 ";
        $command .= "-sDEVICE=bmp256 ";
        $command .= "{$this->baseDir}/example_diff_page_001.pdf ";
        $this->runShellCommand($command);

        $result = $this->runShellCommand("md5sum {$this->baseDir}/example_diff_page_001.bmp");
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
