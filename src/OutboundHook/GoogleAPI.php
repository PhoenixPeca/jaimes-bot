<?php

namespace OutboundHook;

use Supplier\GeneralStatics;
use Supplier\CacheController;

class GoogleAPI
{

    private const GOOGLE_API = 'https://maps.googleapis.com/maps/api/';

    public static function geoCode($place) {
        $cache_uid = 'geocode-'.preg_replace('/[^a-zA-Z0-9_]+/m', '_', $place);
        $data = json_decode(file_get_contents(self::GOOGLE_API . 'geocode/json' .
               '?address=' . urlencode($place) .
               '&key=' . GeneralStatics::getConfig('google_api_key')));
        CacheController::cacheCreate($cache_uid, $data);
        $api_data = CacheController::cacheFetch($cache_uid, $data);
        if (!empty($api_data->results{0}->formatted_address) &&
                in_array('political', $api_data->results{0}->types)) {
            $return->addr = $api_data->results{0}->formatted_address;
            $return->lati = $api_data->results{0}->geometry->location->lat;
            $return->long = $api_data->results{0}->geometry->location->lng;
        } else {
            CacheController::cacheDestroy($cache_uid);
        }
        return (isset($return) ? $return : false);
    }

    public static function coordToTimezoneID($lat, $lng) {
        $cache_uid = 'tzdata-'.preg_replace('/[^a-zA-Z0-9_]+/m', '_', $lat.','.$lng);
        $data = json_decode(file_get_contents(self::GOOGLE_API . 'timezone/json' .
               '?location=' . $lat . ',' . $lng .
               '&timestamp=' . time() .
               '&key=' . GeneralStatics::getConfig('google_api_key')));
        CacheController::cacheCreate($cache_uid, $data);
        $api_data = CacheController::cacheFetch($cache_uid, $data);
        return (isset($api_data->timeZoneId) ? $api_data->timeZoneId : false);
    }

}