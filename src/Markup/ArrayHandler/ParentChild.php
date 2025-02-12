<?php
namespace wwaz\Favigation\Markup\ArrayHandler;

class ParentChild
{
    /**
     * Input array.
     *
     * @array
     */
    protected $collection = [];

    /**
     * Constructor.
     *
     * @param Illuminate\Support\Collection $collection
     */
    public function __construct(\Illuminate\Support\Collection $collection)
    {
        $this->collection = $this->parentIdCorrection($collection);
    }

    /**
     * Sets [parentId] = 0
     * when [id] and [parentId] are equal.
     * (--> root node)
     *
     * @param wwaz\Favigation\Collection $array
     * @return array
     */
    protected function parentIdCorrection(\wwaz\Favigation\Collection $collection)
    {
        foreach ($collection as $index => $item) {
            if ($item->getId() == $item->getParentId()) {
                $item->setParentId(0);
            }
        }
        return $collection;
    }

    /**
     * Returns converted
     * multidimensional indexed array.
     *
     * @param none
     * @return array
     */
    public function toMultidimensionalIndexArray()
    {
        return $this->convertToMultidimensionalIndexArray($this->collection);
    }

    /**
     * Converts self referencing array
     * to multidimensional array.
     *
     * @param Illuminate\Support\Collection $elements
     * @param int|string $parentId – internal usage only.
     * @return array
     */
    protected function convertToMultidimensionalIndexArray(\Illuminate\Support\Collection $elements, int | string $parentId = 0, int $level = 0, array $path = [])
    {
        $branch = [];

        $path[] = $level;

        foreach ($elements as $element) {
            if ($element->getParentId() == $parentId) {
                $children = $this->convertToMultidimensionalIndexArray($elements, $element->getId(), $level + 1, $path);
                if ($children) {
                    foreach ($children as $index => $child) {
                        $children[$index]->setParent($element);
                    }
                    $element->setChildren($children);
                }
                $element->setLevel($level);
                $element->setPath($path);
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
