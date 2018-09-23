<?php

namespace Supplier;

use InboundHook\Hub as InHub;

class Bootstrap
{

    public function __construct() {
        new InHub;
    }

}