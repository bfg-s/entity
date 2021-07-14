<?php

namespace Bfg\Entity\Core\Savers\Modes;

/**
 * Trait DefaultGetHavingData
 * @package Bfg\Entity\Core\Savers\Modes
 */
trait DefaultGetHavingData
{
    /**
     * @return string
     */
    public function getHavingData(): string
    {
        if ($this->ref && $this->file && is_file($this->file)) {

            $this->replace_line_from = $this->ref->getStartLine();

            $this->replace_line_to = $this->ref->getEndLine();

            return file_lines_get_contents($this->file, $this->replace_line_from, $this->replace_line_to);
        }
    }
}
