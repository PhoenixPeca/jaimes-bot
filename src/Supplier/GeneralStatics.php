<?php

namespace Supplier;

use OutboundHook\UserProfile;

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
            $string = str_replace('{{{'.$spot.'}}}', self::percentify($data),
                                  $string);
        }
        return $string;
    }

    public static function percentify($string) {
        preg_match_all('/%%(sender|settings)\.(.+)%%/U', $string,
                       $matches, PREG_SET_ORDER);
        foreach ($matches as $prefix=>$postfix) {
            if ($postfix{1} == 'sender') {
                $replacement = UserProfile::getSenderData($postfix{2});
            } elseif ($postfix{1} == 'settings') {
                $replacement = self::getConfig($postfix{2});
            }
            $str = str_replace($postfix{0}, $replacement, $string);
        }
        return $str;
    }

    public static function basicStrEscape($string) {
        $string = str_replace('\n', "\n", $string);
        $string = explode('\b', $string);
        return $string;
    }

    public static function flattenArray($array) {
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
        return (!empty($return) ? $return : false);
    }

}