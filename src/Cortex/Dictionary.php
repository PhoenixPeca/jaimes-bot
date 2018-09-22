<?php

namespace Cortex;

class Dictionary
{

    public static function defFetch($word) {
        $def = self::dictInterface($word, 'def');
        return (isset($def) ? $def : false);
    }

    public static function exFetch($word) {
        $def = self::dictInterface($word, 'example');
        return (isset($def) ? $def : false);
    }

    public static function synFetch($word) {
        $interface = self::dictInterface($word, 'synonyms');
        foreach ($interface as $synonyms) {
            foreach ($synonyms as $synonym) {
                if (!empty($synonym)) {
                    $syn[] = $synonym;
                }
            }
        }
        return (isset($syn) ? $syn : false);
    }

    private static function dictInterface($word, $element) {
        $dictionaries = array('./cortex/dictionary/'.strtolower($word{0}).'.json',
                              './cortex/dictionary/misc.json');
        foreach ($dictionaries as $dictionary) {
            if (!file_exists($dictionary))
                continue;
            $words = json_decode(file_get_contents($dictionary));
            foreach ($words as $item=>$prop) {
                if (strtolower($item) == strtolower($word)) {
                    foreach ($prop->meanings as $item=>$val) {
                        if ($el = $val->$element) {
                            $elements[] = $el;
                        }
                    }
                }
            }
        }
        return (isset($elements) ? $elements : false);
    }

}