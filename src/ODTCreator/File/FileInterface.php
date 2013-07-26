<?php

namespace ODTCreator\File;

interface FileInterface
{
    /**
     * @return string The file content
     */
    public function render();

    /**
     * @return string The relative path of the file within the ODT structure
     */
    public function getRelativePath();
}
