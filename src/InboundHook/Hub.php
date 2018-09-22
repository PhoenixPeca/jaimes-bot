<?php

namespace InboundHook;

use PreCheckAuthenticity\FacebookIntegrityCheck;
use OutboundHook\UserProfile;
use OutboundHook\Hub as OutHub;

class Hub
{

    public function __construct() {
        new FacebookIntegrityCheck;
        if (self::getMid() && !self::isEcho()) {
            $userData = UserProfile::getData(self::getHookSenderID());
            date_default_timezone_set(
                timezone_name_from_abbr('', $userData->timezone*3600, false));
            new OutHub(
                self::getHookData()->entry{0}->messaging{0}->message,
                self::getHookSenderID()
            );
        } elseif ($HubVerify = HubVerify::getChallenge())
            die($HubVerify);
    }

    public static function getHookData() {
        return json_decode(file_get_contents('php://input'));
    }

    public static function getMid() {
        return self::getHookData()->entry{0}->messaging{0}->message->mid;
    }

    public static function isEcho() {
        return self::getHookData()->entry{0}->messaging{0}->message->is_echo;
    }

    public static function getHookMessageText() {
        return self::getHookData()->entry{0}->messaging{0}->message->text;
    }

    public static function getHookSenderID() {
        return self::getHookData()->entry{0}->messaging{0}->sender->id;
    }

    public static function getHookRecipientID() {
        return self::getHookData()->entry{0}->messaging{0}->recipient->id;
    }

    public static function getHookMessageTimestamp() {
        return self::getHookData()->entry{0}->messaging{0}->timestamp;
    }

}