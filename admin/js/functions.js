function togglediv(aid) {
  if(document.getElementById(aid).style.display=='none') {
    document.getElementById(aid).style.display = '';
  } else {
    document.getElementById(aid).style.display = 'none';
  }
}

function submitenter(myform,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
    document.getElementById(myform).submit();
    return false;
   }
else
   return true;
}

function __alert(msg) 
{
  if(msg!='') alert(msg);
}
function order_delete(text_confirm, text_attention)
{
 if((document.getElementById('r1').checked ||
     document.getElementById('r2').checked ||
     document.getElementById('r3').checked ||
     document.getElementById('r4').checked ||
     document.getElementById('r5').checked
    ) || 
    (
      document.getElementById('r6').checked &&
      document.getElementById('otherreason').value!=''
    )
   )
   {
    return confirm(text_confirm);
   } else
   {
    alert(text_attention);
    return false;
   } 
}

function checkForInt(evt) {
var charCode = ( evt.which ) ? evt.which : event.keyCode;
return ( charCode >= 48 && charCode <= 57 );
}
function makeRequest(url) {
        var http_request = false;
        if (window.XMLHttpRequest) { // Mozilla, Safari,...
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType('text/xml');
                // See note below about this line
            }
        } else if (window.ActiveXObject) { // IE
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!http_request) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        document.getElementById('waitingdiv').style.display = ""; //Start showing the loading image
        http_request.onreadystatechange = function() { alertContents(http_request); };
        http_request.open('GET', url, true);
		http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        http_request.send(null);
}
function alertContents(http_request) {
        if (http_request.readyState == 4) {
            if (http_request.status == 200) {
			  document.body.innerHTML = http_request.responseText;
            } else {
                alert('There was a problem with the request.');
            }
        }
}
function addItemDialog(category_id, category_name, itemlist_id){
	var overlay = $("<div class='addItemDialog'><h1>Add menu item to "+category_name+"</h1><hr style='background-color: #ddd;color: #ddd; height: 1px; border: none;'></div>");
	var form  = $("<form action='addMenuItem.php'><input type='hidden' name='category_id' value='"+category_id+"' /><table> <tr><td class='label'>Name</td><td><input size='40' name='name' type='text'></input></td></tr> <tr><td class='label'>Description</td><td><textarea cols='32' rows='6' name='description'></textarea></td></tr> <tr><td class='label'>Price</td><td><input name='price' type='text' value='$0.00'></input></td></tr></table><table><tr><td style='text-align: right; font-size: 12px; color: #aaa;width: 100%'>Press 'Esc' to cancel</td><td><input class='addItemButton' type='submit' value='Add Item' /></td></tr></table></form>").appendTo(overlay).ajaxForm(function(response) { 
			$.achtung({message: 'Item added', timeout:2});
			response = $.parseJSON(response);
			
			$("<li id='menu_items_"+response.item.id+"'><table><tr> <td><img class='item_dragger icon' src='images/drag.png' /></td> <td style='width: 100%'><h2 style='font-size: 13px;padding: 0;margin: 0;'>"+response.item.name+"</h2> <p>"+response.item.description+"</p></td> <td style='min-width: 50px'>"+response.item.price+"</td> <td style='color:#6666aa ;min-width: 80px'>"+category_name+"</td> <td><img onclick='itemXClicked(this, "+response.item.id+");' class='red_x icon' src='images/red_x.gif' /></td></tr></table></li>").appendTo("#menuItemList_"+itemlist_id).hide().slideToggle("fast");
		});
	
	overlay.modal({
		opacity:80,
		overlayCss: {backgroundColor:"#000"},
		overlayClose: true
	});
}
