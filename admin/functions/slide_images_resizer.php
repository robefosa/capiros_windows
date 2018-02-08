<?php

include_once '../data/common_data.php';
include_once '../functions/get_images.php';
include_once '../class/thumbnail.php';

$images = get_images('../' . $slide_files_location);

if(is_array($images))
{
foreach ($images as $item => $value)
{
    $path = '../' . $slide_files_location . '/' . $value;
    $thum = new thumbnail($path);
    $thum->create_thum_cent('../' . $slide_thumbs_location, 843, 360);
}
}
 else {
    echo 'error';    
}

