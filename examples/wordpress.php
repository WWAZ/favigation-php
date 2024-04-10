<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

use Illuminate\Support\Str;

$path = dirname(__FILE__) . '/files/wp-navigation.json';
$array = json_decode(file_get_contents($path), true);

class WordpressCervFavigation
{
    protected $build;

    public function __construct($data)
    {
        $this->build = $this->build($data);
    }

    public function __call($name, $arguments)
    {
        return $this->build->$name(...$arguments);
    }

    protected function build($data)
    {
        foreach($data as $index => $item){
            $data[$index] = new \wwaz\Favigation\Driver\Wordpress\WordpressArrayAdapter($item);
        }
        return (new wwaz\Favigation\Builder(
            new \wwaz\Favigation\Collection($data), 
            \wwaz\Favigation\Driver\BasicMenuRenderer::class
        ))
            ->class('wordpress_cerv_navigation')
            ->id('nav')
            ->getBuild();
    }
}

$nav = new WordpressCervFavigation($array);

$nav
->setLiContentBefore( function($item){
    if( $item->getId() == 30 ){
        return '<div class="test float-left">';
    }
})
->setLiContentAfter( function($item){
    if( $item->getId() == 30 ){
        $m = [];
        $m[] = '</div>';
        $m[] = '<div class="img float-left">';
        $m[] = '<img src="https://cdn.openai.com/research-covers/dall-e/2x-no-mark.jpg" width="200"/>';
        $m[] = '</div>';
        return implode("", $m);
    }
})
->setContent(function($item){

    $icon = false;

    if( $item->getId() === 430 ){
        $icon = 'europaeische-werte.svg';
    }
    if( $item->getId() === 23 ){
        $icon = 'recht-und-gleichheit.svg';
    }

    if( $icon ){
        $icon = '<img class="icon" src="'.$icon.'">';
    }

    if( count($item->getChildren()) && $item->getUrl() ){
        $target = '';
        if( $item->getTarget() ){
            $target = ' target="' . $item->getTarget() . '"';
        }
        return $icon . '<a' . $target . ' href="' . $item->getUrl() . '">' . $item->getTitle() . '</a>';
    }
    return $icon . '<span>' . $item->getTitle() . '</span>';
})

->setLiAttribute('data-type', function($item){
    return $item->isNode() ? 'node' : null;
})

->setLiAttribute('data-id', function($item){
    return $item->getId();
})

->setLiAttribute('data-path', function($item){
    if( $item->isNode() ){
        return $item->getPath();
    }
})

->setLiAttribute('data-slug', function($item){
    if( $item->isNode() ){
        return Str::slug($item->getTitle());
    }
})

->setLiAttribute('class', function($item){
    if( $item->isNode() ){
        return 'cnt-children-' . count($item->getChildren());
    }
});

// die(print_r($nav->toArray()));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
    <style>
    li[data-id="30"]{
        list-style: none;
        list-style-position: inside;
        padding: 20px;
    }
    .test span{
        font-size: 2em;
        font-weight: bold;
    }
    .float-left{
        width: 50%;
        float: left;
    }
</style>
</head>
<body>
    <?php echo $nav->toHtml(); ?>
</body>
</html>