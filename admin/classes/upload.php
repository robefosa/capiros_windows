<?php

//takes the destination directory for an uploaded file
//and the specific array containing the file data from $_FILES superglobal
//Ex: $_FILES['file_1'] or $_FILES['files_2']
//returns and object to manage and check the file, or otherwise thows an exception


class upload
{
    private $file_data;
    private $destination;
    private $valid_extension = array('image/gif',
                                    'image/jpeg',
                                    'image/pjpeg',
                                    'image/png');
    
    private $error = array('type' => false,
                           'file_data' => false,
                           'destination' => false,
                            'upload' => false);
    
    //constructor
    public function __construct($dest, $files_array)
    {   
        if(is_array($files_array) && !empty($files_array['name']))
        {
            $this->file_data = $files_array;
        }
        else 
        {
            throw (new Exception("Error with FILES array"));
        }
        
        if(is_dir($dest))
        {
            if(substr($dest, -1) == "/")
            {
                $this->destination = $dest . $this->file_data['name'];
            }
            else 
            {
                $this->destination = $dest . "/" . $this->file_data['name'];
            }
            
        }
        else 
        {
            throw (new Exception("Destination pased is not a valid directory"));
        }
        if(is_writable($dest))
        {
          //  throw (new Exception("Destination is not writable"));
        }
   }

    public function check_type()
    {
        if(!in_array($this->file_data['type'], $this->valid_extension))
        {
            $this->error['type'] = TRUE;
        }
    }
    
    //returns TRUE if some error if found in error array
    private function error_check()
    {
        foreach ($this->error as $category => $value)
        {
            if(isset($value) && $value == TRUE)
                {return $value;}
        }
    }
    
    //prints the faulty items inside the error array
    public function show_errors()
    {
        foreach ($this->error as $item => $value)
        {
            if(isset($value) && $value == TRUE)
                echo 'Error with: ' . $item;
        }
    }

    //returns TRUE in susses and FALSE otherwise
    public function upload_file()
    {
        $this->check_type();
        if($this->error_check())
        {return FALSE;}
        else 
        {
            if(move_uploaded_file($this->file_data['tmp_name'], $this->destination))
            {return TRUE;}
            else 
            {return FALSE;}
        }
    }

    public function get_destination()
    {
        return $this->destination;
    }        

}