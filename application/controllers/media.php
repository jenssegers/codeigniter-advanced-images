<?php
/**
 * @name		CodeIgniter Images
 * @author		Jens Segers
 * @link		http://www.jenssegers.be
 * @license		MIT License Copyright (c) 2011 Jens Segers
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
    
    // for convenience
    public function index() {
        return $this->resize();
    }
    
    public function resize() {
        $path = $this->uri->uri_string();
        $pathinfo = pathinfo($path);
        $size = end(explode("-", $pathinfo["filename"]));
        $original = $pathinfo["dirname"] . "/" . str_replace("-" . $size, "", $pathinfo["basename"]);
        
        // only continue if the original file exists
        if (!file_exists($original))
            show_404($path);
        
        // get mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, realpath($original));
        
        // only allow images
        if (!stristr($mime, "image"))
            show_404($path);
        
        // get the requested width and height
        @list($width, $height) = explode("x", $size);
        
        // only continue with a valid width and height
        if ($width >= 0 && $height >= 0) {
            // preserve requested size
            $new_width = $width;
            $new_height = $height;
            
            // get the original width and height
            $orig_size = @getimagesize($original);
            $orig_width = $orig_size[0];
            $orig_height = $orig_size[1];
            
            // load the allowed image sizes
            $this->load->config("images");
            $sizes = $this->config->item("image_sizes");
            
            // security check, to avoid users requesting random sizes
            $allowed = FALSE;
            foreach ($sizes as $s) {
                if ($width . "x" . $height == $s)
                    $allowed = TRUE;
            }
            
            // preset allowed
            if ($allowed) {
                
                if ($width == 0 || $height == 0) {
                    // auto-scale if 1 dimension is 0
                    if ($width == 0)
                        $new_width = ceil($height * $orig_width / $orig_height);
                    else
                        $new_height = ceil($width * $orig_height / $orig_width);
                } else {
                    // making image bigger for cropping
                    $new_width = ceil($height * $orig_width / $orig_height);
                    $new_height = ceil($width * $orig_height / $orig_width);
                    
                    $ratio = (($orig_height / $orig_width) - ($height / $width));
                    $master_dim = ($ratio < 0) ? 'width' : 'height';
                    
                    if (($width != $new_width) && ($height != $new_height)) {
                        if ($master_dim == 'height')
                            $new_width = $width;
                        else
                            $new_height = $height;
                    }
                }
                
                // start resize
                $config = array();
                $config["source_image"] = $original;
                $config["new_image"] = $path;
                $config["width"] = $new_width;
                $config["height"] = $new_height;
                $config["maintain_ratio"] = FALSE;
                
                $this->load->library("image_lib");
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
                
                // cropping needed?
                if ($width != 0 && $height != 0) {
                    $x_axis = floor(($new_width - $width) / 2);
                    $y_axis = floor(($new_height - $height) / 2);
                    
                    // start cropping
                    $config = array();
                    $config["x_axis"] = $x_axis;
                    $config["y_axis"] = $y_axis;
                    $config["width"] = $new_width - 2 * $x_axis;
                    $config["height"] = $new_height - 2 * $y_axis;
                    $config["source_image"] = $path;
                    $config["new_image"] = $path;
                    
                    $this->image_lib->initialize($config);
                    $this->image_lib->crop();
                }
            } else {
                // preset not found, show original image
                $path = $original;
            }
        
        } else {
            // invalid width and/or height, show original image
            $path = $original;
        }
        
        // set headers for dynamic output
        header("Content-Disposition: filename={" . $path . "};");
        header("Content-Type: {$mime}");
        header('Content-Transfer-Encoding: binary');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + 5184000));
        
        readfile($path);
    }
}