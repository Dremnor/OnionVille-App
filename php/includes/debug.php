<?php 
    include_once('/config.php');
    if (DEBUG) {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        ini_set("log_errors", 1);
        ini_set("error_log", "/php-error.log");
        error_reporting(E_ALL);
    }