<?php

namespace OdtToPdfRenderer;

use ShellCommandExecutor\Result;
use ShellCommandExecutor\ShellCommandExecutor;

abstract class AbstractOdtToPdfRenderer
{
    /**
     * @param \SplFileInfo $odtFile
     * @throws \RuntimeException
     * @return \SplFileInfo Info about the generated PDF file
     */
    public function render(\SplFileInfo $odtFile)
    {
        $shellCommand = $this->createShellCommand($odtFile);

        $shellCommandExecutor = new ShellCommandExecutor();
        $result = $shellCommandExecutor->execute($shellCommand);

        $this->assertIsValid($result);

        return new \SplFileInfo($odtFile->getPath() . '/' . $odtFile->getBasename('.odt') . '.pdf');
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
