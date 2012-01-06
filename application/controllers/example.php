<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller {

	public function index()
	{
	    // if not autoloaded
	    $this->load->helper("image");
	    
	    // display example
	    $this->load->view("image_example");
	}
}