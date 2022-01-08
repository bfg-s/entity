<?php

namespace Bfg\Entity\Core\Wrappers;

/**
 * Class CommentWrapper.
 * @package Bfg\Entity\Core\Wrappers
 */
class CommentWrapper extends Wrapper
{
    /**
     * @param string $data
     * @return string
     */
    protected function wrap(string $data): string
    {
        $lines = explode("\n", $data);

        if (count($lines) > 1) {
            $return = $this->space().'/*'.$this->eol();

            foreach ($lines as $item) {
                $return .= $this->space().$item.$this->eol();
            }

            return $return.$this->space().'*/';
        } else {
            return $this->space().'// '.$lines[0];
        }
    }
}
