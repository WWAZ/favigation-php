<?php

namespace wwaz\Favigation;

use Illuminate\Support\Str;
use wwaz\Favigation\Interface\ItemInterface;

abstract class Item implements ItemInterface
{
    /**
     * Adapter keys.
     *
     * @var array
     */
    protected $keys = [
        'id' => null,
        'parentId' => null,
        'ordering' => null,
        'title' => null,
        'url' => null,
        'target' => null,
        'icon' => null,
    ];

    /**
     * Item level.
     *
     * @var int
     */
    protected $level;

    /**
     * Item path.
     *
     * @var string
     */
    protected $path;

    /**
     * Item children.
     *
     * @var array
     */
    protected $children = [];

    /**
     * Parent item.
     *
     * @var Item
     */
    protected $parent;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->validateKeys();
    }

    /**
     * Checks if required adapter keys are defined.
     *
     * @throws Exception
     */
    protected function validateKeys(): void
    {
        $required = ['id', 'title'];
        foreach ($required as $r) {
            if (!isset($this->keys[$r]) || is_null($this->keys[$r])) {
                throw new \Exception('Key "' . $r . '" must be set');
            }
        }
    }

    /**
     * Returns adapter keys.
     *
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * Returns true when item is node.
     *
     * @return bool
     */
    public function isNode(): bool
    {
        return count($this->getChildren()) > 0 ? true : false;
    }

    /**
     * Returns adapter keys as array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'parentId' => $this->getParentId(),
            'ordering' => $this->getOrdering(),
            'title' => $this->getTitle(),
            'url' => $this->getUrl(),
            'target' => $this->getTarget(),
            'icon' => $this->getIcon(),
        ];
    }

    /**
     * Sets path.
     *
     * @param array | string $path
     */
    public function setPath(array | string $path): self
    {
        $this->path = is_array($path) ? implode(',', $path) : $path;
        return $this;
    }

    /**
     * Returns path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets level.
     *
     * @param int $level
     */
    public function setLevel(int $level): self
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Returns level.
     *
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Sets parent.
     *
     * @param wwaz\Favigation\Item $parent
     */
    public function setParent(\wwaz\Favigation\Item $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Returns parent.
     *
     * @return wwaz\Favigation\Item | null
     */
    public function getParent(): \wwaz\Favigation\Item | null
    {
        return $this->parent;
    }

    /**
     * Sets children.
     *
     * @param array $items
     */
    public function setChildren(array $items): self
    {
        $this->children = $items;
        return $this;
    }

    /**
     * Adds one children.
     *
     * @param wwaz\Favigation\Ite
     */
    public function addChild(\wwaz\Favigation\Item $item): self
    {
        $this->children[] = $item;
        return $this;
    }

    /**
     * Returns children.
     *
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Runs given method against this item and it's children
     * and returns array of results.
     *
     * @param string $method
     * @return array
     */
    public function getRecursive($method = 'getId')
    {
        return $this->runGetRecursive($this, $method);
    }

    /**
     * @see getRecursive()
     */
    protected function runGetRecursive($item, $method, $res = [])
    {
        if (count($item->getChildren()) > 0) {
            foreach ($item->getChildren() as $index => $child) {
                $res = $this->runGetRecursive($child, $method, $res);
            }
        }
        $val = $item->$method();
        if (!in_array($val, $res)) {
            $res[] = $val;
        }
        return $res;
    }
}
