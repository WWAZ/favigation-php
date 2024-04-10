<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

$path = dirname(__FILE__) . '/files/wp-navigation.json';
$array = json_decode(file_get_contents($path), true);

$item = new wwaz\Favigation\Driver\Wordpress\WordpressObjectAdapter( new WordpressMenuItem($array[0]) );

class WordpressMenuItem
{
    protected $data;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function toArray()
    {
        return $this->data;
    }
}

die(print_r($item->toArray()));
