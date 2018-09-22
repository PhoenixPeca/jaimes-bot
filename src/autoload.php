<?php

foreach (glob("src/*/*.php") as $class) {
    if (file_exists($class))
        include $class;
}