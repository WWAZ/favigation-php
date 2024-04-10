<?php

namespace wwaz\Favigation\Markup;

use wwaz\Favigation\Markup\MarkupCreator;
use wwaz\Favigation\Markup\MarkupElement;

class ParentChildMarkupCreator extends MarkupCreator
{
    /**
     * Tag.
     * 
     * @var string
     */
    protected $tag = 'ul';

    /**
     * Options.
     * 
     * @var array
     */
    protected $options = [];

    /**
     * Constructor.
     *
     * @param string $tag – 'ul' | 'ol' | 'div'
     * @param array $array
     * @param array $options
     */
    public function __construct(\wwaz\Favigation\Markup\ArrayHandler\ParentChild $obj, array $options = [])
    {
        $this->array = $obj->toMultidimensionalIndexArray();
        $this->options = array_merge([
            'tag' => $this->tag,
            'depth' => false
        ], $options);
        $this->tag = $this->checkTag($this->options['tag']);
    }

    /**
     * Returns markup data array.
     * 
     * @return array
     */
    public function toArray()
    {
        $result = [];
        foreach($this->getMarkupData() as $index => $elem){
            $result[] = $elem->toArray();
        }
        return $result;
    }

    /**
     * Returns markup.
     * 
     * @return string
     */
    public function toHtml()
    {
        $html = [];

        $afterContent = [];

        $lastelem = null;
        
        foreach($this->getMarkupData() as $index => $elem){

            $tl = $elem->tabLevel;

            if( $elem->contentBefore ){
                $html[]= $this->ln($tl) . $elem->contentBefore;
            }

            if( $elem->contentAfter ){
                $afterContent[$elem->item['id']] = $elem->contentAfter;
            }

            if( $elem->oc === 'open' ){
                $html[]= $this->ln($tl) . '<' . $elem->elem . ($elem->attributes ? ' ' . $elem->attributes : '') . '>';
                if( $elem->content ){
                    $html[]= $elem->content;
                }
            }

            if( $elem->oc === 'close' ){
                if( $elem->elem == 'li' && $lastelem !== 'ul' ){
                    $html[]= '</' . $elem->elem . '>';
                } else {
                    $html[]= $this->ln($tl) . '</' . $elem->elem . '>';
                }
                if( $elem->item && isset($afterContent[$elem->item['id']]) ){
                    $html[]= $this->ln($tl) . $afterContent[$elem->item['id']];
                }
            }

            $lastelem = $elem->elem;
        }

        return implode("", $html) . "\n";
    }

    /**
     * Returns markup data.
     * 
     * @return array
     */
    protected function getMarkupData()
    {
        return $this->buildMarkupData($this->array);
    }

    /**
     * Builds markup data.
     * 
     * @param array $items
     * @param int $level – internal 
     * @param array $path – internal 
     * @param int $tabLevel – internal 
     * @param array $result – internal 
     * @return array
     */
    protected function buildMarkupData(array $items, int $level = 0, array $path = [], int $tabLevel = 0, array $result = [])
    {
        if( !isset($result) ){
            $result = [];
        }
        
        $path[] = $level;

        $result[] = new MarkupElement([
            'oc' => 'open',
            'elem' => $this->getMainTag(),
            'type' => 'regular',
            'item' => $items[0]->toArray(),
            'path' => $items[0]->getPath(),
            'level' => $items[0]->getLevel(),
            'tabLevel' => $tabLevel,
            'attributes' => trim($this->ulAttributes($items, $level, $path)),
            'contentBefore' => null,
            'content' => null,
            'contentAfter' => null,
        ]);

        $tabLevel++;

        foreach($items as $index => $item){

            if( $item->getChildren() ){

                $result[] = new MarkupElement([
                    'oc' => 'open',
                    'elem' => $this->getSubTag(),
                    'type' => $item->isNode() ? 'node' : 'regular',
                    'item' => $item->toArray(),
                    'path' => $item->getPath(),
                    'level' => $item->getLevel(),
                    'tabLevel' => $tabLevel,
                    'attributes' => trim($this->liAttributes($item, $level, $path)),
                    'contentBefore' => $this->getBeforeContent($item),
                    'content' => $this->getLiContent($item, $level, $path),
                    'contentAfter' => $this->getAfterContent($item)
                ]);

                $tabLevel++;;

                $result = $this->buildMarkupData($item->getChildren(), $level + 1, $path, $tabLevel, $result, true);

                $tabLevel--;

                $result[] = new MarkupElement([
                    'oc' => 'close',
                    'elem' => $this->getSubTag(),
                    'type' => 'regular',
                    'item' => $item->toArray(),
                    'path' => null,
                    'level' => null,
                    'tabLevel' => $tabLevel,
                    'attributes' => null,
                    'contentBefore' => null,
                    'content' => null,
                    'contentAfter' => null,
                ]);

            } else {

                if( $item->getLevel() > $level ){
                    $tabLevel++;
                }

                $result[] = new MarkupElement([
                    'oc' => 'open',
                    'elem' => $this->getSubTag(),
                    'type' => $item->isNode() ? 'node' : 'regular',
                    'item' => $item->toArray(),
                    'path' => $item->getPath(),
                    'level' => $item->getLevel(),
                    'tabLevel' => $tabLevel,
                    'attributes' => trim($this->liAttributes($item, $level, $path)),
                    'contentBefore' => $this->getBeforeContent($item),
                    'content' => $this->getLiContent($item, $level, $path),
                    'contentAfter' => $this->getAfterContent($item)
                ]);

                $result[] = new MarkupElement([
                    'oc' => 'close',
                    'elem' => $this->getSubTag(),
                    'type' => 'regular',
                    'item' => $item->toArray(),
                    'path' => null,
                    'level' => null,
                    'tabLevel' => $tabLevel,
                    'attributes' => null,
                    'contentBefore' => null,
                    'content' => null,
                    'contentAfter' => null,
                ]);
            }
        }

        $tabLevel--;
        
        $result[] = new MarkupElement([
            'oc' => 'close',
            'elem' => $this->getMainTag(),
            'type' => 'regular',
            'item' => $items[0]->toArray(),
            'path' => null,
            'level' => null,
            'tabLevel' => $tabLevel,
            'attributes' => null,
            'contentBefore' => null,
            'content' => null,
            'contentAfter' => null,
        ]);

        return $result;
    }

    /**
     * Returns li attributes.
     *
     * @param array $item
     * @param int $level
     * @param string $path
     * @return string
     */
    protected function liAttributes($item, $level, $path)
    {
        $res = [];
        if( $att = $this->getAttributes($this->liAttributes, $item, $level, $path) ){
            $res[] = trim($att);
        }

        if( !empty($res) ){
            return ' ' . implode(' ', $res);
        }
        return '';
    }

    /**
     * Returns ul attributes.
     *
     * @param array $item
     * @param int $level
     * @param string $path
     * @return string
     */
    protected function ulAttributes($array, $level, $path)
    {
        $res = [];

        $item = isset($array[0]) ? $array[0] : $array;

        if( $level == 0 ){
            return ' ' . $this->getRootAtributes();
        }

        if( $att = $this->getAttributes($this->ulAttributes, $item, $level, $path) ){
            $res[] = trim($att);
        }

        if( !empty($res) ){
            return ' ' . implode(' ', $res);
        }
        return '';
    }

    /**
     * Returns new line + n times tab.
     *
     * @param int $cnt
     * @return string
     */
    protected function ln($cnt = 0)
    {
        return "\n" . $this->tab($cnt);
    }

    /**
     * Returns new n times tab.
     *
     * @param int $cnt
     * @return string
     */
    protected function tab($cnt)
    {
        return str_repeat("\t", $cnt);
    }

    /**
     * Returns root attributes.
     *
     * @return string
     */
    protected function getRootAtributes()
    {
        $m = [];
        foreach($this->rootAttributes as $index => $attr){
            if( !isset($m[$attr['name']]) ){
                $m[$attr['name']] = [];
            }
            $m[$attr['name']][]= $attr['value'];
        }
        $res = [];
        foreach($m as $key => $attr){
            $res[] = $key . '="' . implode(' ', $attr) . '"';
        }
        if( empty($res) ){
            return '';
        }
        return implode(' ', $res);
    }

    /**
     * Modifier: Calls UL attribute modifier
     * and returns attributes for specific node.
     *
     * @param array $item
     * @return string
     */
    protected function getUlAttributes($item, $level = false, $path = [])
    {
        $item = isset($item[0]) ? $item[0] : $item;
        return $this->getAttributes($this->ulAttributes, $item, null, $level, $path);
    }

    /**
     * Modifier: Calls LI attribute modifier
     * and returns attributes for specific node.
     *
     * @param array $item
     * @return string
     */
    protected function getLiAttributes($item, $level = false, $path = [])
    {
        return $this->getAttributes($this->liAttributes, $item, null, $level, $path);
    }

    /**
     * Modifier: Calls attribute modifier
     * and returns attributes for specific node.
     *
     * @see recursiveToUl()
     *
     * @param array $attributes – li or ul attributes
     * @param array $item – current node
     * @return string
     */
    protected function getAttributes($attributes, $item, $level, $path)
    {
        if( empty($attributes) ){
            return '';
        }

        $m = [];

        foreach($attributes as $index => $attribute){

            $name = $attribute['name'];

            $function = $attribute['function'];

            $value = call_user_func($function, $item, $this->nodeInfo($item));

            if( isset($value) && $value !== false ){
                $m[] = $name . '="' . $value . '"';
            }
        }

        if( !empty($m) ){
            return ' ' . implode(' ', $m);
        }

        return '';
    }

    /**
     * Modifier: Calls li content modifier
     * and returns content for given node.
     *
     * @see recursiveToUl()
     * @param array $item
     * @return string $content
     */
    protected function getLiContent($item, $level, $path)
    {
        $liContent = '';

        if( $this->contentKey && isset($item[$this->contentKey]) ){
            $liContent = $item[$this->contentKey];
        }

        if( getType($this->contentMethod) === 'object' ){
            $liContent = call_user_func($this->contentMethod, $item, $this->nodeInfo($item));
        }

        return $liContent;
    }

    /**
     * Returns node info.
     *
     * @param array $item
     * @param int $level
     * @param string $path
     * @return array
     */
    protected function nodeInfo($item)
    {
        return [
            'type' => $this->isNode($item) ? 'node' : false,
            'level' => $item->getLevel(),
            'children' => count($item->getChildren()),
            'path' => $item->getPath()
        ];
    }

    protected function isNode($item)
    {
       return count($item->getChildren()) > 0 ? true : false;
    } 

    /**
     * Modifier: Returns before content.
     *
     * @param array $item
     * @return string|false
     */
    protected function getBeforeContent($item)
    {
        $content = false;

        if( getType($this->setLiContentBeforeMethod) === 'object' ){
            $content = call_user_func($this->setLiContentBeforeMethod, $item, []);
        }
        return $content;
    }

    /**
     * Modifier: Returns after content.
     *
     * @param array $item
     * @return string|false
     */
    protected function getAfterContent($item)
    {
        $content = false;

        if( getType($this->setLiContentAfterMethod) === 'object' ){
            $content = call_user_func($this->setLiContentAfterMethod, $item, []);
        }

        return $content;
    }

}