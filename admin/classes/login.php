<?php

class login
{
    private $conn;
    private $table;
    private $error_message;

    function __construct($db_user, $db_pass, $db_name, $table_name, $db_host = "localhost")
    {
        $this->error_message = FALSE;

        try {
            $this->conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        } catch (Exception $ex) {
            $this->error_message = $ex->getMessage();
            throw (new Exception($this->error_message));
        }
        
        $this->table = $table_name;    
        
    }

    public function check_login($user, $pass)
    {
        $user_name = $this->conn->real_escape_string($user);
        $actual_pass = $this->conn->real_escape_string($pass);
        
        $sql = "SELECT password FROM " . $this->table . " WHERE user = '" . $user_name . "'";
        $result = $this->conn->query($sql);
        
        if($result && ($result->num_rows >= 1))
        {
            $data = $result->fetch_row();
            $hash = $data[0];
            
            if(password_verify($actual_pass, $hash))
            {
                return TRUE;
            }
            else {
                $this->error_message = "Wrong password";
                return FALSE;
            }
        }
        else {
            $this->error_message = "Wrong user";
            return FALSE;
            }
    }
    
    public function get_error()
    {
        return $this->error_message;
    }
    
    public function create_user($user, $pass, $email)
    {
        if($hash = password_hash($pass, PASSWORD_BCRYPT))
        {
            $sql = "INSERT INTO login (user, password, email) VALUES ('" . $user . "', '" . $hash . "', '" . $email . "')";
            echo $sql;
            $result = $this->conn->query($sql);
            if($result)
            {
                return TRUE;
            }
            else {
                $this->error_message = "DB error";
                return FALSE;
            }
        }
        else {
            $this->error_message = "Hash error";
            return FALSE;
        }
    }
    
                    
}
