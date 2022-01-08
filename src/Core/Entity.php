<?php

namespace Bfg\Entity\Core;

use Bfg\Entity\Core\Traits\EntityDecorator;
use Bfg\Entity\Core\Wrappers\CommentWrapper;
use Bfg\Entity\Core\Wrappers\PHPWrapper;
use Bfg\Entity\Core\Wrappers\ReturnWrapper;
use Bfg\Entity\Core\Wrappers\Wrapper;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Entity.
 * @package Bfg\Entity\Core
 */
abstract class Entity implements Renderable
{
    use EntityDecorator;

    /**
     * @var array
     */
    protected static $wrappers = [
        'php' => PHPWrapper::class,
        'return' => ReturnWrapper::class,
        'comment' => CommentWrapper::class,
    ];

    /**
     * @var array
     */
    protected $apply_wrappers = [];

    /**
     * Build entity.
     *
     * @return string
     */
    abstract protected function build() : string;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param string|array $name
     * @return $this
     */
    public function wrap(...$names)
    {
        foreach ($names as $name) {
            $parts = [];

            if (is_string($name)) {
                $parts = explode(':', $name);
            } elseif (is_array($name)) {
                $this->wrap(...array_values($name));
            } else {
                $parts[] = $name;
            }

            foreach ($parts as $part) {
                if (is_string($part)) {
                    if (isset(self::$wrappers[$part])) {
                        $this->apply_wrappers[] = self::$wrappers[$part];
                    } elseif (class_exists($part)) {
                        $o = new $name;

                        if ($o instanceof Wrapper) {
                            $this->apply_wrappers[] = $o;
                        }
                    }
                } elseif ($name instanceof Wrapper) {
                    $this->apply_wrappers[] = $name;
                }
            }
        }

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $data = $this->build();

        $i = 0;

        foreach (array_reverse(array_values($this->apply_wrappers)) as $apply_wrapper) {
            if ($apply_wrapper instanceof Wrapper) {
                $data = $apply_wrapper->createData($data);
            } else {
                $data = $apply_wrapper::create()->createData($data);
            }

            $i++;
        }

        return (string) $data;
    }

    /**
     * @param mixed ...$params
     * @return $this
     */
    public static function create(...$params)
    {
        return new static(...$params);
    }
}
