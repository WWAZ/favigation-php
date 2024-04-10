<?php

namespace wwaz\Favigation\Driver\Wordpress;

use \wwaz\Favigation\ArrayItem;

class WordpressArrayAdapter extends ArrayItem
{
    protected $keys = [
        'id' => 'ID',
        'parentId' => 'menu_item_parent',
        'ordering' => 'menu_order',
        'title' => 'title',
        'url' => 'url',
        'target' => 'target',
        'icon' => null
    ];
}
