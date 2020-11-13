<?php

namespace Bfg\Entity\Core\Savers\Modes;

/**
 * Class FunctionAbstractMode
 * @package Bfg\Entity\Core\Savers\Modes
 */
class FunctionAbstractMode extends Mode
{
    /**
     * @param string $data
     * @return string
     */
    public function build(string $data): string
    {
        return $data;
    }

    public function getHavingData(): string
    {
        // TODO: Implement getHavingData() method.
    }

    protected function insert(string $data, string $origin, string $file_data): string
    {
        // TODO: Implement insert() method.
    }
}
