/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function simulate(element, eventName)
{
    var options = extend(defaultOptions, arguments[2] || {});
    var oEvent, eventType = null;

    for (var name in eventMatchers)
    {
        if (eventMatchers[name].test(eventName)) { eventType = name; break; }
    }

    if (!eventType)
        throw new SyntaxError('Only HTMLEvents and MouseEvents interfaces are supported');

    if (document.createEvent)
    {
        oEvent = document.createEvent(eventType);
        if (eventType == 'HTMLEvents')
        {
            oEvent.initEvent(eventName, options.bubbles, options.cancelable);
        }
        else
        {
            oEvent.initMouseEvent(eventName, options.bubbles, options.cancelable, document.defaultView,
            options.button, options.pointerX, options.pointerY, options.pointerX, options.pointerY,
            options.ctrlKey, options.altKey, options.shiftKey, options.metaKey, options.button, element);
        }
        element.dispatchEvent(oEvent);
    }
    else
    {
        options.clientX = options.pointerX;
        options.clientY = options.pointerY;
        var evt = document.createEventObject();
        oEvent = extend(evt, options);
        element.fireEvent('on' + eventName, oEvent);
    }
    return element;
}

function extend(destination, source) {
    for (var property in source)
      destination[property] = source[property];
    return destination;
}

var eventMatchers = {
    'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/,
    'MouseEvents': /^(?:click|dblclick|mouse(?:down|up|over|move|out))$/
}
var defaultOptions = {
    pointerX: 0,
    pointerY: 0,
    button: 0,
    ctrlKey: false,
    altKey: false,
    shiftKey: false,
    metaKey: false,
    bubbles: true,
    cancelable: true
}
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

function searchdb()
{
   
  $.ajax({
    url: "/application/search_table_copy.php",
    type: "post",
    dataType: "html",
    data: $("form").serialize(),
    success: function(returnData){
      $("#booking").html(returnData);
      simulate(document.getElementById('sendbtn'), 'click');
    },
    error: function(e){
      alert(e);
    }
  });
  
}