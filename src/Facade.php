<?php

namespace Bfg\Entity;

use Illuminate\Support\Facades\Facade as FacadeIlluminate;

/**
 * Class Facade
 * @package Bfg\Entity
 */
class Facade extends FacadeIlluminate
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Entity::class;
    }
}
