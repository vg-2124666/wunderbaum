<?php

    // This block is here so the application can serve static files through the php built-in web server
    $filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if (php_sapi_name() === 'cli-server' && is_file($filename)) {
        return false;
    }
    // end php web server block

    define('DEBUG_MODE', true);

    // Require the default application initialization script
    require "index.php";
