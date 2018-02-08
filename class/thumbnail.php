<?php //


class thumbnail {
    protected $_original;
    protected $_originalwidth;
    protected $_originalheight;
    protected $_thumbwidth;
    protected $_thumbheight;
    protected $_maxSize = 120;
    protected $_canProcess = false;
    protected $_imageType;
    protected $_destination;
    protected $_name;
    protected $_suffix = '_thb';
    protected $_messages = array();

    //The constructor takes one argument, the path to an image
    public function __construct($image) 
    {
        if (is_file($image) && is_readable($image)) 
        {
            $details = getimagesize($image);
        } 
        else 
        {
            $details = null;
            $this->_messages[] = "Cannot open $image.";
        }
        // if getimagesize() returns an array, it looks like an image
        if (is_array($details)) 
        {
            $this->_original = $image;
            $this->_originalwidth = $details[0];
            $this->_originalheight = $details[1];
            // check the MIME type
            $this->checkType($details['mime']);
            $this->getName();
        } 
        else 
        {
            $this->_messages[] = "$image doesn't appear to be an image.";
        }
    }
    
    //creates a thum centered in the original image and returns the path ti its location
    public function create_thum_cent($dest, $thum_w, $thum_h)
    {
        $this->setDestination($dest);
          
        $w_dif = $this->_originalwidth - $thum_w;
        $h_dif = $this->_originalheight - $thum_h;
        
        if($w_dif > $h_dif)
        {
           $h = TRUE;
        }
        elseif ($w_dif <= $h_dif)
        {
            $w = TRUE;
            
        }

        if ($this->_originalwidth <= $thum_w && $this->_originalheight <= $thum_h)
        {
            $newname = $this->_name . $this->_suffix . "." . $this->_imageType;
            $success = copy($this->_original, $this->_destination . $newname);
            return $this->_destination . $newname;
           
        }
        elseif (($this->_originalheight >= $thum_h && $this->_originalwidth <= $thum_w) || $h) 
        {
            $r = $this->_originalheight / $this->_originalwidth;
            $d_heidht = $thum_h;
            $d_width = $d_heidht / $r;
             
        }
        elseif (($this->_originalwidth >= $thum_w && $this->_originalheight <= $thum_h) || $w) 
        {
            $r = $this->_originalwidth / $this->_originalheight;
            $d_width = $thum_w;
            $d_heidht = $d_width / $r;
               
        }
        
        //coordenates to center the thumb sample
        $x = round(($d_width - $thum_w) / 2);
        $y = round(($d_heidht - $thum_h) / 2);

        $thum = imagecreatetruecolor($thum_w, $thum_h);
        $thum1 = imagecreatetruecolor($d_width, $d_heidht);
        $original = $this->createImageResource();
        
        //whole imagen keeping the aspect ratio
        imagecopyresized($thum1, $original, 0, 0, 0, 0, $d_width, $d_heidht, $this->_originalwidth, $this->_originalheight );
        //sample fron the center of image
        imagecopyresampled($thum, $thum1, 0, 0, $x, $y, $thum_w, $thum_h, $thum_w, $thum_h);
        
        //rename and store the thumb
        $newname = $this->_name . $this->_suffix;
        if ($this->_imageType == 'jpeg') 
        {
            $newname .= '.jpg';
            $success = imagejpeg($thum, $this->_destination . $newname, 100);
        } 
        elseif ($this->_imageType == 'png') 
        {
            $newname .= '.png';
            $success = imagepng($thum, $this->_destination . $newname, 0);
        } 
        elseif ($this->_imageType == 'gif') 
        {
            $newname .= '.gif';
            $success = imagegif($thum, $this->_destination . $newname);
        }
         //check for success
        if ($success) 
        {
            $this->_messages[] = "$newname created successfully.";
            return $this->_destination . $newname;
        }
        else 
        {
            $this->_messages[] = "Couldn't create a thumbnail for " . basename($this->_original);
        }  
        imagedestroy($thum);
        imagedestroy($thum1);
        imagedestroy($original);
        
    }
    
    public function create_miniature($dest, $max_zise)
    {
        if($this->_originalheight >= $this->_originalwidth)
        {
            $r = $this->_originalheight / $this->_originalwidth;
            $d_height = $max_zise;
            $d_width = $d_height / $r;
            
        }
        else 
        {
          $r = $this->_originalwidth / $this->_originalheight;
          $d_width = $max_zise;
          $d_height = $d_width / $r;
          
        }
        
        $final = imagecreatetruecolor($d_width, $d_height);
        $original = $this->createImageResource();
            
        imagecopyresized($final, $original, 0, 0, 0, 0, $d_width, $d_height, $this->_originalwidth, $this->_originalheight );
        
        //OUTPUT TO A FILE
          
            $this->setDestination($dest);
            //rename and store the thumb
            $newname = $this->_name . $this->_suffix;
            if ($this->_imageType == 'jpeg') 
            {
                $newname .= '.jpg';
                $success = imagejpeg($final, $this->_destination . $newname, 100);
            } 
            elseif ($this->_imageType == 'png') 
            {
                $newname .= '.png';
                $success = imagepng($final, $this->_destination . $newname, 0);
            } 
            elseif ($this->_imageType == 'gif') 
            {
                $newname .= '.gif';
                $success = imagegif($final, $this->_destination . $newname);
            }
             //check for success
            if ($success) 
            {
                $this->_messages[] = "$newname created successfully.";
                return $this->_destination . $newname;
            }
            else 
            {
                $this->_messages[] = "Couldn't create a thumbnail for " . basename($this->_original);
            }  
            imagedestroy($final);
            imagedestroy($original);
        
       
    }

        public function setDestination($destination)
    {
        if (is_dir($destination) && is_writable($destination)) 
        {
            // get last character
            $last = substr($destination, -1);
            // add a trailing slash if missing
            if($last == '/' || $last == '\\') 
            {
                $this->_destination = $destination;
            }
            else 
            {
                $this->_destination = $destination . DIRECTORY_SEPARATOR;
            }
        } 
        else 
        {
            $this->_messages[] = "Cannot write to $destination.";
        }
    }
    
    public function getMessages() 
    {
        return $this->_messages;
    }
    
    //sets a sufix for the thumbnail
    public function setSuffix($suffix) 
    {
        if (preg_match('/^\w+$/', $suffix)) 
        {
            if (strpos($suffix, '_') !== 0) 
            {
                $this->_suffix = '_' . $suffix;
            } 
            else 
            {
                $this->_suffix = $suffix;
            }
        } 
        else 
        {
            $this->_suffix = '';
        }
    }
   
    //creates an imagen resourse based on imagen extension $this->_imageType
    protected function createImageResource() 
    {
        if ($this->_imageType == 'jpeg') 
        {
            return imagecreatefromjpeg($this->_original);
        } 
        elseif ($this->_imageType == 'png') 
        {
            return imagecreatefrompng($this->_original);
        } 
        elseif ($this->_imageType == 'gif') 
        {
            return imagecreatefromgif($this->_original);
        }
    }
        
    //Sets $_imageType to the extension of the image 
    protected function checkType($mime) 
    {
        $mimetypes = array('image/jpeg', 'image/png', 'image/gif');
        if (in_array($mime, $mimetypes)) 
        {
            $this->_canProcess = true;
            // extract the characters after 'image/'
            $this->_imageType = substr($mime, 6);
        }
    }
    
    //obtains the imagen file name without extension
    protected function getName() 
    {
        $extensions = array('/\.jpg$/i', '/\.jpeg$/i', '/\.png$/i', '/\.gif$/i');
        $this->_name = preg_replace($extensions, '', basename($this->_original));
    }
    
   
}
