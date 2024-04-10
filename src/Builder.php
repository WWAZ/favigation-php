<?php

namespace wwaz\Favigation;

use wwaz\Favigation\Markup\ArrayHandler\ParentChild;

class Builder
{
    /**
     * Collection of items.
     * 
     * @var Illuminate\Support\Collection 
     */
    protected $collection;

    /**
     * Renderer class name.
     * 
     * @var string
     */
    protected $renderer;

    /**
     * Tag name for rendered list markup:
     * 'ul', 'ol', 'div'.
     * 
     * @var string
     */
    protected $tag = 'ul';

    /**
     * Id string which will be added to root tag.
     * 
     * @var string
     */
    protected $id;

    /**
     * Class name which will be added to root tag.
     * 
     * @var string
     */
    protected $class;

    /**
     * Selected value:
     * 1) method to be called on item to receive current value
     * 2) value that will match when item is selected.
     * ex: getId() --> 1 = selected
     * 
     * @var array
     */
    protected $selected = [
        'method' => null,
        'value' => null
    ];

    /**
     * Constructor.
     * 
     * @param Illuminate\Support\Collection $collection
     * @param string $renderer
     */
    public function __construct(\Illuminate\Support\Collection $collection, string $renderer)
    {
        $this->collection = $this->orderItems($collection);
        $this->renderer = $renderer;
    }

    /**
     * Sets and returns tag name for rendered list markup:
     * 'ul', 'ol', 'div'.
     * 
     * @param string $val 
     * @return string
     */
    public function tag(string $val = null)
    {
        if ($val) {
            $this->tag = $val;
            return $this;
        }
        return $this->tag;
    }

    /**
     * Sets and returns class name
     * which will be added to root tag.
     * 
     * @param string $val
     * @return string
     */
    public function class (string $val = null)
    {
        if ($val) {
            $this->class = $val;
            return $this;
        }
        return $this->class;
    }

    /**
     * Sets and returns id string
     * which will be added to root tag.
     * 
     * @param string $val
     * @return string
     */
    public function id (string $val = null)
    {
        if ($val) {
            $this->id = $val;
            return $this;
        }
        return $this->id;
    }

    /**
     * Sets and returns selected value:
     * 1) method to be called on item to receive current value
     * 2) value that will match when item is selected.
     * ex: getId() --> 1 = selected
     * 
     * @param callable $method
     * @param mixed $value
     * @return array
     */
    public function selected($method, $value)
    {
        if ($method && $value) {
            $this->selected = [
                'method' => $method,
                'value' => $value
            ];
            return $this;
        }
        return $this->selected;
    }

    /**
     * Builds and returns navigation markup creator object.
     * 
     * @return wwaz\Favigation\Markup\ParentChildMarkupCreator
     */
    public function getBuild()
    {
        $nav = new \wwaz\Favigation\Markup\ParentChildMarkupCreator(
            new ParentChild($this->collection), 
            [
                'tag' => $this->tag
            ]
        );

        if ($this->id()) {
            $nav->setRootAttribute('id', $this->id());
        }

        if ($this->class()) {
            $nav->setRootAttribute('class', $this->class());
        }

        $nav = (new $this->renderer())->build($nav, $this->selected);

        return $nav;
    }

    /**
     * Orders items by ordering.
     * 
     * @param wwaz\Favigation\Collection $collection
     * @return wwaz\Favigation\Collection
     */
    protected function orderItems(\wwaz\Favigation\Collection $collection): \wwaz\Favigation\Collection
    {
        return $collection->sortBy([
            fn ($a, $b) => $a->getOrdering() <=> $b->getOrdering(),
        ]);
    }
}