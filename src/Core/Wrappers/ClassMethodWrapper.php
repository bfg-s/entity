<?php

namespace Bfg\Entity\Core\Wrappers;

use Bfg\Entity\Core\Entities\ClassMethodEntity;

/**
 * Class ClassMethodWrapper
 * @package Bfg\Entity\Core\Wrappers
 */
class ClassMethodWrapper extends Wrapper
{
    /**
     * @var ClassMethodEntity
     */
    protected $method;

    /**
     * ClassMethodWrapper constructor.
     *
     * @param string|ClassMethodEntity $name
     */
    public function __construct($name)
    {
        if ($name instanceof ClassMethodEntity) {

            $this->method = $name;
        }

        else {

            $this->method = class_method_entity((string)$name);
        }
    }

    /**
     * @param string $data
     * @return string
     */
    protected function wrap(string $data): string
    {
        return $this->method->line()->line($data)->setLevel($this->level)->render();
    }
}
