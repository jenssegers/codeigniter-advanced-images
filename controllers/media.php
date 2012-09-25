<?php
/**
 * @name		CodeIgniter Advanced Images
 * @author		Jens Segers
 * @link		http://www.jenssegers.be
 * @license		MIT License Copyright (c) 2012 Jens Segers
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Media extends CI_Controller {
    
    public function resize() {
        // basic info
        $path = $this->uri->uri_string();
        $pathinfo = pathinfo($path);
        $size = end(explode("-", $pathinfo["filename"]));
        $original = $pathinfo["dirname"] . "/" . str_ireplace("-" . $size, "", $pathinfo["basename"]);
        
        // original image not found, show 404
        if (!file_exists($original)) {
            show_404($original);
        }
        
        // load the allowed image sizes
        $this->load->config("images");
        $sizes = $this->config->item("image_sizes");
        $allowed = FALSE;
        
        if (stripos($size, "x") !== FALSE) {
            // dimensions are provided as size
            @list($width, $height) = explode("x", $size);
            
            // security check, to avoid users requesting random sizes
            foreach ($sizes as $s) {
                if ($width == $s[0] && $height == $s[1]) {
                    $allowed = TRUE;
                    break;
                }
            }
        } else if (isset($sizes[$size])) {
            // optional, the preset is provided instead of the dimensions
            // NOTE: the controller will be executed EVERY time you request the image this way
            @list($width, $height) = $sizes[$size];
            $allowed = TRUE;
            
            // set the correct output path
            $path = str_ireplace($size, $width . "x" . $height, $path);
        }
        
        // only continue with a valid width and height
        if ($allowed && $width >= 0 && $height >= 0) {
            // initialize library
            $config["source_image"] = $original;
            $config['new_image'] = $path;
            $config["width"] = $width;
            $config["height"] = $height;
            $config["dynamic_output"] = FALSE; // always save as cache
            
            $this->load->library('image_lib');
			$this->image_lib->initialize($config);
			
            $this->image_lib->fit();
        }
        
        // check if the resulting image exists, else show the original
        if (file_exists($path)) {
            $output = $path;
        } else {
            $output = $original;
        }
        
        $info = getimagesize($output);
        
        // output the image
        header("Content-Disposition: filename={$output};");
        header("Content-Type: {$info["mime"]}");
        header('Content-Transfer-Encoding: binary');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
        
        readfile($output);
    }
}