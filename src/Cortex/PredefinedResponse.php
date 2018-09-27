<?php

namespace Cortex;

use OutboundHook\UserProfile;

class PredefinedResponse
{

    public static function initiator($message) {
        return ['Hi ' . UserProfile::getSenderData('name') . '!' .
            'I am sorry, I\'m currently being developed. Most of my features aren\'t that ready for usage yet.',
               ':( :('] ;
    }

}