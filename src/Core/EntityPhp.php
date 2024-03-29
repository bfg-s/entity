<?php

namespace Bfg\Entity\Core;

use Bfg\Entity\Core\Entities\ArrayEntity;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class EntityPhp.
 * @package Bfg\Entity\Core
 */
class EntityPhp extends Entity
{
    /**
     * @var Renderable|object|string
     */
    protected $data = '';

    /**
     * EntityPhp constructor.
     *
     * @param Renderable|object|string $data
     */
    public function __construct($data = '')
    {
        $this->data = $this->adapter($data);
    }

    /**
     * @return Renderable|object|string
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Build entity.
     *
     * @return string
     */
    protected function build(): string
    {
        if ($this->data instanceof Renderable) {
            $this->data = $this->data->render();
        } else {
            $this->data = (string) $this->data;
        }

        return $this->data;
    }

    /**
     * @param mixed $data
     * @return string
     */
    private function adapter($data)
    {
        if (is_null($data)) {
            $data = 'null';
        } elseif ($data === true) {
            $data = 'true';
        } elseif ($data === false) {
            $data = 'false';
        } elseif (is_array($data)) {
            $data = ArrayEntity::create($data)->minimized()->render();
        }

        return $data;
    }
}
