<?php
/**
 * You need to use --prefer-dist. And if there is a dist version for your repository it will be downloaded.
 * You also can use --no-dev flag to exclude libraries that listed in require-dev section of packages.
 * These libraries maybe useful only for development.
 */

//anti favicon.ico without apache
($_SERVER['REQUEST_URI'] !== '/favicon.ico')
    ? require 'app/bootstrap.php'
    : die;
