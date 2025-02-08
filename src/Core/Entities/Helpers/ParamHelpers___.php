<?php

namespace Bfg\Entity\Core\Entities\Helpers;

use Bfg\Entity\Core\Entities\ParamEntity;

/**
 * Trait ParamHelpers.
 * @package Bfg\Entity\Core\Entities\Helpers
 */
trait ParamHelpers___
{
    /**
     * @param  \ReflectionParameter|\ReflectionMethod|array|\ReflectionFunction|\Closure  $params
     * @param  bool  $no_types
     * @param  bool  $no_values
     * @return string
     */
    public static function buildFromReflection(
        \ReflectionParameter|\ReflectionMethod|array|\ReflectionFunction|\Closure $params,
        bool $no_types = false,
        bool $no_values = false
    ): string
    {
        if ($params instanceof \Closure) {
            try {
                $params = new \ReflectionFunction($params);
            } catch (\Exception $exception) {
            }
        }

        if ($params instanceof \ReflectionParameter) {
            $params = [$params];
        } elseif ($params instanceof \ReflectionFunction) {
            $params = $params->getParameters();
        } elseif ($params instanceof \ReflectionMethod) {
            $params = $params->getParameters();
        }

        if (! is_array($params)) {
            $params = [$params];
        }

        $entity = [];

        foreach ($params as $key => $param) {
            if ($param instanceof \ReflectionParameter) {
                if ($param->isVariadic()) {
                    $entity[$key] = "\$" . $param->name . ($param->isDefaultValueAvailable() && ! $no_values ? " = " . json_encode($param->getDefaultValue()) : "");
                } elseif ($param->hasType() && ! $no_types) {
                    $entity[$key] = $param->getType()->getName() . " \$" . $param->name . ($param->isDefaultValueAvailable() && ! $no_values ? " = " . json_encode($param->getDefaultValue()) : "");
                } else {
                    $entity[$key] = "\$" . $param->name . ($param->isDefaultValueAvailable() && ! $no_values ? " = " . json_encode($param->getDefaultValue()) : "");
                }
            }
        }

        return implode(", ", $entity);
    }
}
