<?php
/*
|--------------------------------------------------------------------------
| Image Preset Sizes
|--------------------------------------------------------------------------
|
| Specify the preset sizes you want to use in your code. Only these preset 
| will be accepted by the controller for security.
|
| Each preset exists of a width and height. If one of the dimensions are 
| equal to 0, it will automatically calculate a matching width or height 
| to maintain the original ratio.
|
| Uf both dimensions are specified it will automatically crop the 
| resulting image so that it fits those dimensions.
|
*/

$config["image_sizes"]["square"] = array(400, 400);
$config["image_sizes"]["rectangle"] = array(600, 400);
$config["image_sizes"]["long"] = array(200, 600);
$config["image_sizes"]["wide"] = array(600, 200);

$config["image_sizes"]["small"] = array(300, 0);
$config["image_sizes"]["medium"] = array(500, 0);
$config["image_sizes"]["large"] = array(800, 0);