<?php
namespace wwaz\Favigation;

class Collection extends \Illuminate\Support\Collection
{
    /**
     * Constructor.
     *
     * @param array $items
     */
    public function __construct(array $items)
    {
        if (! ($items[0] instanceof \wwaz\Favigation\Interface\ItemInterface)) {
            throw new \Exception('Collection takes only objectes implemnting wwaz\Favigation\Interface\ItemInterface');
        }

        parent::__construct($items);
    }

    /**
     * Returns collection as array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->all() as $index => $item) {
            $result[] = $item->toArray();
        }

        return $result;
    }
}
