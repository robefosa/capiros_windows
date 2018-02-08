
function checkData(form)
{
    if(form.p_name.value == "")
    {
        alert("Para crear un proyecto debe incluir, al menos, un nombre para el proyecto");
        return false;    
    }
    else
    {
        if(form.order.value == "")
        {
            form.order.value = 1;
        }
            
        return true;
    }

}