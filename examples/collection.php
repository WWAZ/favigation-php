<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

use Illuminate\Support\Str;

$path = dirname(__FILE__) . '/files/wp-navigation.json';
$array = json_decode(file_get_contents($path), true);


$item = new wwaz\Favigation\Driver\Wordpress\WordpressArrayAdapter($array[0]);

$collection = new wwaz\Favigation\Collection([$item]);

print_r($collection->toArray());