{literal}
<!---
function checkElement(id, type, required, minlength, maxlength, regex) 
{
    var name = document.getElementById(id).value;
    var valid = false;
    switch (type)
    {
        case "text":
            if (required == true || name != "")
            {
                valid = true;  
                if (minlength > 0 || maxlength > 0)
                { 
                    if (minlength == 0 && name.length > maxlength && maxlength > 0)
                    {
                        valid = false;
                    }
                    else if (maxlength == 0 && name.length < minlength && minlength > 0)
                    {
                        valid = false;
                    }
                    else if ((name.length < minlength && minlength > 0) || (name.length > maxlength && maxlength > 0))
                    {
                        valid = false;
                    }
                }
            }
	    else if (required == false && name == "")
	    {
		valid = true;
	    }
            break;
        case "date":
            var datereg = /^(19|20)\d\d[- \/.](0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])/;
            if (required == true || name != "")
            {            
                if (datereg.test(name))
                {
                    valid = true;
                }
            }
            else if (required == false && name == "")
            {
                valid = true;
            }
            break;
        case "number":
            var numberreg = /^[0-9]*$/;
            if (required == true || name != "")
            {            
                if (numberreg.test(name))
                {
                    valid = true;
                }
            }
            else if (required == false && name == "")
            {
                valid = true;
            }        
            break;
        case "email":
	        var emailreg  = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (required == true || name != "")
            {            
                if (emailreg.test(name))
                {
                    valid = true;
                }
            }
            else if (required == false && name == "")
            {
                valid = true;
            }
            break;
        case "custom":
            if (required == true || name != "")
            {            
                if (regex.test(name))
                {
                    valid = true;
                }
            }
            else if (required == false && name == "")
            {
                valid = true;
            }
            break;
        case "duplicate":
            if (required == true || name != "" || document.getElementById(regex).value != "")
            {            
                if (document.getElementById(regex).value == name)
                {
                    valid = true;
                }
            }
            else if (required == false && name == "" && document.getElementById(regex).value == "")
            {
                valid = true;
            }
            break;
    }
    
    if (required == true && name == "")
    {
        valid = false;
    }
    
    if (valid == false)
    {
        document.getElementById(id+"Error").style.display = "inline";
        {/literal}
        document.getElementById(id).style.borderColor  = "{$templateinfo.invalid}";
        {literal}
        document.getElementById(id).style.borderWidth = "2px";
        document.getElementById(id).select();
        document.getElementById(id).focus();
    }
    else
    {
        document.getElementById(id+"Error").style.display = "none";
        {/literal}
        document.getElementById(id).style.borderColor = "{$templateinfo.valid}";
        {literal}
        document.getElementById(id).style.borderWidth = "1px";
    }
    return valid;
}

function checkForm(items)
{
    overallValid = true;
    if (document.getElementById('anyerror'))
        document.getElementById('anyerror').innerHTML = "";
    
    for(i=0;i<items.length;i++)
    {
        itemargs = items[i];
        valid = checkElement(itemargs[0],itemargs[1],itemargs[2],itemargs[3],itemargs[4],itemargs[5]);
        if (!valid && document.getElementById('anyerror'))
        {
            document.getElementById('anyerror').innerHTML += "Error with " + itemargs[6] + "<br />"; 
            document.getElementById("anyerror").style.display = "block";
        }
        overallValid = overallValid && valid;
    }

    return overallValid;
}
-->
{/literal}