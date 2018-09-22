<?php

namespace PreCheckAuthenticity;

use Supplier\GeneralStatics;

class FacebookIntegrityCheck
{

    public function __construct() {
        $AllHeaders = getallheaders();
        if (!self::isSecuredCom()) {
            header('HTTP/1.0 403 Forbidden');
            die('HTTPS is required in this communication endpoint.');
        }
        if (!($AllHeaders{'User-Agent'} == 'facebookexternalua' &&
           $AllHeaders{'Content-Type'} == 'application/json' &&
           $AllHeaders{'X-Hub-Signature'} == self::payloadSignature('sha1'))) {
            #header("HTTP/1.1 401 Unauthorized");
            #die('You are not allowed to access this communication endpoint.');
            die('<html><head><title>Welcome Stranger!</title></head><body><h1>Welcome</h1>'.
                '<p>This is The Endpoint &mdash; a portal to the beautiful mind of compute'.
                'r artificial intelligence.</p><hr><address>Jaimes Bot (<a href="https://g'.
                'ithub.com/PhoenixPeca/jaimes-bot">jaimes.ai.bot</a>) for Facebook Messeng'.
                'er</address></body></html>');
        }
        return true;
    }

    private static function payloadSignature($type) {
        return $type . '=' . hash_hmac($type,
                               file_get_contents('php://input'),
                               GeneralStatics::getConfig('app_secret'));
    }

    private static function isSecuredCom() {
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
            $ssl = false;
        } else {
            $ssl = true;
        }
        return $ssl;
    }

}