<?php

namespace Bfg\Entity\Core\Entities\Helpers;

use Illuminate\Contracts\Support\Renderable;

/**
 * Trait DocumentorHelper.
 * @package Bfg\Entity\Core\Entities\Helpers
 */
trait DocumentorHelper
{
    /**
     * Pars description.
     *
     * @param string|Renderable $doc
     * @param string $glue
     * @return string
     */
    public static function parseDescription($doc, string $glue = "\n")
    {
        if ($doc instanceof Renderable) {
            $doc = $doc->render();
        } else {
            $doc = (string) $doc;
        }

        if (preg_match('/\*\s([^\@.]+\n)/m', $doc, $matches)) {
            unset($matches[0]);

            return trim(trim(
                str_replace("\n", '', implode($glue, $matches)),
                '*. '
            ), " \t\n\r\0\x0B*");
        }

        return '';
    }

    /**
     * Parse return tag.
     *
     * @param string|Renderable $doc
     * @return string
     */
    public static function parseReturn($doc)
    {
        if ($doc instanceof Renderable) {
            $doc = $doc->render();
        } else {
            $doc = (string) $doc;
        }

        if (preg_match('/@return\\s([\\_a-zA-Z\\\\\|\s\[\]]+)/m', $doc, $matches)) {
            return isset($matches[1]) ? trim($matches[1]) : '';
        }

        return '';
    }

    /**
     * @param  string  $doc
     * @param  string  $var_name
     * @return string
     */
    public static function get_variable(string $doc, string $var_name)
    {
        if ($doc instanceof Renderable) {
            $doc = $doc->render();
        } else {
            $doc = (string) $doc;
        }

        if (preg_match('/@'.$var_name.'\s(.*)/m', $doc, $matches)) {
            return isset($matches[1]) ? trim($matches[1]) : '';
        }

        return '';
    }

    /**
     * @param  string  $doc
     * @param  string  $var_name
     * @return string
     */
    public static function get_variables(string $doc)
    {
        if ($doc instanceof Renderable) {
            $doc = $doc->render();
        } else {
            $doc = (string) $doc;
        }

        $doc = explode("\n", $doc);

        $result = [];

        foreach ($doc as $item) {
            if (preg_match('/\*\s+@([a-zA-Z\-\_]+)\s+(.*)/', $item, $m)) {
                $result[$m[1]] = $m[2];
            }
        }

        return $result;
    }
}
