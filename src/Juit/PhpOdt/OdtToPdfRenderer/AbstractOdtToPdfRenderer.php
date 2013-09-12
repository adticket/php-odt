<?php

namespace Juit\PhpOdt\OdtToPdfRenderer;

use Juit\ShellCommandExecutor\Result;
use Juit\ShellCommandExecutor\ShellCommandExecutor;

abstract class AbstractOdtToPdfRenderer
{
    /**
     * @param \SplFileInfo $odtFileInfo The input file
     * @param \SplFileInfo $pdfFileInfo The output file
     */
    public function render(\SplFileInfo $odtFileInfo, \SplFileInfo $pdfFileInfo)
    {
        $shellCommand = $this->createShellCommand($odtFileInfo);

        $shellCommandExecutor = new ShellCommandExecutor();
        $result = $shellCommandExecutor->execute($shellCommand);

        $this->assertIsValid($result);

        // TODO: This is just a dev experiment
//        $out = new \SplFileInfo($pdfFileInfo->getPath() . '/with_background.pdf');
//        $cmd = "/usr/bin/pdftk {$pdfFileInfo->getPathname()}"
//            . " multibackground /var/www/app/doc/Briefbogen.pdf"
//            . " output {$out->getPathname()}";
//        exec($cmd);
//        unlink($pdfFileInfo->getPathname());
//        rename($out->getPathname(), $pdfFileInfo->getPathname());
    }

    /**
     * @param \SplFileInfo $odtFile
     * @return string
     */
    abstract protected function createShellCommand(\SplFileInfo $odtFile);

    /**
     * @param Result $result
     * @throws \RuntimeException
     */
    protected function assertIsValid(Result $result)
    {
        $isReturnValueError = ($result->getReturnVar() !== 0);
        $hasUnexpectedErrorOutput = !$this->isExpectedOutput($result->getErrors(), $this->getErrorWhiteListRegexes());
        $hasUnexpectedOutput = !$this->isExpectedOutput($result->getOutput(), $this->getOutputWhiteListRegexes());

        if ($isReturnValueError || $hasUnexpectedErrorOutput || $hasUnexpectedOutput) {
            $message = "Unexpected result ({$result->getReturnVar()}) from LibreOffice: ";
            $message .= implode("| ", $result->getOutput());
            $message .= ' | ' . implode(" | ", $result->getErrors());
            throw new \RuntimeException($message);
        }
    }

    /**
     * @param array $lines
     * @param array $whiteListRegexes
     * @return bool
     */
    protected function isExpectedOutput(array $lines, array $whiteListRegexes)
    {
        $isExpectedOutput = true;

        foreach ($lines as $line) {
            if (!$this->isExpectedOutputLine($line, $whiteListRegexes)) {
                $isExpectedOutput = false;
            }
        }

        return $isExpectedOutput;
    }

    /**
     * @param string $line
     * @param array $whiteListRegexes
     * @return bool
     */
    protected function isExpectedOutputLine($line, array $whiteListRegexes)
    {
        foreach ($whiteListRegexes as $regex) {
            if (preg_match($regex, $line)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    abstract protected function getOutputWhiteListRegexes();

    /**
     * @return array
     */
    abstract protected function getErrorWhiteListRegexes();
}
