<?php

namespace Bfg\Entity\Core\Entities;

use Bfg\Entity\Core\Entity;
use Bfg\Entity\Core\Traits\HaveDocumentatorEntity;

/**
 * Class ClassMethodEntity.
 * @package Bfg\Entity\Core\Entities
 */
class ClassMethodEntity extends Entity
{
    use HaveDocumentatorEntity;

    /**
     * Method name.
     *
     * @var null|string
     */
    protected $name = null;

    /**
     * Method parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Method return type.
     *
     * @var null|string
     */
    protected $returnType = null;

    /**
     * Doc Method return type.
     *
     * @var null|string
     */
    protected $docReturnType = null;

    /**
     * Method modifiers.
     *
     * @var string
     */
    protected $modifiers = 'public';

    /**
     * Method data.
     *
     * @var string
     */
    protected $data = null;

    /**
     * Lines on method.
     *
     * @var array
     */
    protected $lines = [];

    /**
     * Parent if exists.
     *
     * @var ClassEntity
     */
    protected $parent = null;

    /**
     * Create exception.
     *
     * @var bool
     */
    protected $exception = false;

    /**
     * ClassMethodEntity constructor.
     *
     * @param $name
     * @param ClassEntity|null $parent
     */
    public function __construct($name, ClassEntity $parent = null)
    {
        $this->name = $name;

        if ($parent) {
            $this->parent = $parent;
        }
    }

    /**
     * Parent accessor.
     *
     * @return ClassEntity|null
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * Parent accessor.
     *
     * @param ClassEntity $parent
     * @return ClassMethodEntity
     */
    public function setParent(ClassEntity $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Add param in to method.
     *
     * @param $name
     * @param null $default
     * @param null $type
     * @return $this
     */
    public function param($name, $default = null, $type = null)
    {
        if ($type === true && ! is_null($default)) {
            $type = gettype($default);
        }

        $type = (string) $type;

        if ($type && (class_exists($type) || preg_match('/^[^\\\\]([A-Za-z\\\\]+)\\\\([A-Za-z]+)$/', $type))) {
            $type = '\\'.$type;
        }

        if (is_string($default)) {
            $default = '"'.$default.'"';
        }

        if (is_array($default)) {
            $default = array_entity($default)->minimized()->render();
        }

        $data = ($type ? $type.' ' : '').'$'.$name.($default ? ' = '.$default : '');

        $this->parameters[] = $data;

        return $this;
    }

    /**
     * Create custom param.
     *
     * @param string $data
     * @return $this
     */
    public function customParam(string $data)
    {
        $this->parameters[] = $data;

        return $this;
    }

    /**
     * Create custom param if $eq.
     *
     * @param $eq
     * @param string $data
     * @return $this
     */
    public function customParamIf($eq, string $data)
    {
        return $eq ? $this->customParam($data) : $this;
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
     * Extend method inner data.
     *
     * @param $data
     * @return $this
     */
    public function data($data)
    {
        if ($this->data) {
            $this->data .= $data.$this->eol();
        } else {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * Set tag "return" in to auto doc.
     *
     * @param $data
     * @return $this
     */
    public function docReturnType($data)
    {
        $this->docReturnType = $data;

        return $this;
    }

    /**
     * Quick set doc description.
     * @param $data
     * @return $this
     */
    public function docDescription($data)
    {
        $this->doc(function (DocumentorEntity $doc) use ($data) {
            $doc->description($data);
        });

        return $this;
    }

    /**
     * Set end line hov return self.
     *
     * @return $this
     */
    public function returnThis()
    {
        $this->docReturnType = '$this';

        $this->dataReturn($this->docReturnType);

        return $this;
    }

    /**
     * Insert return data.
     *
     * @param $return_data
     * @return $this
     */
    public function dataReturn($return_data)
    {
        if (is_array($return_data)) {
            $return_data = array_entity($return_data)->render();

            $this->returnType('array');
        }

        return $this->line('return '.$return_data.';');
    }

    /**
     * @param $type
     * @return $this
     */
    public function returnType($type)
    {
        $this->returnType = $type;

        return $this;
    }

    /**
     * Add line public method.
     *
     * @param string $data
     * @param null $key
     * @return $this
     */
    public function line($data = '', $key = null)
    {
        foreach (explode("\n", $data) as $item) {
            if (! is_null($key)) {
                if (! isset($this->lines[$key])) {
                    $this->lines[$key] = $item;
                } else {
                    $this->lines[$key] .= $item;
                }
            } else {
                $this->lines[] = $item;
            }
        }

        return $this;
    }

    /**
     * @param  string  $data
     * @param  null  $key
     * @return $this
     */
    public function tab($data = '', $key = null)
    {
        return $this->line('    '.$data, $key);
    }

    public function exception()
    {
        $this->exception = true;

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
    private function autoDoc()
    {
        $this->doc(function (DocumentorEntity $doc) {
            foreach ($this->parameters as $parameter) {
                if (preg_match('/^\.\.\.\$(.*)/', $parameter, $m)) {
                    $doc->tagParam('array', $m[1]);
                } elseif (preg_match('/(\$[a-z\_]+)/', $parameter, $m)) {
                    $doc->tagParam($m[1]);
                }
            }

            $type = $this->docReturnType ? $this->docReturnType : 'void';

            $type = $this->returnType ? $this->returnType : $type;

            $doc->tagReturn($type);

            if ($this->exception) {
                $doc->tagThrows("\Exception");
            }
        });
    }

    /**
     * @return $this
     */
    public function noAutoDoc()
    {
        $this->doc = false;

        return $this;
    }

    protected $compressed = false;

    /**
     * @return $this
     */
    public function compressed()
    {
        $this->compressed = true;

        return $this;
    }

    /**
     * Build entity.
     *
     * @return string
     */
    protected function build(): string
    {
        if ($this->doc === null) {
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

        $data .= $spaces.$this->modifiers.' function '.$this->name.'('.implode(', ', $this->parameters).')'.($this->returnType ? ' : '.$this->returnType : '').$this->eol();
        $data .= $spaces.'{';

        if ($this->data) {
            $data .= $this->eol().$spaces.str_repeat(' ', 4).$this->data.$this->eol().$spaces;
        }

        foreach ($this->lines as $line) {
            if (! $this->compressed) {
                $data .= $this->eol().$spaces.str_repeat(' ', 4).$line;
            } else {
                $data .= $line;
            }
        }

        if (count($this->lines) && ! $this->compressed) {
            $data .= $this->eol().$spaces;
        }

        $data .= '}';

        return $data;
    }
}
