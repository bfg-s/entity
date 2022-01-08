<?php

namespace Bfg\Entity\Core\Entities;

use Bfg\Entity\Core\Entity;
use Bfg\Entity\Core\EntityPhp;
use Bfg\Entity\Core\Traits\HaveDocumentatorEntity;
use Illuminate\Support\Str;

/**
 * Class ClassPropertyEntity.
 * @package Bfg\Entity\Core\Entities
 */
class ClassPropertyEntity extends Entity
{
    use HaveDocumentatorEntity;

    const NONE_PARAM = '__!NONE!__';

    /**
     * Property name.
     *
     * @var string
     */
    protected $name;

    /**
     * Property value.
     *
     * @var null|string
     */
    protected $value = self::NONE_PARAM;

    /**
     * Property modifiers.
     *
     * @var string
     */
    protected $modifiers = 'public';

    /**
     * ClassPropertyEntity constructor.
     *
     * @param string $name
     * @param $value
     */
    public function __construct(string $name, $value = self::NONE_PARAM)
    {
        $test = explode(':', $name);

        if (count($test) == 2) {
            $this->modifiers = $test[0];
            $name = $test[1];
        }

        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Update modifier.
     *
     * @param $modifier
     * @return $this
     */
    public function modifier($modifier)
    {
        $this->modifiers = $modifier;

        return $this;
    }

    /**
     * Set new value from property.
     *
     * @param $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Method name getter.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Auto doc.
     */
    public function autoDoc()
    {
        $this->doc(function (DocumentorEntity $doc) {
            $doc->name(ucwords($this->modifiers).' variable '.ucfirst(Str::camel($this->name)));

            $t = gettype($this->value);

            try {
                if ($this->value instanceof EntityPhp) {
                    $data = eval('return '.$this->value->render().';');

                    $t = gettype($data);
                }
            } catch (\Exception $exception) {
            }

            $doc->tagVar($t);
        });
    }

    /**
     * Build entity.
     *
     * @return string
     */
    protected function build(): string
    {
        if (! $this->doc) {
            $this->autoDoc();
        }

        $spaces = $this->space();
        $data = '';

        if ($this->doc) {
            $this->doc->setLevel($this->level);

            if ($d = $this->doc->render()) {
                $data .= $d.$this->eol();
            }
        }

        $sp = self::NONE_PARAM != $this->value;

        if ($this->value instanceof EntityPhp) {
            $this->value = $this->value->render();
        } else {
            if (is_string($this->value)) {
                $this->value = '"'.$this->value.'"';
            }
            if (is_array($this->value)) {
                $this->value = array_entity($this->value)->setLevel($this->level)->render();
            }
            if ($this->value === true) {
                $this->value = 'true';
            }
            if ($this->value === false) {
                $this->value = 'false';
            }
            if (is_numeric($this->value)) {
                $this->value = (string) $this->value;
            }
            if (! is_string($this->value)) {
                $this->value = gettype($this->value);
            }
        }

        $data .= $spaces.$this->modifiers.' $'.$this->name.($sp ? ' = '.$this->value.';' : ';');

        return $data;
    }
}
