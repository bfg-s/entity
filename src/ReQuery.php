<?php

namespace Bfg\Entity;

/**
 * Class ReQuery.
 * @package Bfg\Entity
 */
class ReQuery
{
    /**
     * @var array
     */
    protected array $filter = [];

    /**
     * ReQuery constructor.
     * @param $source
     */
    public function __construct(
       protected $source
    ) {
    }

    /**
     * @param  string  $query Example: upper_case|test>>int|float:id,test|hay:name,int:role_id,roles(id,name,users(id,name)),test
     * @param  array|null  $global
     * @return $this
     */
    public function setFilter(string $query, array $global = null)
    {
        $matches = [];

        $current_global = [];

        if (preg_match_all('/((([0-9A-Za-z\_\-\s\|]+)([\>]{1,2}))*(([0-9A-Za-z\_\-\s\|]+)\:)*([0-9A-Za-z\_\-\s]+)(\((.*)\))?)/', $query, $matches, PREG_SET_ORDER, 0)) {
            foreach ($matches as $match) {
                if ($match[3]) {
                    $current_global = explode('|', $match[3]);

                    if ($match[4] == '>>') {
                        if ($global) {
                            $global = array_unique(array_merge($global, $current_global));
                        } else {
                            $global = $current_global;
                        }
                    }
                }

                if ($match[7]) {
                    $add_data = [
                        'field' => trim($match[7]),
                    ];

                    $g = array_merge($global ?? $current_global);

                    if ($match[6]) {
                        $add_data['filter'] = array_unique(array_merge($g, explode('|', $match[6])));
                    } elseif ($g) {
                        $add_data['filter'] = $g;
                    }

                    if (isset($match[9]) && $match[9]) {
                        $add_data['child'] = $this->parse($match[9], $global);
                    }

//                    if (isset($match[8]) && $match[8]) {
//
//                        $add_data['child'] = $this->parse(substr($match[8], 1, -1), $global);
//                    }

                    $this->filter[] = $add_data;
                }
            }
        }

        return $this;
    }
}
