<?php

namespace Bfg\Entity\Core\Entities;

use Bfg\Entity\Core\Entity;
use Bfg\Entity\Core\Traits\HaveDocumentatorEntity;

/**
 * Class ClassEntity
 * @package Bfg\Entity\Core\Entities
 */
class ClassEntity extends Entity
{
    use HaveDocumentatorEntity;

    /**
     * Object namespace
     *
     * @var null|string
     */
    protected $namespace = null;

    /**
     * Use objects
     *
     * @var array
     */
    protected $uses = [];

    /**
     * Class name
     *
     * @var string
     */
    protected $name;

    /**
     * Object modifiers
     *
     * @var string
     */
    protected $modifiers = "class";

    /**
     * Object traits
     *
     * @var array
     */
    protected $traits = [];

    /**
     * Object properties
     *
     * @var array
     */
    protected $props = [];

    /**
     * Object constants
     *
     * @var array
     */
    protected $const = [];

    /**
     * Extend object
     *
     * @var null|string
     */
    protected $extends = null;

    /**
     * Implement list
     *
     * @var array
     */
    protected $implements = [];

    /**
     * Methods list
     *
     * @var array
     */
    protected $methods = [];

    /**
     * Auto find object and set use
     *
     * @var bool
     */
    protected $auto_use = true;

    /**
     * ClassEntity constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Add method in to collection
     *
     * @param string|ClassMethodEntity $name
     * @param \Closure|array|string|null $call
     * @return $this|ClassMethodEntity
     */
    public function method($name, $call = null)
    {
        if ($name instanceof ClassMethodEntity) {

            if (!empty($n = $name->getName())) {

                $name->setParent($this);

                $this->methods[$n] = $name;
            }
        }

        else if (is_string($name)) {

            $this->methods[$name] = new ClassMethodEntity($name, $this);

            if (is_embedded_call($call)) {

                call_user_func($call, $this->methods[$name]);

                return $this;

            } else {

                return $this->methods[$name];
            }
        }

        return $this;
    }

    /**
     * @param  string  $name
     * @param  string  $value
     * @return $this
     */
    public function const(string $name, $value = ClassPropertyEntity::NONE_PARAM)
    {
        $this->const[$name] = $value;

        return $this;
    }

    /**
     * Add prop inn to object
     *
     * @param string|ClassPropertyEntity $name
     * @param mixed $value
     * @return ClassEntity|ClassPropertyEntity|string
     */
    public function prop($name, $value = ClassPropertyEntity::NONE_PARAM)
    {
        if ($name instanceof ClassPropertyEntity) {

            if (!empty($n = $name->getName())) {

                $this->props[$n] = $name;
            }

            return $name;
        }

        else if (is_string($name)) {

            $prop = new ClassPropertyEntity($name);

            if (is_embedded_call($value)) {

                call_user_func($value, $prop);

            } else {

                $prop->value($value);
            }

            $this->props[\Arr::last(explode(':', $name))] = $prop;

            return $prop;
        }

        return $this;
    }

    /**
     * Set object namespace
     *
     * @param $namespace
     * @return $this
     */
    public function namespace($namespace)
    {
        $this->namespace = "namespace " . $namespace . ";" . $this->eol();

        return $this;
    }

    /**
     * Set extends object
     *
     * @param string $object
     * @return ClassEntity
     */
    public function extend($object = "")
    {
        if ($this->auto_use && preg_match('/^([A-Za-z][A-Za-z\\\\]+)\\\\([A-Za-z]+)$/', $object, $matches)) {

            $this->use($object);
            
            $object = $matches[2];
        }

        $this->extends = $object;

        return $this;
    }

    /**
     * Add use in to object
     *
     * @param $object
     * @return $this
     */
    public function use($object)
    {
        $object = trim($object, " \t\n\r\0\x0B\\");

        $this->uses[$object] = "use " . $object . ";";

        return $this;
    }

    /**
     * Add Implement in to object
     *
     * @param $implement
     * @return $this
     */
    public function implement($implement)
    {
        if ($this->auto_use && preg_match('/^([A-Za-z][A-Za-z\\\\]+)\\\\([A-Za-z]+)$/', $implement, $matches)) {

            $this->use($implement);
            
            $implement = $matches[2];
        }

        $this->implements[$implement] = $implement;

        return $this;
    }

    /**
     * Add trait in to object
     *
     * @param $trait
     * @return $this
     */
    public function addTrait($trait)
    {
        if ($this->auto_use && preg_match('/^([A-Za-z][A-Za-z\\\\]+)\\\\([A-Za-z]+)$/', $trait, $matches)) {

            $this->use($trait);
            
            $trait = $matches[2];
        }

        $this->traits[$trait] = $trait;

        return $this;
    }

    /**
     * Set Final Class
     *
     * @return $this
     */
    public function finalClass()
    {
        $this->modifiers = "final class";

        return $this;
    }

    /**
     * Set Abstract Class
     *
     * @return $this
     */
    public function abstractClass()
    {
        $this->modifiers = "abstract class";

        return $this;
    }

    /**
     * Set Trait Object
     *
     * @return $this
     */
    public function traitObject()
    {
        $this->modifiers = "trait";

        return $this;
    }

    /**
     * Set Interface Object
     *
     * @return $this
     */
    public function interfaceObject()
    {
        $this->modifiers = "interface";

        return $this;
    }

    /**
     * Off auto use objects
     *
     * @return $this
     */
    public function offAutoUse()
    {
        $this->auto_use = false;

        return $this;
    }

    /**
     * Method name getter
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Auto doc
     */
    private function autoDoc()
    {
        $this->doc(function (DocumentorEntity $doc) {

            $doc->name($this->name . " " . ucwords($this->modifiers));

            if (!empty($this->namespace)) {

                $doc->tagPackage(str_replace(["namespace ", "\n", ";"], "", $this->namespace));
            }
        });
    }

    /**
     * Build entity
     *
     * @return string
     */
    protected function build(): string
    {
        if (!$this->doc) {

            $this->autoDoc();
        }

        foreach ($this->methods as $key => $method) {

            /** @var ClassMethodEntity $method */
            $method->setLevel($this->level + 4);
            $method = $method->render() . $this->eol() . $this->eol();

            if ($this->auto_use && preg_match_all('/([A-Za-z\\\\]+)\\\\([A-Za-z]+)/', $method, $matches, PREG_SET_ORDER)) {

                foreach ($matches as $match) {

                    $method = str_replace($match[0], $match[2], $method);
                    if ($this->name != $match[2]) $this->use($match[0]);
                }

                $method = str_replace("\\" . $this->name, $this->name, $method);
            }

            $this->methods[$key] = $method;
        }

        foreach ($this->props as $key => $prop) {

            /** @var ClassPropertyEntity $prop */
            $prop->setLevel($this->level + 4);
            $prop = $prop->render();

            if ($this->auto_use && preg_match_all('/([A-Za-z\\\\]+)\\\\([A-Za-z]+)/', $prop, $matches2, PREG_SET_ORDER)) {

                foreach ($matches2 as $match) {

                    $prop = str_replace($match[0], $match[2], $prop);
                    if ($this->name != $match[2]) $this->use($match[0]);
                }

                $prop = str_replace("\\" . $this->name, $this->name, $prop);
            }

            $this->props[$key] = $prop;
        }

        $spaces = $this->space();

        $data = "";

        if ($this->namespace){

            $data .= $spaces . $this->namespace . $this->eol();
        }

        foreach ($this->uses as $use) {

            $data .= $spaces . $use . $this->eol();
        }

        if (count($this->uses)) {

            $data .= $this->eol();
        }

        if ($this->doc){

            $this->doc->setLevel($this->level);

            $data .= $this->doc->render() . $this->eol();
        }

        $data .= $spaces . $this->modifiers . " " . $this->name .
            ($this->extends ? " extends " . $this->extends : "") .
            ($this->implements ? " implements " . implode(", ", $this->implements) : "") . $this->eol();

        $data .= $spaces . "{" . $this->eol();

        if (count($this->traits)) {

            $data .= $spaces . str_repeat(" ", 4) . "use " . implode(", ", $this->traits) . ";" . $this->eol() . $this->eol();
        }

        foreach ($this->const as $n_const => $const) {
            $data .= "const {$n_const}" . ($const !== ClassPropertyEntity::NONE_PARAM ? " = {$const};":";") . $this->eol() . $this->eol();
        }

        foreach ($this->props as $prop) {

            /** @var ClassPropertyEntity $prop */
            $data .= $prop . $this->eol() . $this->eol();
        }

        foreach ($this->methods as $method) {

            $data .= $method;
        }

        $data .= $spaces . "}" . $this->eol();

        return $data;
    }
}
