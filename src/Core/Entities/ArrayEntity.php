<?php

namespace Bfg\Entity\Core\Entities;

use Bfg\Entity\Core\Entity;
use Bfg\Entity\Core\EntityPhp;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class ArrayEntity.
 * @package Bfg\Entity\Core\Entities
 */
class ArrayEntity extends Entity
{
    /**
     * Compress.
     *
     * @var bool
     */
    private $minimized = false;

    /**
     * A max char count in string value, 0 - off counter.
     *
     * @var int
     */
    private $max_chars = 0;

    /**
     * Array data.
     *
     * @var array
     */
    private $data = [];

    /**
     * Level count from.
     *
     * @var int
     */
    protected $ins_level = 0;

    /**
     * ArrayEntity constructor.
     * @param array|Arrayable $data
     */
    public function __construct($data = [])
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        if (! is_array($data)) {
            $data = [];
        }

        $this->data = $data;
    }

    /**
     * @return $this
     */
    public function minimized()
    {
        $this->minimized = true;

        return $this;
    }

    /**
     * @param bool $minimized
     * @return $this
     */
    public function setMinimized(bool $minimized)
    {
        $this->minimized = $minimized;

        return $this;
    }

    /**
     * @param int $max_chars
     * @return $this
     */
    public function setMaxChars(int $max_chars)
    {
        $this->max_chars = $max_chars;

        return $this;
    }

    /**
     * @deprecated only build
     * @param int $lvl
     * @return $this
     */
    public function setInsLevel(int $lvl)
    {
        $this->ins_level = $lvl;

        return $this;
    }

    /**
     * Build entity.
     *
     * @return string
     */
    protected function build(): string
    {
        if (is_string($this->data)) {
            return $this->data;
        }

        if (! $this->minimized) {
            $t = $this->space().str_repeat($this->tabulation(), $this->ins_level);

            $t2 = $t.$this->tabulation();

            $n = "\n";

            $s = ' ';
        } else {
            $t = '';
            $t2 = '';
            $n = '';
            $s = '';
        }

        $pattern = function ($key, $val, $quot = true) use ($t, $t2, $n, $s) {
            $quot = $quot ? '"' : '';

            if ($this->max_chars) {
                $value = $quot.substr($val, 0, $this->max_chars).(strlen($val) > $this->max_chars ? '...' : '').$quot;
            } else {
                if ($quot == '"') {
                    $val = str_replace('\\', '\\\\', $val);
                    $val = str_replace('$', '\$', $val);
                }

                $value = $quot.$val.$quot;
            }

            $index = ! is_numeric($key) ? "\"{$key}\"{$s}=>{$s}" : '';

            return "{$t2}{$index}{$value}";
        };

        $return = [];

        foreach ($this->data as $key => $val) {
            if (is_array($val)) {
                $val = (new static($val))->setMinimized($this->minimized)->setMaxChars($this->max_chars)->setInsLevel($this->ins_level + 1)->setLevel($this->level)->render();

                $return[] = $pattern($key, (is_numeric($key) ? $t : '').$val, false);
            } elseif (is_bool($val)) {
                $val = $val ? 'true' : 'false';

                $return[] = $pattern($key, $val, false);
            } elseif (is_int($val) || is_float($val) || is_float($val)) {
                $return[] = $pattern($key, $val, false);
            } elseif ($val instanceof EntityPhp) {
                $return[] = $pattern($key, $val->render(), false);
            } else {
                $return[] = $pattern($key, $val);
            }
        }

        return "[{$n}".implode(",{$n}", $return)."{$n}{$t}]";
    }
}
