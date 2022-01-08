<?php

namespace Bfg\Entity\Core\Savers\Modes;

/**
 * Class ClassMode.
 * @package Bfg\Entity\Core\Savers\Modes
 */
class ClassMode extends Mode
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
        if ($this->ref && $this->file && is_file($this->file)) {
            $file_data = file_get_contents($this->file);

            if (preg_match('/.*[class|interface|trait|abstract\sclass|final\sclass].*\{(.*?)\}/isxU', $file_data, $m)) {
                return empty($m[1]) ? str_repeat(' ', 4) : $m[1];
            }

            return $file_data;
        }
    }

    /**
     * Insert data.
     *
     * @param string $data
     * @param string $origin
     * @param string $file_data
     * @return string
     */
    protected function insert(string $data, string $origin, string $file_data): string
    {
        $data = trim($data, "\n");

        return preg_replace('/(.*[class|interface|trait|abstract\sclass|final\sclass].*)\{(.*?)\}/isxU', "$1{\n{$data}\n}", $file_data);
    }
}
