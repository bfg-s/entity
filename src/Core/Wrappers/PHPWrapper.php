<?php

namespace Bfg\Entity\Core\Wrappers;

/**
 * Class PHPWrapper
 * @package Bfg\Entity\Core\Wrappers
 */
class PHPWrapper extends Wrapper {

    /**
     * @param string $data
     * @return string
     */
    protected function wrap(string $data): string
    {
        return "<?php\n\n{$data}";
    }
}
