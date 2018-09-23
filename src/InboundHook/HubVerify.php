<?php

namespace InboundHook;

class HubVerify
{

    public static function getChallenge() {
        return (isset($_GET["hub_challenge"]) &&
                self::getMode() && self::getVerifyToken()?
                die($_GET["hub_challenge"]) : false);
    }

    private static function getMode() {
        return (isset($_GET["hub_mode"]) ?
                $_GET["hub_mode"] : false);
    }

    private static function getVerifyToken() {
        return (isset($_GET["hub_verify_token"]) ?
                $_GET["hub_verify_token"] : false);
    }

}