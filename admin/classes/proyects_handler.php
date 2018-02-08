<?php

class proyects_handler
{
    private $error_message;
    private $conn;
    private $number_proyects;
    private $proyects_index;
    
    function __construct($db_user, $db_pass, $db_name, $table_name, $db_host = "localhost") {
        
        $this->error_message = FALSE;
        
        try {
            $this->conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        } catch (Exception $ex) {
            $this->error_message = $ex->getMessage();
        }
        
        if($this->error_message){
            throw (new Exception($this->error_message));
        }
        else {
            //get number of proyects
            $sql = "SELECT COUNT(*) FROM " . $table_name;
            $result = $this->conn->query($sql);
            
            if($result){
                $data = $result->fetch_row();
                $this->number_proyects = $data[0];
            }
            else {
                $this->error_message = "error geting number_proyects";
                throw (new Exception($this->error_message));
            }
        }
    }
    //if there are no proyects this method returns NULL
    public function get_number_proyects(){
        if(isset($this->number_proyects))
            return $this->number_proyects;
        else {
            return NULL;
        }
    }


    public function get_error()
    {
        return $this->error_message;
    }
}
