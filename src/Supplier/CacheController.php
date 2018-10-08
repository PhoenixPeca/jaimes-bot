<?php

namespace Supplier;

class CacheController
{

    private const CacheDir = './cache/';
    
    private static function initCache($cacheID) {
        $cacheFile = self::CacheDir . $cacheID . '.json';
        if (!file_exists(self::CacheDir)) {
            mkdir(self::CacheDir);
            file_put_contents(self::CacheDir . '.gitignore',
                              "*\n!.gitignore");
        }
        if (empty(file_get_contents($cacheFile))) {
            unlink($cacheFile);
        }
        return $cacheFile;
    }

    public static function cacheCreate($cacheID, $data, $replace_after = 172800) {
        $cacheFile = self::initCache($cacheID);
        if (!empty($cacheID) && !empty($data)) {
            if (time() - filemtime($cacheFile) > $replace_after) {
                unlink($cacheFile);
                file_put_contents($cacheFile, json_encode($data,
                                               JSON_PRETTY_PRINT));
            }
        }
        return true;
    }

    public static function cacheDestroy($cacheID) {
        return (unlink(self::CacheDir . $cacheID. '.json') ? true : false);
    }

    public static function cacheFetch($cacheID, $prev_data, $fetch_before = 172800) {
        $cacheFile = self::initCache($cacheID);
        if (file_exists($cacheFile) &&
                            time() - filemtime($cacheFile) < $fetch_before) {
            $data = json_decode(file_get_contents($cacheFile));
            if (empty($data)) {
                $data = $prev_data;
                unlink($cacheFile);
            }
        } else {
            $data = $prev_data;
        }
        return (!empty($data) ? $data : false);
    }

}