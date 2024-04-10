<?php

namespace wwaz\Favigation\Markup;

abstract class MarkupCreator
{
    /**
     * Array data.
     *
     * @var array
     */
    protected $array = [];

    /**
     * Allowed tags.
     *
     * @var array
     */
    protected $allowedTags = ['ul', 'ol', 'div'];

    /**
     * Tag to be used
     * div or ul.
     *
     * @var string
     */
    protected $tag = 'ul';

    /**
     * Root node attributes.
     *
     * @var array
     */
    protected $rootAttributes = [];

    /**
     * Array key name which includes children.
     *
     * @var string
     */
    protected $childrenKey = false;

    /**
     * Array Key name which will be displayed as li content.
     *
     * @var string
     */
    protected $contentKey = false;

    /**
     * Method to render li content.
     *
     * @var callable
     */
    protected $contentMethod = false;

    /**
     * Attributes which will be added to li element.
     *
     * @var array
     */
    protected $liAttributes = [];

    /**
     * Attributes which will be added to ul element.
     *
     * @var array
     */
    protected $ulAttributes = [];

    /**
     * Modifier: sets li content before.
     *
     * @var callable
     * @abstract
     */
    protected $setLiContentBeforeMethod = null;

    /**
     * Modifier: sets li content after.
     *
     * @var callable
     * @abstract
     */
    protected $setLiContentAfterMethod = null;

    /**
     * Constructor.
     *
     * @param string $tag – 'ul' | 'ol' | 'div'
     * @param array $array
     */
    public function __construct($tag, $array, $options = [])
    {
        $this->tag = $this->checkTag($tag);
        $this->array = $array;
        $this->options = array_merge([
            'depth' => false,
        ], $options);
    }

    /**
     * Checks support of input tag.
     *
     * @param string $tag
     * @return string $tag
     * @throws \Exception when tag is not supported
     */
    protected function checkTag($tag)
    {
        $tag = strtolower($tag);
        if (!in_array($tag, $this->allowedTags)) {
            throw new \Exception('Tag "' . $tag . '" is not supported. Choose one of those: ' . implode(', ', $this->allowedTags));
        }
        return $tag;
    }

    /**
     * Sets root UL attribute.
     *
     * @param string $name
     * @param string value
     * @return self
     */
    public function setRootAttribute($name, $value)
    {
        $this->rootAttributes[] = [
            'name' => $name,
            'value' => $value,
        ];
        return $this;
    }

    /**
     * Sets LI attribute modifier.
     *
     * @param string $name - name of attribute: e.g. data-path
     * @param callable $function - modifier function
     * @return self
     */
    public function setLiAttribute($name, callable $function)
    {
        $this->liAttributes[] = [
            'name' => $name,
            'function' => $function,
        ];
        return $this;
    }

    /**
     * Sets ul attribute modifier.
     *
     * @param string $name - name of attribute: e.g. data-path
     * @param callable $function - modifier function
     * @return self
     */
    public function setUlAttribute($name, callable $function)
    {
        $this->ulAttributes[] = [
            'name' => $name,
            'function' => $function,
        ];
        return $this;
    }

    /**
     * Sets modifier function for generating LI content.
     *
     * @param callable $function
     * @return self
     */
    public function setContent(callable $function)
    {
        $this->contentMethod = $function;
        return $this;
    }

    /**
     * Sets modifier function for generating LI content.
     *
     * @param callable $function
     * @return self
     * @deprecated
     */
    public function setContentMethod(callable $function)
    {
        return $this->setContent($function);
    }

    /**
     * Modifier: sets li content before.
     *
     * @param callable $function
     * @return self
     */
    public function setLiContentBefore(callable $function)
    {
        $this->setLiContentBeforeMethod = $function;
        return $this;
    }

    /**
     * Modifier: sets li content after.
     *
     * @param callable $function
     * @return self
     */
    public function setLiContentAfter(callable $function)
    {
        $this->setLiContentAfterMethod = $function;
        return $this;
    }

    /**
     * Returns html markup.
     *
     * @param none
     * @return string html
     */
    abstract public function toHtml();

    /**
     * Returns RecursionHandler object as array.
     *
     * @param none
     * @return array
     */
    // abstract public function toArray();

    /**
     * Returns main tag.
     *
     * @param none
     * @return string $tag
     */
    protected function getMainTag()
    {
        return $this->tag;
    }

    /**
     * Returns sub tag.
     *
     * @param none
     * @return string $tag
     */
    protected function getSubTag()
    {
        if ($this->tag === 'ul' || $this->tag === 'ol') {
            return 'li';
        }
        if ($this->tag === 'div') {
            return 'div';
        }
    }

    /**
     * Returns root node attributes.
     *
     * @param none
     * @return string
     */
    protected function getRootAttributes()
    {
        if (!$this->rootAttributes) {
            return '';
        }
        $m = [];
        foreach ($this->rootAttributes as $index => $attribute) {
            $m[] = $attribute['name'] . '="' . $attribute['value'] . '"';
        }
        return ' ' . implode(' ', $m);
    }

    /**
     * Returns node name,
     * that provides recursive node children.
     *
     * @param none
     * @return string
     */
    protected function getChildrenKey()
    {
        return $this->childrenKey;
    }

    /**
     * Calls UL attribute modifier
     * and returns attributes for specific node.
     *
     * @param array $item
     * @return string
     */
    abstract protected function getUlAttributes($item, $level = false, $path = []);

    /**
     * Returns n * '\t'
     *
     * @param integer $n
     * @return string
     */
    protected function tabNTimes($n)
    {
        $m = '';
        for ($i = 0; $i < $n; $i++) {
            $m .= "\t";
        }
        return $m;
    }
}
