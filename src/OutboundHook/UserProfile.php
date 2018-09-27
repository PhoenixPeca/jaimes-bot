<?php

namespace OutboundHook;

use Supplier\GeneralStatics;
use Supplier\CacheController;
use InboundHook\Hub as InHub;

class UserProfile
{

    private const fbuprofAPI = 'https://graph.facebook.com/v3.1/';
    private const uidCache = './cache/';
    private const cache_expiration = 172800;

    public static function getData($uid, $fields = 'first_name,last_name,name'/* . 
                                          ',profile_pic,locale,timezone,gender'*/) {
        $cache_uid = 'profile-' . $uid;
        $data = json_decode(file_get_contents(self::fbuprofAPI .
                                 $uid . '?fields=' . $fields .
                                 '&access_token=' .
                                 GeneralStatics::getConfig('page_access_token')));
        CacheController::cacheCreate($cache_uid, $data, self::cache_expiration);
        $data = CacheController::cacheFetch($cache_uid, $data,
                                            self::cache_expiration);
        return (isset($data) ? $data : false);
    }

    public static function getSenderData($element) {
        return self::getData(
                    InHub::getHookSenderID()
                )->$element;
    }

}