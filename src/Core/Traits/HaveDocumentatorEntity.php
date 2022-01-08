<?php

namespace Bfg\Entity\Core\Traits;

use Bfg\Entity\Core\Entities\DocumentorEntity;

/**
 * Trait HaveDocumentatorEntity.
 * @package Bfg\Entity\Core\Traits
 */
trait HaveDocumentatorEntity
{
    /**
     * @var null|DocumentorEntity
     */
    protected $doc = null;

    /**
     * Documentor class access.
     *
     * @param \Closure|DocumentorEntity $call
     * @return $this
     */
    public function doc($call)
    {
        if ($call instanceof DocumentorEntity) {
            $this->doc = $call;
        } elseif ($call instanceof \Closure) {
            if (! $this->doc) {
                $this->doc = new DocumentorEntity();
            }

            call_user_func($call, $this->doc);
        }

        return $this;
    }
}
