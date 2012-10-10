// JavaScript Document
function checkForm()
{
var fname, cemail, lname,telephone,country,telephone,enquiry;

// use this section to assign form object to variable

   fname			= window.document.form1.fname;
   lname			= window.document.form1.lname;
   cemail			= window.document.form1.email;  
   country     		= window.document.form1.country;
   telephone   		= window.document.form1.telephone;
   enquiry     		= window.document.form1.enquiry;

   				if(trim(fname.value) == '' || !isNaN(fname.value) || !allValidChars_Username(fname))
   				{
      					alert('Please enter your name.');
      					fname.focus();
      					return false;
   				}else if(trim(lname.value) == '' || !isNaN(lname.value) || !allValidChars_Username(lname))
   				{
      					alert('Please enter your last name.');
      					lname.focus();
      					return false;
   				}else if(trim(cemail.value) == '')
   				{
      					alert('Please enter your email.');
      					cemail.focus();
      					return false;
   				}
				else if(!validate())
   				{
      					alert('Email address is not valid.');
      					cemail.focus();
      					return false;
   				}else if(trim(telephone.value) == '' || isNaN(telephone.value))
				{
					alert("Please enter numeric value in telephone.");
					telephone.focus();
					return false;
				}else if(trim(enquiry.value) == '')
				{
					alert("Please enter your query");
					enquiry.focus();
					return false;
				}else if(!countcharacter())
   				{
      					alert('Please enter your query.It must be less than 500 character.');
      					enquiry.focus();
      					return false;
   				}else if(!select_country())
   				{
      					alert('Please select the country.');
      					country.focus();
      					return false;
   				}
				 
  
   else
   {
      fname.value		= trim(fname.value);
      cemail.value 		= trim(cemail.value);
      lname.value 		= trim(lname.value);
	  telephone.value	= trim(telephone.value);
	  enquiry.value		= trim(enquiry.value);
	  
    		
			return true;
   }
		

}
function trim(str)
{
   return str.replace(/^\s+|\s+$/g,'');
}

function validate() 
{
    		if (!isValidEmail(document.form1.email.value)) 
			{
           			return false;
    		}
    	   return true;
}
  function isValidEmail(email, required) 
  {
    	if (required==undefined) 
		{   // if not specified, assume it's required
        		required=true;
   	 	}
    	if (email==null) 
		{
        		if (required)
				{
            		return false;
        		}
        		return true;
				
    	}
    if (email.length==0) 
	{  
        	if (required) 
			{
            	return false;
        	}
        		return true;
				
    }
    	if (! allValidChars(email)) 
		{  // check to make sure all characters are valid
	
      		  	return false;
    	}
   		 if (email.indexOf("@") < 1) 
		 { //  must contain @, and it must not be the first character
		 
        		return false;
    	} 
		else if (email.lastIndexOf(".") <= email.indexOf("@")) 
		{  // last dot must be after the @

        		return false;
    	} 
		else if (email.indexOf("@") == email.length-1) 
		{  // @ must not be the last character
	
       			 return false;
   		} 
		else if (email.indexOf("..") >=0) 
		{ // two periods in a row is not valid

				return false;
    	} else if (email.indexOf(".") == email.length-1) 
		{  // . must not be the last character

				return false;
    	}
		return true;
				
}

function allValidChars(email) 
{
  var parsed = true;
  var validchars = "abcdefghijklmnopqrstuvwxyz0123456789.-_@";
  for (var i=0; i < email.length; i++) {
    var letter = email.charAt(i).toLowerCase();
	
    if (validchars.indexOf(letter) != -1)
      continue;
    parsed = false;
    break;
  }
  return parsed;
}
function allValidChars_Username(username) 
{
  var parsed = true;
  var validchars = "abcdefghijklmnopqrstuvwxyz_";
  for (var i=0; i < username.length; i++) {
    var letter = username.charAt(i).toLowerCase();
	
    if (validchars.indexOf(letter) != -1)
      continue;
    parsed = false;
    break;
  }
  return parsed;
}

function countcharacter()
{

	var content = window.document.form1.enquiry.value;
	
	var char_count = parseInt(content.length);
	
	if(!(char_count >=1 && char_count <=500))
	return false;
	
	else
	return true;
}
function select_country()
{
var country_value = document.form1.country.value	
if(country_value == 'null')
return false
else 
return true
}
