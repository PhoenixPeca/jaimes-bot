<?php

namespace Cortex;

use Supplier\GeneralStatics;
use OutboundHook\GoogleAPI;

class Time
{

    public static function getFinalTime($matches,
                                        $format = 'l jS \of F Y h:i:s A') {
        if (isset($matches{0}) && isset($matches{1})) {
            return self::getTZTimeFormatted(
                    GoogleAPI::coordToTimezoneID(GoogleAPI::geoCode($matches{1})->lati,
                                            GoogleAPI::geoCode($matches{1})->long),
                    $format);
        } elseif (isset($matches{0})) {
            return date($format, time());
        }
        return false;
    }

    public static function getTZTimeFormatted($timezone, $format = 'Y-m-d H:i:s') {
        $date = new \DateTime("now", new \DateTimeZone($timezone));
        return $date->format($format);
    }

}