<?php
namespace wwaz\Favigation\Markup;

class MarkupElement
{
    /**
     * Data.
     *
     * @var array
     */
    protected $data = [
        'oc'            => null,
        'elem'          => null,
        'type'          => null,
        'item'          => null,
        'path'          => null,
        'level'         => null,
        'tabLevel'      => null,
        'attributes'    => null,
        'contentBefore' => null,
        'content'       => null,
        'contentAfter'  => null,
    ];

    /**
     * Constructor.
     *
     * @param array
     */
    public function __construct($array = [])
    {
        foreach ($array as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = $value;
            }
        }
    }

    /**
     * Magic setter.
     *
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Magic getter.
     *
     */
    public function __get($key)
    {
        return $this->data[$key];
    }

    /**
     * Returns data as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}
