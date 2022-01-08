<?php

namespace Bfg\Entity\Core\Savers;

/**
 * Class File.
 * @package Bfg\Entity\Core\Savers
 */
class File extends Driver
{
    /**
     * Save method.
     * @return int
     */
    public function save(): int
    {
        return file_put_contents($this->file, $this->getData());
    }
}
