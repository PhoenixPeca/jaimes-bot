<?php

namespace Cortex;

use OutboundHook\UserProfile;

class PredefinedResponse
{

    public static function initiator($message) {
        return false;
    }

}