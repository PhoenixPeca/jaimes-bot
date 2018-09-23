<?php

namespace OutboundHook;

use Supplier\GeneralStatics;

class GoogleAPI
{

    private const GOOGLE_API = 'https://maps.googleapis.com/maps/api/';

    public static function geoCode($place) {
        $cache_file = './cache/geocode-'.preg_replace('/[^a-zA-Z0-9_]+/m',
                                                     '_', $place) . '.json';
        $api = self::GOOGLE_API . 'geocode/json' .
               '?address=' . urlencode($place) .
               '&key=' . GeneralStatics::getConfig('google_api_key');
        if (file_exists($cache_file)) {
            $api = $cache_file;
        }
        $api_data = json_decode(file_get_contents($api));
        if (!empty($api_data->results{0}->formatted_address) &&
            !file_exists($cache_file)) {
            file_put_contents($cache_file, json_encode($api_data), JSON_PRETTY_PRINT);
        }
        if (!empty($api_data->results{0}->formatted_address) &&
                in_array('political', $api_data->results{0}->types)) {
            $return->addr = $api_data->results{0}->formatted_address;
            $return->lati = $api_data->results{0}->geometry->location->lat;
            $return->long = $api_data->results{0}->geometry->location->lng;
        }
        return (isset($return) ? $return : false);
    }

    public static function coordToTimezoneID($lat, $lng) {
        $cache_file = './cache/tzdata-'.preg_replace('/[^a-zA-Z0-9_]+/m',
                                                     '_', $lat.','.$lng) . '.json';
        $api = self::GOOGLE_API . 'timezone/json' .
               '?location=' . $lat . ',' . $lng .
               '&timestamp=' . time() .
               '&key=' . GeneralStatics::getConfig('google_api_key');
        if (file_exists($cache_file)) {
            $api = $cache_file;
        }
        $api_data = json_decode(file_get_contents($api));
        if (!file_exists($cache_file) && !empty($api_data->timeZoneId)) {
            file_put_contents($cache_file, json_encode($api_data), JSON_PRETTY_PRINT);
        }
        return (isset($api_data->timeZoneId) ? $api_data->timeZoneId : false);
    }

}