<?php

function send_contact_email($data, $default_email, $subj, $head)
{
$to = $default_email; 
$subject = $subj;

$headers = "From: ". $data['e_mail'] ."\r\n";
//Aditional headers  
$headers .= $head;

// initialize the $message variable
$message = '';

// loop through the $data array
foreach($data as $item => $value) 
    {
        // assign the value of the current item to $val
        if (isset($data[$item]) && !empty($data[$item]))
        {
            $val = $data[$item];
        } 
        else 
            {
                // if it has no value, assign 'Not selected'
                $val = 'Dato no registrado';
            }
                
        // if an array, expand as comma-separated string
        if (is_array($val)) 
            {
                $val = implode(', ', $val);
            }
        // replace underscores and hyphens in the label with spaces
        $item = str_replace(array('_', '-'), ' ', $item);
        // add label and value to the message body
        $message .= ucfirst($item).": $val\r\n\r\n";
    }
    
// limit line length to 70 characters
$message = wordwrap($message, 70);

$mailSent = mail($to, $subject, $message, $headers);
return $mailSent;

}