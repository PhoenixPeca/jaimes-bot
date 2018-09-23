<?php

spl_autoload_register(function ($className) {
    $ds = DIRECTORY_SEPARATOR;
    $dir = __DIR__;
    $className = str_replace('\\', $ds, $className);
    $file = "{$dir}{$ds}{$className}.php";
    if (is_readable($file)) require_once $file;
});