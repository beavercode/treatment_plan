<?php
/**
 * You need to use --prefer-dist. And if there is a dist version for your repository it will be downloaded.
 * You also can use --no-dev flag to exclude libraries that listed in require-dev section of packages.
 * These libraries maybe useful only for development.
 */

version_compare(phpversion(), '5.4.0', '>=') ?: die('PHP 5.4.0+ required.');
//anti favicon.ico without apache
($_SERVER['REQUEST_URI'] !== '/favicon.ico')
    ? require 'app/bootstrap_new.php'
    : die;
