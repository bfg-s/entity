<?php

namespace Bfg\Entity\Core\Traits;

/**
 * Trait EntityDecorator.
 * @package Bfg\Entity\Core\Traits
 */
trait EntityDecorator
{
    /**
     * Level counter for tab spaces.
     *
     * @var int
     */
    protected $level = 0;

    /**
     * Add Tab.
     *
     * @param int $spaces
     * @return $this
     */
    public function tabSpace(int $spaces = 4)
    {
        $this->setLevel($this->level + $spaces);

        return $this;
    }

    /**
     * @param int $level
     * @return $this
     */
    public function setLevel(int $level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Spaces getter.
     *
     * @param int $x
     * @return string
     */
    public function space()
    {
        return str_repeat(' ', $this->level);
    }

    /**
     * Get tab space.
     *
     * @return string
     */
    public function tabulation()
    {
        return '    ';
    }

    /**
     * Get new line constant.
     *
     * @return string
     */
    public function eol()
    {
        return PHP_EOL;
    }
}
