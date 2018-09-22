<?php

namespace InboundHook;

class HubVerify
{

    public static function getMode() {
        return $_GET["hub_mode"];
    }

    public static function getChallenge() {
        return $_GET["hub_challenge"];
    }

    public static function getVerifyToken() {
        return $_GET["hub_verify_token"];
    }

}