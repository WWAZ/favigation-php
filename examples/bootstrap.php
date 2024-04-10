<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

$path = dirname(__FILE__) . '/files/backend-navigation.json';
$array = json_decode(file_get_contents($path), true);

class BackendBootstrapFavigation
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
            $data[$index] = new \wwaz\Favigation\Driver\Backend\BackendArrayAdapter($item);
        }
        return (new wwaz\Favigation\Builder(
            new \wwaz\Favigation\Collection($data), 
            \wwaz\Favigation\Driver\Bootstrap\BootstrapMenuRenderer::class
        ))
            ->selected('getId', 'cmm-reports')
            ->class('backend_bootstrap_navigation')
            ->id('nav')
            ->tag('ul')
            ->getBuild();
    }
}

$nav = new BackendBootstrapFavigation($array);

// die(print_r($nav->toArray()));
// die(print_r($nav->test()));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
    li.active > button,
    li.active > span,
    li.active > a{
        font-weight: bold;
    }
   
</style>
</head>
<body>
    <?php echo $nav->toHtml(); ?>
</body>
</html>