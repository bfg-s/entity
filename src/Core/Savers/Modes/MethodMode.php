<?php

namespace Bfg\Entity\Core\Savers\Modes;

/**
 * Class MethodMode.
 * @package Bfg\Entity\Core\Savers\Modes
 */
class MethodMode extends Mode
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
    }

    protected function insert(string $data, string $origin, string $file_data): string
    {
    }
}
