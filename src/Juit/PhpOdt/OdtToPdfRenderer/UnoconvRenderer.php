<?php

namespace Juit\PhpOdt\OdtToPdfRenderer;

class UnoconvRenderer extends AbstractOdtToPdfRenderer
{
    /**
     * @param \SplFileInfo $odtFile
     * @return string
     */
    protected function createShellCommand(\SplFileInfo $odtFile)
    {
        return '/usr/bin/unoconv -f pdf ' . $odtFile->getPathname();
    }

    /**
     * @return array
     */
    protected function getOutputWhiteListRegexes()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function getErrorWhiteListRegexes()
    {
        return array(
            '/^Warning: -.* is deprecated\./'
        );
    }
}
