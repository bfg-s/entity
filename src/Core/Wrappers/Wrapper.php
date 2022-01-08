<?php

namespace Bfg\Entity\Core\Wrappers;

use Bfg\Entity\Core\Traits\EntityDecorator;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Wrapper.
 * @package Bfg\Entity\Core\Wrappers
 */
abstract class Wrapper implements Renderable
{
    use EntityDecorator;

    /**
     * @var string
     */
    protected $data = '';

    /**
     * @var string
     */
    protected $space = '';

    /**
     * @param $data
     * @return $this
     */
    public function createData($data)
    {
        if ($data instanceof Renderable) {
            $data = $data->render();
        }

        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $return = explode("\n", $this->wrap($this->data));

        foreach ($return as $key => $item) {
            if (! empty(str_replace(' ', '', $item))) {
                $return[$key] = $this->space().$item;
            }
        }

        return implode("\n", $return);
    }

    /**
     * @param array $props
     * @return Wrapper
     */
    public static function create(...$props)
    {
        return new static(...$props);
    }

    /**
     * @param string $data
     * @return string
     */
    abstract protected function wrap(string $data) : string;
}
