<?php

namespace Bfg\Entity\Core\Savers;

use Illuminate\Contracts\Support\Renderable;
use Bfg\Entity\Core\Savers\Modes\ClassMode;
use Bfg\Entity\Core\Savers\Modes\ConstantMode;
use Bfg\Entity\Core\Savers\Modes\FileMode;
use Bfg\Entity\Core\Savers\Modes\FunctionAbstractMode;
use Bfg\Entity\Core\Savers\Modes\FunctionMode;
use Bfg\Entity\Core\Savers\Modes\MethodMode;
use Bfg\Entity\Core\Savers\Modes\ObjectMode;
use Bfg\Entity\Core\Savers\Modes\ParameterMode;
use Bfg\Entity\Core\Savers\Modes\PropertyMode;

/**
 * Class Driver
 * @package Bfg\Entity\Core\Savers
 */
abstract class Driver {

    /**
     * @var array
     */
    protected static $modes = [
        "class" => ClassMode::class,
        "constant" => ConstantMode::class,
        "file" => FileMode::class,
        "function_abstract" => FunctionAbstractMode::class,
        "function" => FunctionMode::class,
        "method" => MethodMode::class,
        "object" => ObjectMode::class,
        "parameter" => ParameterMode::class,
        "property" => PropertyMode::class
    ];

    /**
     * @var string
     */
    protected $file;

    /**
     * @var \ReflectionClassConstant|\ReflectionMethod|\ReflectionParameter|\ReflectionProperty|\ReflectionFunction|\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionObject
     */
    protected $ref;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var string
     */
    private $data;

    /**
     * @var array
     */
    private $position;

    /**
     * @param $subject
     * @return $this
     * @throws \ReflectionException
     */
    public function setSubject($subject) {

        if ($subject instanceof \ReflectionClassConstant) {

            if (!$this->ref) {

                $this->ref = $subject;

                $this->mode = "constant";
            }

            $subject = $subject->getDeclaringClass();
        }

        else if ($subject instanceof \ReflectionMethod) {

            if (!$this->ref) {

                $this->ref = $subject;

                $this->mode = "method";
            }

            $subject = $subject->getDeclaringClass();
        }

        else if ($subject instanceof \ReflectionParameter) {

            if (!$this->ref) {

                $this->ref = $subject;

                $this->mode = "parameter";
            }

            $subject = $subject->getDeclaringClass() || $subject->getDeclaringFunction();
        }

        else if ($subject instanceof \ReflectionProperty) {

            if (!$this->ref) {

                $this->ref = $subject;

                $this->mode = "property";
            }

            $subject = $subject->getDeclaringClass();
        }


        if ($subject instanceof \ReflectionFunction) {

            if (!$this->ref) {

                $this->ref = $subject;

                $this->mode = "function";
            }

            $subject = $subject->getFileName();
        }

        else if ($subject instanceof \ReflectionClass) {

            if (!$this->ref) {

                $this->ref = $subject;

                $this->mode = "class";
            }

            $subject = $subject->getFileName();
        }

        else if ($subject instanceof \ReflectionFunctionAbstract) {

            if (!$this->ref) {

                $this->ref = $subject;

                $this->mode = "function_abstract";
            }

            $subject = $subject->getFileName();
        }

        else if ($subject instanceof \ReflectionObject) {

            if (!$this->ref) {

                $this->ref = $subject;

                $this->mode = "object";
            }

            $subject = $subject->getFileName();
        }

        else if ($subject instanceof \Closure) {

            $ref = new \ReflectionFunction($subject);

            $subject = $ref->getFileName();

            if (!$this->ref) {

                $this->ref = $ref;

                $this->mode = "function";
            }
        }

        else if (is_object($subject) || class_exists($subject)) {

            $ref = new \ReflectionClass($subject);

            $subject = $ref->getFileName();

            if (!$this->ref) {

                $this->ref = $ref;

                $this->mode = "class";
            }
        }

        else {

            $this->mode = "file";
        }

        $this->file = (string)$subject;

        return $this;
    }

    /**
     * @param string|Renderable $data
     * @param array $position
     * @return $this
     */
    public function setData($data, array $position)
    {
        if ($data instanceof Renderable) {

            $this->data = $data->render();
        }

        else {

            $this->data = (string)$data;
        }

        $this->position = $position;

        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        if ($this->mode && isset(static::$modes[$this->mode])) {

            $this->data = static::$modes[$this->mode]::create($this->data)
                ->position($this->position)
                ->file($this->file)
                ->ref($this->ref)
                ->render();
        }

        return $this->data;
    }

    /**
     * Save method
     * @return int
     */
    abstract public function save() : int;
}
