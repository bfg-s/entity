<?php

namespace Bfg\Entity\Core\Savers\Modes;

/**
 * Class FileMode
 * @package Bfg\Entity\Core\Savers\Modes
 */
class FileMode extends Mode
{
    /**
     * @param string $data
     * @return string
     */
    public function build(string $data): string
    {
        return $data;
    }

    /**
     * @return string
     */
    public function getHavingData(): string
    {
        if (is_file($this->file)) {

            return file_get_contents($this->file);
        }

        return "";
    }

    /**
     * Insert data
     *
     * @param string $data
     * @param string $origin
     * @param string $file_data
     * @return string
     */
    protected function insert(string $data, string $origin, string $file_data): string
    {
        return $data;
    }
}
