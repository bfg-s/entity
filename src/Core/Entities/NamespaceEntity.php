<?php

namespace Bfg\Entity\Core\Entities;

use Bfg\Entity\Core\Entities\Helpers\NamespaceHelpers;
use Bfg\Entity\Core\Entity;
use Bfg\Entity\Core\Traits\HaveDocumentatorEntity;

/**
 * Class NamespaceEntity
 * @package Bfg\Entity\Core\Entities
 */
class NamespaceEntity extends Entity
{
    use HaveDocumentatorEntity, NamespaceHelpers;

    /**
     * Namespace name
     *
     * @var string
     */
    protected $name;

    /**
     * Namespace objects collection
     *
     * @var array
     */
    protected $objects = [];

    /**
     * NamespaceEntity constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Add object in to namespace area
     *
     * @param string|ClassEntity $name
     * @param \Closure|array|null $call
     * @return ClassEntity|NamespaceEntity
     */
    public function class($name, $call = null)
    {
        if ($name instanceof ClassEntity) {

            $this->objects[$name->getName()] = $name;
        }

        else if (is_string($name)) {

            $object = new ClassEntity($name);

            if (is_callable($call)) {

                call_user_func($call, $object);
            }

            $this->objects[$name] = $object;

            return $object;
        }

        return $this;
    }

    /**
     * Build entity
     *
     * @return string
     */
    protected function build(): string
    {
        $spaces = $this->space();
        $data = "";

        if ($this->doc){

            $this->doc->setLevel($this->level);

            if ($d = $this->doc->render()) {

                $data .= $d . $this->eol();
            }
        }

        $data .= $spaces . "namespace " . $this->name . " {" . $this->eol() . $this->eol();

        foreach ($this->objects as $object) {

            /** @var ClassEntity $object */
            $object->setLevel($this->level + 4);
            $data .= $object->render() . $this->eol() . $this->eol();
        }

        $data .= $spaces . "}" . $this->eol();

        return $data;
    }
}
