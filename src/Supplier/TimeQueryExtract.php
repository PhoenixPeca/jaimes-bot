<?php

namespace Supplier;

use Cortex\Time;

class TimeQueryExtract
{

    private const TimeRules = '/^(?:what(?:(?:\'s| is) the)?)?(?: ?time)(?:(?: ' .
                              'here| is it)?(?: today| now)?(?: in (.+))?)?$/i';

    public static function getTime($string) {
        preg_match(self::TimeRules, GeneralStatics::strSanitize($string), $matches);
        if (!empty($matches{0})) {
            if (!empty($matches{1})) {
                if ($place_matched = Time::geoCode($matches{1})->addr) {
                    $matches{1} = $place_matched;
                } else {
                    $matches = true;
                }
            } else {
                unset($matches{1});
            }
        } else {
            unset($matches);
        }
        return (isset($matches) ? $matches : false);
    }

}