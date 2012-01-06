Codeigniter Advanced Images
=========================================

An advanced image resize/cropping controller that will resize your images on the fly from preset dimensions.

Installation
------------

 1. Download the files from github and place the in their corresponding folder.
 2. Make sure you are using the .htaccess mod_rewrite: http://codeigniter.com/wiki/mod_rewrite
 2. Rename the controller and the media folder to your liking. (must be the same name)
 3. Add a custom route to your config/routes.php file. This will redirect all "non-existing-file" request to the media controller's resize method:

	$route['media/(:any)'] = 'media/resize/$1';

 4. Optional: autoload the image_helper by adding it to $autoload['helper'] array in your config/autoload.php file.

Configuration
-------------

In your config/images.php file you need to specify what preset sizes you will be using for your images:

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
	
	
Each preset has a width and a height. If one of the dimensions are equal to 0, it will automatically calculate a matching width or height to maintain the original ratio. if you specify both dimensions it will automatically crop the resulting image so that it fits those dimensions.
	
Usage
-----

To resize and crop the images to the preset sizes you need to load the image_helper. This helper has a function image($path, $preset) that will translate a preset to a generated path that contains the dimensions for the controller or that will take you directly to the image if it has already been resized.

Use this helper in your view files like this:

	<img src="<?php echo image("media/smiles.png", "wide"); ?>" alt="smiles" />

This will eventually translate into:

	<img src="media/smiles-600x200.png" alt="smiles" />

The reason I add the dimensions to the original filename instead of the preset name is because when you would change the preset's dimensions, it would still load images with the old dimensions that were already generated instead of the new dimensions.