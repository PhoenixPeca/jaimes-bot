<?php

namespace OutboundHook;

use Supplier\GeneralStatics;
use InboundHook\Hub as InHub;

class UserProfile
{

    private const uidCache = './cache/';

    public static function getData($uid, $fields = 'first_name,last_name' . 
                                          ',profile_pic,locale,timezone,gender') {
        if (!file_exists(self::uidCache))
            mkdir(self::uidCache);
        $cacheFile = (self::uidCache . 'profile-' . $uid . '.json');
        if (file_exists($cacheFile) && time() - filemtime($cacheFile) <= 172800) {
            $fetch = file_get_contents($cacheFile);
        } else {
            $data = file_get_contents(
                        'https://graph.facebook.com/v2.12/' . 
                        $uid . '?fields=' . $fields . 
                        '&access_token=' . 
                        GeneralStatics::getConfig('page_access_token'));
            file_put_contents($cacheFile,
                              json_encode(
                                  json_decode($data),
                                  JSON_PRETTY_PRINT));
            $fetch = $data;
        }
        return (isset($fetch) ? json_decode($fetch) : false);
    }

    public static function getSenderData($element) {
        return self::getData(
                    InHub::getHookSenderID()
                )->$element;
    }

}