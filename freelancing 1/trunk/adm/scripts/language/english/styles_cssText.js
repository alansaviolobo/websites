function loadText()
    {
    var txtLang = document.getElementsByName("txtLang");
    txtLang[0].innerHTML = "CSS Text";
    txtLang[1].innerHTML = "Class Name";
    
    document.getElementById("btnCancel").value = "cancel";
    document.getElementById("btnApply").value = "apply";
    document.getElementById("btnOk").value = " ok ";
    }
function getText(s)
    {
    switch(s)
        {
        case "You're selecting BODY element.":
            return "You're selecting BODY element.";
        case "Please select a text.":
            return "Please select a text.";
        default:return "";
        }
    }
function writeTitle()
    {
    document.write("<title>Custom CSS</title>")
    }