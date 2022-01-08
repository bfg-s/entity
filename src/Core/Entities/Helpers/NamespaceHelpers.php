<?php

namespace Bfg\Entity\Core\Entities\Helpers;

/**
 * Trait NamespaceHelpers.
 * @package Bfg\Entity\Core\Entities\Helpers
 */
trait NamespaceHelpers
{
    /**
     * @param string $namespace
     * @param int $level
     * @return string
     */
    public static function lastSegment(string $namespace, int $level = 1)
    {
        if ($level < 1) {
            $level = 1;
        }

        $parts = explode('\\', $namespace);

        $count = count($parts);

        if ($level > $count) {
            $level = $count;
        }

        if (! $count) {
            return '';
        } elseif ($count == 1) {
            return $parts[0];
        } else {
            return $parts[$count - $level];
        }
    }

    /**
     * @param string $namespace
     * @param int $level
     * @return string
     */
    public static function bodySegment(string $namespace, int $level = 1)
    {
        if ($level < 1) {
            $level = 1;
        }

        $parts = explode('\\', $namespace);

        $count = count($parts);

        if ($level > $count) {
            $level = $count;
        }

        $iterators = $count - $level;

        if ($iterators < 0) {
            $iterators = 0;
        }

        $new = [];

        for ($i = 0; $i < $iterators; $i++) {
            $new[] = $parts[$i];
        }

        return implode('\\', $new);
    }
}
