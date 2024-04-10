<?php

namespace wwaz\Favigation\Driver\Wordpress;

use \wwaz\Favigation\Item as BaseItem;

class WordpressObjectAdapter extends BaseItem
{
    protected $item;
    
    protected $keys = [
        'id' => 'ID',
        'parentId' => 'menu_item_parent',
        'ordering' => 'menu_order',
        'title' => 'title',
        'url' => 'url',
        'target' => 'target',
        'icon' => null
    ];
    
    public function __construct($item)
    {
        parent::__construct();
        $this->item = $item;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), $this->item->toArray());
    }
    
    public function getId(): int | string
    {
        return $this->item->ID;
    }

    public function setId(int | string $val): self
    {
        $this->item->ID = $val;
        return $this;
    }

    public function getParentId(): int | string | null
    {
        return $this->item->menu_item_parent;
    }

    public function setParentId(int $val): self
    {
        $this->item->menu_item_parent = $val;
        return $this;
    }

    public function getOrdering(): int
    {
        return $this->item->menu_order;
    }

    public function setOrdering(int $val): self
    {
        $this->item->menu_order = $val;
        return $this;
    }

    public function getTitle(): mixed
    {
        return $this->item->title;
    }

    public function setTitle(mixed $val): self
    {
        $this->item->title = $val;
        return $this;
    }

    public function getUrl(): string | null
    {
        return $this->item->url;
    }

    public function setUrl(string $val): self
    {
        $this->item->url = $val;
        return $this;
    }

    public function getTarget(): string | null
    {
        return $this->item->target;
    }

    public function setTarget(string $val): self
    {
        $this->item->target = $val;
        return $this;
    }

    public function getIcon(): string | null 
    {
        return null;
    }

    public function setIcon(string $val): self
    {
        return $this;
    }
}
