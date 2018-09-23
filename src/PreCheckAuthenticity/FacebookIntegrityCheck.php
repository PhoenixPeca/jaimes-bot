<?php

namespace PreCheckAuthenticity;

use Supplier\GeneralStatics;
use InboundHook\HubVerify;

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
           $AllHeaders{'X-Hub-Signature'} == self::payloadSignature('sha1')) &&
           !HubVerify::getChallenge()) {
            die(base64_decode('PCFET0NUWVBFIEhUTUwgUFVCTElDICItLy9JRVRGLy9EVEQ'.
                              'gSFRNTCAyLjAvL0VOIj48aHRtbD48aGVhZD48dGl0bGU+V2'.
                              'VsY29tZSBTdHJhbmdlciE8L3RpdGxlPjwvaGVhZD48Ym9ke'.
                              'T48aDE+V2VsY29tZTwvaDE+PHA+VGhpcyBpcyBUaGUgRW5k'.
                              'cG9pbnQgJm1kYXNoOyBhIHBvcnRhbCB0byB0aGUgYmVhdXR'.
                              'pZnVsIG1pbmQgb2YgY29tcHV0ZXIgYXJ0aWZpY2lhbCBpbn'.
                              'RlbGxpZ2VuY2UuPGJyIC8+PC9wPjxocj48YWRkcmVzcz5KY'.
                              'WltZXMgQm90ICg8YSBocmVmPSJodHRwczovL2dpdGh1Yi5j'.
                              'b20vUGhvZW5peFBlY2EvamFpbWVzLWJvdCI+amFpbWVzLmF'.
                              'pLmJvdDwvYT4pIGZvciBGYWNlYm9vayBNZXNzZW5nZXI8L2'.
                              'FkZHJlc3M+PC9ib2R5PjwvaHRtbD4='));
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