<?php
/**
 * @name		CodeIgniter Advanced Images
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

class MY_Image_lib extends CI_Image_lib {
    
    var $user_width = 0;
    var $user_height = 0;
    
    /**
	 * Initialize image preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	bool
	 */
    function initialize($props = array()) {
        // save user specified dimensions before they are modified by the CI library
        if (isset($props["width"]))
            $this->user_width = $props["width"];
        if (isset($props["height"]))
            $this->user_height = $props["height"];
        
        return parent::initialize($props);
    }

    /**
	 * Smart resize and crop function
	 *
	 * @access	public
	 * @return	bool
	 */
    function fit() {
        // overwrite the dimensions with the original user specified dimensions
        $this->width = $this->user_width;
        $this->height = $this->user_height;
        
        // save dynamic output for last
        $dynamic_output = $this->dynamic_output;
        $this->dynamic_output = FALSE;
        
        if ($this->user_width == 0 || $this->user_height == 0) {
            // auto-calculate other dimension
            if ($this->user_width == 0)
                $this->width = ceil($this->user_height * $this->orig_width / $this->orig_height);
            else
                $this->height = ceil($this->user_width * $this->orig_height / $this->orig_width);
        } else {
            // making image bigger for cropping
            $this->width = ceil($this->user_height * $this->orig_width / $this->orig_height);
            $this->height = ceil($this->user_width * $this->orig_height / $this->orig_width);
            
            $ratio = (($this->orig_height / $this->orig_width) - ($this->user_height / $this->user_width));
            $master_dim = ($ratio < 0) ? 'width' : 'height';
            
            if (($this->user_width != $this->width) && ($this->user_height != $this->height)) {
                if ($master_dim == 'height')
                    $this->width = $this->user_width;
                else
                    $this->height = $this->user_height;
            }
        }
        
        // we've calculated the sizes ourselves
        $this->maintain_ratio = FALSE;
        
        // resize stage
        if (!$this->resize())
            return FALSE;
        
        // is cropping needed?
        if ($this->user_width != 0 && $this->user_height != 0) {
            $x_axis = floor(($this->width - $this->user_width) / 2);
            $y_axis = floor(($this->height - $this->user_height) / 2);
            
            // set original dimensions
            $this->orig_width = $this->width;
            $this->orig_height = $this->height;
            
            // cropping options
            $this->x_axis = $x_axis;
            $this->y_axis = $y_axis;
            $this->width = $this->user_width;
            $this->height = $this->user_height;
            
            // use the previous generated image
            $this->full_src_path = $this->full_dst_path;
            
            // cropping stage
            if (!$this->crop())
                return FALSE;
        }
        
        // finally check for dynamic output
        if ($dynamic_output) {
            header("Content-Disposition: filename={$this->full_dst_path};");
            header("Content-Type: {$this->mime_type}");
            header('Content-Transfer-Encoding: binary');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
            
            readfile($this->full_dst_path);
        } else {
            return TRUE;
        }
    }

}