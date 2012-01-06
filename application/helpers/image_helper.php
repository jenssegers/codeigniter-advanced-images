<?php

if (!function_exists('image')) {
    function image($image_path, $preset) {
        $ci = &get_instance();
        
        // load the allowed image presets
        $ci->load->config("images");
        $sizes = $ci->config->item("image_sizes");
        
        $pathinfo = pathinfo($image_path);
        $new_path = $image_path;
        
        // check if requested preset exists
        if (isset($sizes[$preset])) {
            $new_path = $pathinfo["dirname"] . "/" . $pathinfo["filename"] . "-" . implode("x", $sizes[$preset]) . "." . $pathinfo["extension"];
        }
        
        return $new_path;
    }
}