<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

$path = dirname(__FILE__) . '/files/wp-navigation.json';
$array = json_decode(file_get_contents($path), true);

$nav = new wwaz\Favigation\Driver\Wordpress\WordpressBuilder(
    $array,
    \wwaz\Favigation\Driver\BasicMenuRenderer::class
);

?>
<!DOCTYPE html>
<html>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<head>
    <title>Basic Wordpress menu</title>
    <style>
        .wrapper{
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Basic Wordpress menu</h1>
        <?php echo $nav->toHtml(); ?>
    </div>
</body>
</html>