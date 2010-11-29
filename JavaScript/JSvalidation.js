	
		
// ajax oject 
function getHTTPObject() 
{ 
    var xmlhttp;
    /*@cc_on @if (@_jscript_version >= 5) try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } } @else xmlhttp = false; @end @*/
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined')
    {
        try {
            xmlhttp = new XMLHttpRequest();
        }
        catch (e) {
            xmlhttp = false;
        }
    }
    return xmlhttp;
}
var http = getHTTPObject(); // We create the HTTP Object 
		
function clearBox(ObjselBox)	
{
    for(i=ObjselBox.length-1; i>=0; i--)
    {
        deleteOption(ObjselBox, i);
    }
}
		
function deleteOption(theSel, theIndex)
{	
    var selLength = theSel.length;
    if(selLength > 0)
    {
        theSel.options[theIndex] = null;
    }
}
		
// end ajas object
		
		
function trimString(str)
{
    return str.replace(/^\s+|\s+$/g, '');
}
		
		
		