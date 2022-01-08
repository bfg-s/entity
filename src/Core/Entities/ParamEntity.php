<?php

namespace Bfg\Entity\Core\Entities;

use Bfg\Entity\Core\Entities\Helpers\ParamHelpers;
use Bfg\Entity\Core\Entity;
use Bfg\Entity\Core\EntityPhp;

/**
 * Class ParamEntity.
 * @package Bfg\Entity\Core\Entities
 */
class ParamEntity extends Entity
{
    use ParamHelpers;

    const NO_PARAM_VALUE = '__!NO_PARAM_VALUE!__';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * Add custom segment.
     *
     * @param string $segment
     * @return $this
     */
    public function segmentParam(string $segment)
    {
        $this->params[] = ['method' => 'segment_param', 'value' => $segment];

        return $this;
    }

    /**
     * @param string|object $type
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function typeParam($type, string $name, $value = self::NO_PARAM_VALUE)
    {
        if (is_object($type)) {
            $type = get_class($type);
        } else {
            $type = (string) $type;
        }

        if ($type !== 'array' && $type !== 'callable' && $type !== 'self' && $type !== 'bool' && $type !== 'boolean' && $type !== 'float' && $type !== 'int' && $type !== 'string' && $type !== 'iterable' && $type !== 'object') {
            if (class_exists($type) || preg_match('/^[^\\\\]([A-Za-z\\\\]+)\\\\([A-Za-z]+)$/', $type)) {
                $type = '\\'.$type;
            }
        }

        $this->params[] = ['method' => 'type_param', 'type' => $type, 'name' => $name, 'value' => $value];

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function manyParam(string $name = 'params')
    {
        $this->params[] = ['method' => 'many_param', 'name' => $name];

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function param(string $name, $value = self::NO_PARAM_VALUE)
    {
        $this->params[] = ['method' => 'no_type_param', 'name' => $name, 'value' => $value];

        return $this;
    }

    /**
     * Build entity.
     *
     * @return string
     */
    protected function build(): string
    {
        return implode(', ', array_map(function ($item) {
            return $this->{$item['method']}($item);
        }, $this->params));
    }

    /**
     * @param array $data
     * @return string
     */
    private function segment_param(array $data)
    {
        return $data['value'];
    }

    /**
     * @param array $data
     * @return string
     */
    private function type_param(array $data)
    {
        return "{$data['type']} \${$data['name']}".($data['value'] !== self::NO_PARAM_VALUE ? " = {$this->valueAdapter($data['value'])}" : '');
    }

    /**
     * @param array $data
     * @return string
     */
    private function many_param(array $data)
    {
        return "...\${$data['name']}";
    }

    /**
     * @param array $data
     * @return string
     */
    private function no_type_param(array $data)
    {
        return "\${$data['name']}".($data['value'] !== self::NO_PARAM_VALUE ? " = {$this->valueAdapter($data['value'])}" : '');
    }

    /**
     * @param $value
     * @return string
     */
    private function valueAdapter($value)
    {
        if (is_null($value) || $value === true || $value === false || is_array($value)) {
            return EntityPhp::create($value)->render();
        } elseif (is_int($value) || is_float($value) || is_float($value)) {
            return $value;
        } else {
            return "\"{$value}\"";
        }
    }
}
