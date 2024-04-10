<?php

namespace wwaz\Favigation\Driver\Wordpress;

use wwaz\Favigation\Driver\Wordpress\WordpressArrayAdapter;
use wwaz\Favigation\Driver\Wordpress\WordpressObjectAdapter;

class WordpressBuilder
{
    protected $build;

    protected $renderer;

    public function __construct($data, string $renderer)
    {
        $this->renderer = $renderer;
        $this->build = $this->build($data);
    }

    public function __call($name, $arguments)
    {
        return $this->build->$name(...$arguments);
    }

    protected function build($data)
    {
        foreach ($data as $index => $item) {
            if (is_array($item)) {
                $data[$index] = new WordpressArrayAdapter($item);

            } else if (is_object($item)) {
                $data[$index] = new WordpressObjectAdapter($item);
            }
        }
        return (new \wwaz\Favigation\Builder(
            new \wwaz\Favigation\Collection($data),
            $this->renderer
        ))
            ->getBuild();
    }
}
