<?php

namespace Bfg\Entity\Core\Savers\Modes;

use Illuminate\Contracts\Support\Renderable;

/**
 * Class Mode
 *
 * @package Bfg\Entity\Core\Savers\Modes
 */
abstract class Mode implements Renderable
{
    /**
     * @var string
     */
    protected $data;

    /**
     * Reflection object
     *
     * @var object
     */
    protected $ref;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var array
     */
    protected $position;

    /**
     * @var int
     */
    protected $replace_line_from = 0;

    /**
     * @var string
     */
    protected $replace_line_to = 0;

    /**
     * Mode constructor.
     *
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public static function create(string $data)
    {
        return new static($data);
    }

    /**
     * @param $ref
     * @return $this
     */
    public function ref($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function file(string $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @param array $position
     * @return $this
     */
    public function position(array $position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->data = $this->build($this->data);

        $origin_data = $this->getHavingData();

        if (empty(trim($origin_data))) {

            $this->data = "\n" . $this->data . "\n";
        }

        if (!empty($origin_data)) {

            $fline = explode("\n", $origin_data)[0];

            $tab = str_repeat(" ", 4);

            if (!empty($fline) && preg_match('/([\s\t]+).*/', $fline, $m)) { $tab = $m[1]; }

            $lines = [];

            foreach (explode("\n", trim($this->data)) as $item) {

                $lines[] = $tab . $item;
            }

            $this->data = implode("\n", $lines);
        }

        $this->havingData($origin_data);

        return is_file($this->file) ? $this->insert($this->data, $origin_data, file_get_contents($this->file)) : $this->data;
    }

    /**
     * @param string $data_in
     */
    private function havingData(string $data_in)
    {
        if (!empty($data_in) && isset($this->position['mode']) && $this->position['mode'] !== 'new') {

            if ($this->position['mode'] === 'append') {

                $this->data = $data_in . "\n" . $this->data;
            }

            else if ($this->position['mode'] === 'prepend') {

                $this->data = $this->data . "\n" . $data_in;
            }

            else if ($this->position['mode'] === 'after') {

                $lines = [];

                foreach (explode("\n", $data_in) as $item) {

                    $lines[] = $item;

                    if (trim($item) === trim($this->position['line'])) {

                        $lines[] = $this->data;
                    }
                }

                $this->data = implode("\n", $lines);
            }

            else if ($this->position['mode'] === 'before') {

                $lines = [];

                foreach (explode("\n", $data_in) as $item) {

                    if (trim($item) === trim($this->position['line'])) {

                        $lines[] = $this->data;
                    }

                    $lines[] = $item;
                }

                $this->data = implode("\n", $lines);
            }
        }
    }

    /**
     * @param string $data
     * @return string
     */
    abstract public function build (string $data) : string;

    /**
     * @return string
     */
    abstract public function getHavingData () : string;

    /**
     * Insert data
     *
     * @param string $data
     * @param string $origin
     * @param string $file_data
     * @return string
     */
    abstract protected function insert (string $data, string $origin, string $file_data) : string;
}
