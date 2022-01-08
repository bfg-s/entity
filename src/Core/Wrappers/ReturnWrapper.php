<?php

namespace Bfg\Entity\Core\Wrappers;

/**
 * Class ReturnWrapper.
 * @package Bfg\Entity\Core\Wrappers
 */
class ReturnWrapper extends Wrapper
{
    /**
     * @param string $data
     * @return string
     */
    protected function wrap(string $data): string
    {
        return "return {$data};";
    }
}
