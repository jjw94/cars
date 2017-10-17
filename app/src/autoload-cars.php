<?php

spl_autoload_register(function ($class_name) {
    $file = str_replace("\\", "/", $class_name);
    require_once dirname(__DIR__) .  "/src/" . $file . ".php";
});
