<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
$path = dirname(__FILE__) . '/files/wp-navigation.json';
$array = json_decode(file_get_contents($path), true);

wwaz\Favigation\BaseNavigation::setDepth(2);

$markup = (wwaz\Favigation\BaseNavigation::getInstance($array, 'ID', 'menu_item_parent', 'menu_order', 'base-navigation'))->toHtml();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</style>
</head>
<body>
    <?php echo $markup; ?>
</body>
</html>
