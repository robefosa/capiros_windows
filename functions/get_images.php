<?php
// recives a directory path without last ¨/¨
//returns an array with the names of all the images in the directory (includin extension)

function get_images($dir_name)
{
    if (is_dir($dir_name))
    {
        $files = scandir($dir_name);
        foreach ($files as $order => $value)
        {
            $file_path = $dir_name . '/' . $value;
            if(is_array(getimagesize($file_path)))
            {
                $images[] = $value;
            }
            
        }
        return $images;
    }
    return FALSE;
  
}