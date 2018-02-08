<?php

function update_proyect_data($)
{
if (isset($_POST['name_description']))
        {
            if($stmt = $conn->prepare("UPDATE proyects SET proyect_name = ?, description = ? WHERE proyect_id =" . $_GET['proyect']))
            {
                $stmt->bind_param("ss", $_POST['p_name'], $_POST['p_description']);
                
                if($stmt->execute())
                {
                    header('Refresh:0');
                }               
            }
        }
}