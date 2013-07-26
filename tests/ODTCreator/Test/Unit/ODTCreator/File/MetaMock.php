<?php

namespace ODTCreator\Test\Unit\ODTCreator\File;

use ODTCreator\File\Meta;

class MetaMock extends Meta
{
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
    }
}
