<?php

namespace wwaz\Favigation;

use Illuminate\Support\Str;
use wwaz\Favigation\Item as BaseItem;

abstract class ArrayItem extends BaseItem
{
    /**
     * Data.
     * 
     * @var array
     */
    protected $data;

    /**
     * Constructor.
     * 
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    protected function setValue($key, $value)
    {
        $this->data[$this->getKeys()[$key]] = $value;
        return $this;
    }

    protected function getValue($key)
    {
        if( isset($this->data[$this->getKeys()[$key]]) ){
            return $this->data[$this->getKeys()[$key]];
        }
        return null;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), $this->data);
    }
    
    public function getId(): int | string
    {
        return $this->getValue('id');
    }

    public function setId(int | string $val): self
    {
        return $this->setValue('id', $val);
    }

    public function getParentId(): int | string | null
    {
        return $this->getValue('parentId');
    }

    public function setParentId(int $val): self
    {
        return $this->setValue('parentId', $val);
    }

    public function getOrdering(): int | null
    {
        return $this->getValue('ordering');
    }

    public function setOrdering(int $val): self
    {
        return $this->setValue('ordering', $val);
    }

    public function getTitle(): mixed
    {
        return $this->getValue('title');
    }

    public function setTitle(mixed $val): self
    {
        return $this->setValue('title', $val);
    }

    public function getUrl(): string | null
    {
        return $this->getValue('url');
    }

    public function setUrl(string $val): self
    {
        return $this->setValue('url', $val);
    }

    public function getTarget(): string | null
    {
        return $this->getValue('target');
    }

    public function setTarget(string $val): self
    {
        return $this->setValue('target', $val);
    }

    public function getIcon(): string | null 
    {
        return $this->getValue('icon');
    }

    public function setIcon(string $val): self
    {
        return $this->setValue('icon', $val);
    }
}