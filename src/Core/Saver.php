<?php

namespace Bfg\Entity\Core;

use Illuminate\Contracts\Support\Renderable;
use Bfg\Entity\Core\Savers\Driver;
use Bfg\Entity\Core\Savers\File;

/**
 * Class Saver
 * @package Bfg\Entity\Core
 */
final class Saver  {

    /**
     * Saver type list
     *
     * @var array
     */
    protected static $savers = [
        "file" => File::class
    ];

    /**
     * @var mixed
     */
    private $save_subject;

    /**
     * @var Driver
     */
    private $saver;

    /**
     * @var array
     */
    private $insert_mode = ["mode" => "append", "line" => null];

    /**
     * Saver constructor.
     *
     * @param mixed $save_subject
     * @param string $saver
     * @throws \Exception
     */
    public function __construct($save_subject, string $saver = "file")
    {
        $this->save_subject = $save_subject;

        if (!static::$savers[$saver]) {

            throw new \Exception("Saver [{$saver}] not found!");
        }

        else {

            $this->saver = new static::$savers[$saver];
        }

        $this->saver->setSubject($save_subject);
    }

    /**
     * @return $this
     */
    public function prepend()
    {
        $this->insert_mode["mode"] = "prepend";

        return $this;
    }

    /**
     * @param $line
     * @return $this
     */
    public function before($line)
    {
        $this->insert_mode["mode"] = "before";

        $this->insert_mode["line"] = $line;

        return $this;
    }

    /**
     * @param $line
     * @return $this
     */
    public function after($line)
    {
        $this->insert_mode["mode"] = "after";

        $this->insert_mode["line"] = $line;

        return $this;
    }

    /**
     * @return $this
     */
    public function append()
    {
        $this->insert_mode["mode"] = "append";

        return $this;
    }

    /**
     * @param string|Renderable $data
     * @return $this
     */
    public function data($data)
    {
        $this->saver->setData($data, $this->insert_mode);

        return $this;
    }

    /**
     * Run save
     * @param null|string|Renderable $data
     * @return int
     */
    public function save($data = null)
    {
        if ($data) {

            $this->data($data);
        }

        return $this->saver->save();
    }
}
