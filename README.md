CodeIgniter Advanced Images
===========================

An advanced image resize/cropping controller that will resize your images on the fly from preset dimensions.

Installation
------------

 1. Download the files from github and place the in their corresponding folder (or use spark).
 2. Make sure you are using the .htaccess mod_rewrite: http://codeigniter.com/wiki/mod_rewrite
 2. Rename the controller and the media folder to your liking. (must be the same name)
 3. Add a custom route to your config/routes.php file. This will redirect all "non-existing-file" request to the media controller's resize method:  
	`$route['media/(:any)'] = 'media/resize/$1';`
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
	| If both dimensions are specified it will automatically crop the 
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

Library
-------

The resize and crop logic is grouped in a library that extends CodeIgniter's 'image_lib' and adds a **'fit'** function. You can use this library to resize and crop an image to fit the specified dimensions like this:

	$config["source_image"] = '/path/to/image/mypic.jpg';
	$config['new_image'] = '/path/to/new/image/newpic.jpg';
	$config["width"] = 100;
	$config["height"] = 100;
					
	$this->load->library('image_lib', $config);
	$this->image_lib->fit();

The function will return TRUE on sucess or FALSE on failure. Error messages can be read like the normal library with the display_errors() method.

When an image is cropped, the center axis is used by default. If you want to override this behaviour you can specify your own x_axis and y_axis.

Configuration options:

 - **image_library**: Sets the image library to be used.
 - **library_path**: Sets the server path to your ImageMagick or NetPBM library. If you use either of those libraries you must supply the path.
 - **source_image**: Sets the source image name/path. The path must be a relative or absolute server path, not a URL.
 - **dynamic_output**: Determines whether the new image file should be written to disk or generated dynamically. Note: If you choose the dynamic setting, only one image can be shown at a time, and it can't be positioned on the page. It simply outputs the raw image dynamically to your browser, along with image headers.
 - **quality**: Sets the quality of the image. The higher the quality the larger the file size.
 - **new_image**: Sets the destination image name/path. You'll use this preference when creating an image copy. The path must be a relative or absolute server path, not a URL.
 - **width**: Sets the width you would like the image set to.
 - **height**: Sets the height you would like the image set to.
 - **x_axis**: Sets the X coordinate in pixels (after resizing) for image cropping. For example, a setting of 30 will crop an image 30 pixels from the left.
 - **y_axis**: Sets the Y coordinate in pixels (after resizing) for image cropping. For example, a setting of 30 will crop an image 30 pixels from the top.

Usage
-----

To resize and crop the images to the preset sizes you need to load the image_helper. This helper has a function image($path, $preset) that will translate a preset to a generated path that contains the dimensions for the controller or that will take you directly to the image if it has already been resized.

Use this helper in your view files like this:

	<img src="<?php echo image("media/smiles.png", "wide"); ?>" alt="smiles" />

This will eventually translate into:

	<img src="media/smiles-600x200.png" alt="smiles" />

The reason I add the dimensions to the original filename instead of the preset name is because when you would change the preset's dimensions, it would still load images with the old dimensions that were already generated instead of the new dimensions.