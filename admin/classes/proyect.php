<?php

//Takes the data to connect to DB(user and pass should have control total over DB),
//Takes the name of the table where the proyects are stored, an index for the actual proyect and the name of the
//column inside the table where the proyects indexes are stored
//Returns a Proyect Object or throws an exception otherwise
//To create a new empty proyect, $proyect_index shold be "0", or another non existing proyect number
//In orther to work with imagen upload and update, "upload" class should be included in the script

class proyect
{
    private $proyect_data;
    private $conn;
    private $error_message;
    private $data_base_name;
    private $proyect_table;
    private $proyect_ident;
    private $ident_colum_name;
    private $user_db;
    private $pass_db;
    private $host_db;
    private $exist;

    public function __construct($db_user, $db_pass, $db_name, $table_name, $proyect_index, $index_colum_name, $db_host = "localhost") 
    {
        $this->error_message = FALSE;
        
        try {
            $this->conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        } catch (Exception $ex) {
            $this->error_message = $ex->getMessage();
        }
        
        if($this->error_message != FALSE)
        {
            throw (new Exception($this->error_message));
        }
        else 
        {
            if($stmt = $this->conn->prepare("SELECT * FROM " . $table_name . " WHERE " . $index_colum_name . "=?"))
            {
                if($stmt->bind_param("i", $proyect_index))
                {
                    if($stmt->execute())
                    {
                        $results = $stmt->get_result();
                        if($results->num_rows >= 1)
                        {
                            $this->proyect_data = $results->fetch_assoc();
                            $this->data_base_name = $db_name;
                            $this->proyect_table = $table_name;
                            $this->proyect_ident = $proyect_index;
                            $this->ident_colum_name = $index_colum_name;
                            $this->exist = TRUE;
                        }
                        else 
                        {
                            $this->data_base_name = $db_name;
                            $this->proyect_table = $table_name;
                            $this->ident_colum_name = $index_colum_name;
                            $this->user_db = $db_user;
                            $this->pass_db = $db_pass;
                            $this->host_db = $db_host;
                            $this->exist = FALSE;
                        }
                    }
                    else
                        {
                        throw (new Exception("Execute error"));
                    }
                }
                else
                {
                    throw (new Exception("Bind_param error"));
                }
            }
            else
            {
                throw (new Exception("Prepare error"));
            }
        }
    }
    
    //returns true if the proyect exist in DB, FALSE otherwise
    public function exist()
    {
        return $this->exist;
    }


    //returns the value of the proyect´s property wish name is $item
    public function get_property($item)
    {
        if(isset($this->proyect_data[$item]))
            return $this->proyect_data[$item];
        else 
            return FALSE;
    }

    //updates the value of the proyect´s property($proyect_property) with $value. 
    //update is reflected in Proyect object and in DB
    //returns TRUE on susses, FALSE othewise
    public function update_property($proyect_property, $value)
    {
        if($stmt = $this->conn->prepare("UPDATE " . $this->proyect_table . " SET " . $proyect_property . "= ? WHERE " . $this->ident_colum_name . "=" . $this->proyect_ident))
            {
                $type = $this->get_type($value);
                
                if($stmt->bind_param($type, $value))
                {
                    if($stmt->execute())
                    {
                        $this->proyect_data[$proyect_property] = $value;
                        return TRUE;
                    }
                }
                else 
                {  
                    $this->error_message = "bind_param";
                    return FALSE;
                }
            }
            else 
            {
                $this->error_message = "prepare";
                return FALSE;
            }
    }
    
    //gets the $_FILES array for an expecific form ($_FILES['form_x'])
    //returns TRUE if susseful uploaded otherwise FALSE
    public function add_image($array)
    {
        
        if(is_array($array))
        {
            $total = count($array['name']);
            
                for($i=0; $i<$total; $i++)
                {
                    $update['name'] = $array['name'][$i];
                    $update['type'] = $array['type'][$i];
                    $update['tmp_name'] = $array['tmp_name'][$i];
                    $update['error'] = $array['error'][$i];
                    $update['size'] = $array['size'][$i];

                    try {
                           $image = new upload("../../" . $this->proyect_data['images_base_dir'], $update);
                        } catch (Exception $ex) {
                            $this->error_message = "Debe seleccionar al menos una imagen antes de dar clic en \"Agregar Imagenes\"";
                            return FALSE;
                        }

                        //set imagen data to db
                        $stmt = $this->conn->prepare("INSERT INTO images (location, proyects_proyect_id) VALUES (?, ?)");
                        if($stmt)
                        {           
                            $stmt->bind_param("si", substr($image->get_destination(), 6), $this->proyect_ident);
                            if(!$stmt->execute())
                            {
                                $this->error_message ="Error al subir imagen";
                                return FALSE;
                            }
                        }
                        else {
                                $this->error_message = "Error al subir imagen";
                                return FALSE;
                        }

                        //upload new imagen
                        if(!$image->upload_file())
                        {
                            $this->error_message = "Error al cargar el archivo";
                            return FALSE;
                        }

                }
                return TRUE;
        }
        else 
        {
            $this->error_message = "Parameter is not array";
            return FALSE;
        }
    }
    
    //gets the imagen ID returns True on susses otherwise false
    public function delete_image($id)
    {
        if(is_numeric($id))
        {
            $id =(int)$id;
            if($stmt = $this->conn->prepare("SELECT * FROM images WHERE imagen_id=?"))
            {           
                $stmt->bind_param("i", $id);
                if($stmt->execute())
                {
                    $result = $stmt->get_result();
                    $img_data = $result->fetch_assoc();
                    $path = "../../" . $img_data['location'];
                    
                    //delete imagen from storage
                    if(file_exists($path))
                    {
                        if(!unlink($path))
                        {
                            $this->error_message = "File not deleted";
                            return FALSE;
                        }
                    }
                    else 
                        {
                        $this->error_message = "File does not exist in storage";
                        }

                    //delete imagen data from db
                    if($stmt = $this->conn->prepare("DELETE FROM images WHERE imagen_id = ?"))
                    {           
                        $stmt->bind_param("i", $id);
                        if($stmt->execute())
                            return TRUE;;
                    }
                    else {
                        $this->error_message = "database error";
                        return FALSE;
                    }
                    
                }
                else {
                    $this->error_message = "Imagen ID error, does not exist"; 
                    return FALSE;
                }
            }
            else 
                {
                    $this->error_message = "database error2";
                        return FALSE;
                }
            
        }
        else {
                   $this->error_message = "Imagen ID error, does not exist2";
                   return FALSE;
        }
    }
    
    public function update_image($array, $id)
    {
        if($this->add_image($array)){
            if($this->delete_image($id)){
                return TRUE;
            } 
            else {
                $this->error_message = "error deleting image";
                return FALSE;
            } 
        }
        else {
            $this->error_message = "Debe seleccionar una imagen antes de dar clic en \"Cambiar imagen\"";
            return FALSE;
        }
    }
    
    //returns an assoc array whit the principal image data
    public function get_principal_image_data()
    {
        $images = $this->get_images_data();
        $total = count($images);
        
        for($i=0; $i<$total; $i++)
        {
            if($images[$i]["imagen_id"] == $this->proyect_data['principal_img'])
            {
                $array = $images[$i];
                return $array;
            }
        }
    }
    
    //returns a numeric array of associative arrays that contain images data 
    //or false if no images in db
    public function get_images_data()
    {
        $all_sql = "SELECT * FROM images WHERE proyects_proyect_id=" . $this->proyect_ident;
        if($images = $this->conn->query($all_sql))
        {     
            $array = array();
                //check for DB error and if not, show content
                if ($images->num_rows > 0)
                {
                    for($i=0; $i<$images->num_rows; $i++)
                    {
                        $images_result = $images->fetch_assoc();
                        $array[$i] = $images_result;
                    }
                    return $array;
                }
                else 
                    {
                    $this->error_message ="Error getting images from DB";
                    return FALSE;
                    }
        }
        else 
            {
                $this->error_message = "Error en DB";
                return FALSE;
            }
    }
    
    public function set_principal_image($id)
    {
        $sql = "UPDATE proyects SET principal_img = ? WHERE proyect_id =" . $this->proyect_ident;
        if($stmt = $this->conn->prepare($sql))
        {
            if($stmt->bind_param("i", $id))
            {
                if($stmt->execute())
                {
                    if($stmt->affected_rows <=1)
                        return TRUE;
                    else {
                        $this->error_message = "DB error 1";
                        return FALSE;
                    }
                }
                 else {
                        $this->error_message = "DB error 2";
                        return FALSE;
                    }
            }
             else {
                        $this->error_message = "DB error 3";
                        return FALSE;
                    }
        }
         else {
                        $this->error_message = "DB error 4";
                        return FALSE;
                    }
        
    }

    public function print_data()
    {
        echo '<pre>';
        print_r($this->proyect_data);
        echo '</pre>';
    }
    
    public function get_error()
    {
        return $this->error_message;
    }
   
    //receives an associative array of data tu create a new proyect array[$property_name]=$value
    public function create_proyect($pro_data)
    {
        if(is_array($pro_data) && isset($pro_data['proyect_name']))
        {
            $this->proyect_data = $pro_data;
            
            //update order to show proyects
            $sql1 = "UPDATE proyects SET display_order = display_order + 1 WHERE display_order >=" . $this->proyect_data['display_order'];
            $result = $this->conn->query($sql1);
            if($result == FALSE)
            {
                return FALSE;
            }
            else
            {
                    $sql2 = "INSERT INTO proyects (proyect_name, display_order, description) VALUES (?, ?, ?)";
                    if($stmt = $this->conn->prepare($sql2))
                    {
                        if($stmt->bind_param("sis", $this->proyect_data['proyect_name'], $this->proyect_data['display_order'],
                        $this->proyect_data['description']))
                        {
                            if($stmt->execute())
                            {
                                $dir = $this->create_proyect_base_dir($this->proyect_data['proyect_name']);
                                if($dir)
                                {
                                    return TRUE;
                                }
                                else
                                {
                                    $this->error_message .= "File system error 1";
                                    $delete = "DELETE FROM proyects WHERE proyect_name='" . $this->proyect_data['proyect_name'] . "'";
                                    $this->conn->query($delete);
                                    return FALSE;
                                }
                            }
                            else 
                                {
                                    $this->error_message = "DB error 2";
                                    return FALSE;
                                }
                        }
                    }
                    else 
                    {
                        $this->error_message = "DB error 1"; 
                        return FALSE;
                    }
                
            }
            
        }
        
    }
    
    public function delete_proyect()
    {
        $images = $this->get_images_data();
        
        if($images)
        {
            $total = count($images);

            for($i=0; $i <$total; $i++)
            {
                if(!$this->delete_image($images[$i]['imagen_id']))
                {
                    $this->error_message = "error erasing image";
                    return FALSE;
                }
            }
            $path = "../../" . $this->proyect_data['images_base_dir'];
            if(rmdir($path))
            {
                $sql = "DELETE FROM proyects WHERE proyect_id=" . $this->proyect_ident;
                if($this->conn->query($sql))
                {   //update display order
                    $update = "UPDATE proyects SET display_order = display_order - 1 "
                            . "WHERE display_order > " . $this->proyect_data['display_order'];
                    if($this->conn->query($update))
                        return TRUE;
                    else {
                        $this->error_message = "error updating display order";
                        return FALSE;
                    }
                }
                else 
                    {
                        $this->error_message = "error deleting proyect";
                        return FALSE;
                    }
            }
            else
                {
                $this->error_message = "error borrando directorio";
                return FALSE;
                }
        }
        else 
            {
                $this->error_message = "no images in db";
                return TRUE;
                    
            }
    }

        private function create_proyect_base_dir($p_name)
    {
        $sql = "SELECT proyect_id FROM proyects WHERE proyect_name ='" . $p_name . "'";
        
        $result = $this->conn->query($sql);
        if($result)
        {
            $pro = $result->fetch_row();
            $path = "../../img/proyects/proyect_" . $pro[0];
            $this->proyect_data['proyect_id'] = $pro[0];
            
            if(mkdir($path))
            {
                $sql2 = "UPDATE proyects SET images_base_dir='" . substr($path, 6) . "' WHERE proyect_name ='" .$p_name . "'";
                
                if($this->conn->query($sql2))
                {
                    return TRUE;
                }
                else 
                    {
                        rmdir($path);
                        $this->error_message = "db error";
                        return FALSE;
                    }
            }
            else {
                $this->error_message = "mkdir error";
                return FALSE;
            }
        }
        else 
            {
                $this->error_message = "proyect_name  error";
                return FALSE;
            }
    }

    //returns a string to be used with mysqli_stmt::bind_param
    private function get_type($var)
    {
        if(is_int($var))
        {
            return "i";
        }
        elseif (is_double($var))
        {
            return "d";
        }
        else 
        {
            return "s";
        }
    }
}