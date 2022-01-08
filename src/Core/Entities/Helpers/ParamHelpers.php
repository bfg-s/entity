<?php

namespace Bfg\Entity\Core\Entities\Helpers;

use Bfg\Entity\Core\Entities\ParamEntity;

/**
 * Trait ParamHelpers.
 * @package Bfg\Entity\Core\Entities\Helpers
 */
trait ParamHelpers
{
    /**
     * @param array|\ReflectionParameter|\ReflectionFunction|\ReflectionMethod|\Closure $params
     * @param bool $no_types
     * @param bool $no_values
     * @return ParamEntity
     */
    public static function buildFromReflection($params, $no_types = false, $no_values = false)
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

        $entity = new ParamEntity();

        try {
            foreach ($params as $param) {
                if ($param instanceof \ReflectionParameter) {
                    if ($param->isVariadic()) {
                        $entity->manyParam($param->name);
                    } elseif ($param->hasType() && ! $no_types) {
                        $entity->typeParam($param->getType()->getName(), $param->name, ($param->isDefaultValueAvailable() && ! $no_values ? $param->getDefaultValue() : ParamEntity::NO_PARAM_VALUE));
                    } else {
                        $entity->param($param->name, ($param->isDefaultValueAvailable() && ! $no_values ? $param->getDefaultValue() : ParamEntity::NO_PARAM_VALUE));
                    }
                }
            }
        } catch (\Exception $exception) {
            \Log::error($exception);
        }

        return $entity;
    }
}
