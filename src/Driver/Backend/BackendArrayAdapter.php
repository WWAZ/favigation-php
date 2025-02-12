<?php
namespace wwaz\Favigation\Driver\Backend;

use \wwaz\Favigation\ArrayItem;

class BackendArrayAdapter extends ArrayItem
{
    public function getKeys(): array
    {
        return [
            'id'       => 'id',
            'parentId' => 'parentId',
            'ordering' => 'order',
            'title'    => 'name',
            'url'      => 'slug',
            'target'   => null,
            'icon'     => 'icon',
        ];
    }
}
