<?php

namespace Supplier;

class GeneralStatics
{

    public static function strSanitize($string, $whitelist = array()) {
        foreach($whitelist as $char) {
            $wl .= '\\'.$char;
        }
        $string = preg_replace('/[^a-z0-9-\-\s\#\&'.$wl.']+/i',
                               ' ', $string);
        $string = strtolower(trim($string));
        $string = preg_replace('/\s+/i', ' ', $string);
        return $string;
    }

    public static function getConfig($key) {
        $settings = json_decode(file_get_contents('./config/settings.json'));
        return (isset($settings->$key) ? $settings->$key : false);
    }

    public static function arrRandoMix($array) {
        shuffle($array);
        return $array[array_rand($array)];
    }

    public static function curlify($string, $envars) {
        foreach($envars as $spot=>$data) {
            $string = str_replace('{{{'.$spot.'}}}', $data, $string);
        }
        return $string;
    }

}