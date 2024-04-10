<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

use Illuminate\Support\Str;

$path = dirname(__FILE__) . '/files/backend-navigation.json';
$array = json_decode(file_get_contents($path), true);

$item = new wwaz\Favigation\Driver\Backend\BackendArrayAdapter($array[0]);

die(print_r($item->toArray()));