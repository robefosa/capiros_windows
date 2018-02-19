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
        $user1 = $this->conn->real_escape_string($user);
        $pass1 = $this->conn->real_escape_string($pass);
        $email1 = $this->conn->real_escape_string($email);
        
        if($hash = password_hash($pass1, PASSWORD_BCRYPT))
        {
            $sql = "INSERT INTO login (user, password, email) VALUES ('" . $user1 . "', '" . $hash . "', '" . $email1 . "')";
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
    
    public function check_if_email_exist($email)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);

        if($stmt->execute())
        {
            $result = $stmt->get_result();

            if($result->num_rows >= 1)
            {
                $data = $result->fetch_assoc();
                return $data['email'];
            }
            else 
            {
                $this->error_message = "The email is not in db";
                return FALSE;
            }
        }
        else 
        {
            $this->error_message = "Error in execute";
            return FALSE;
        }
    }
    
    public function send_pass_recovery_email($email, $password_reset_script)
    {
         //prepare data to send email
        $to = $email;
        $subject = "Reset your password";
         // Additional headers
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'From: Capiros Windows Corp. <contact@capiroswindows.com>';       

        // Mail it
        mail($to, $subject, $message, implode("\r\n", $headers));

        if($message = $this->build_pass_recovery_message($email, $password_reset_script))
        {
            // Mail it
            mail($to, $subject, $message, implode("\r\n", $headers));
        }
 
        else {
            echo $this->error_message;
        }
        
    }
    
    private function build_pass_recovery_message($email, $password_reset_script)
    {
        $token = md5(time());
        $link = $password_reset_script . "?email=" . $email . "&t=" . $token;
        $message = "";
        
        if($message_file = fopen("../data/pass_recovery_email_text.txt", "r"))
        {
            while (!feof($message_file))
            {
                $message .= fgets($message_file);
            } 
            str_replace("#", $link, $message);
            return $message;
        }
        else 
        {
            $this->error_message = "Can not open a file";
            return FALSE;
        }
    }
                    
}
