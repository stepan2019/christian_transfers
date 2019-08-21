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

function search()
{
 document.getElementById('loader_div').style.display='';
   
 $.ajax({
   url: "/application/search_table.php",
   type: "post",
   dataType: "html",
   data: $("form").serialize(),
   success: function(returnData){
     $("#searchtable").html(returnData);
     simulate(document.getElementById('sendbtn'), 'click');
  },
   error: function(e){
     alert(e);
   }
});

// new Ajax.Updater('searchtable', '/application/search_table.php', {
//  evalScripts:       true, 
//  parameters : {
//    pickup_tara: 	   $F('pickup_tara') 
//   ,pickup_locatie:    $F('pickup_locatie') 
//   ,id_destinatie:     $F('id_destinatie')
//   ,oneway:    		   $F('oneway')
//   
//   ,arrival_month:     $F('arrival_month')
//   ,arrival_day:       $F('arrival_day')
//   ,arrival_hour:      $F('arrival_hour')
//   ,arrival_min:       $F('arrival_min')
//   ,arrival_passengers:$F('arrival_passengers')
//   
//   ,dep_month:     	   $F('dep_month')
//   ,dep_day:     	   $F('dep_day')
//   ,dep_hour:          $F('dep_hour')
//   ,dep_min:           $F('dep_min')
//   ,dep_passengers:    $F('dep_passengers')
//  },
//
//  onComplete:function(){simulate(document.getElementById('sendbtn'), 'click')}
//});
}

function makeRequest_zona(url) {

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
alert('Giving up Cannot create an XMLHTTP instance');
return false;
}
http_request.onreadystatechange = function() { alertContents(http_request,url); };
http_request.open('GET', url, true);
http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
http_request.send(null);
}
function alertContents(http_request,url) {
if (http_request.readyState == 4) {
if (http_request.status == 200) {
 document.getElementById("result_zona").innerHTML = http_request.responseText;
  }
  else
  {
  document.getElementById("result_zona").innerHTML ='';
  }
}
}

function makeRequest_specific(url) {

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
alert('Giving up Cannot create an XMLHTTP instance');
return false;
}
http_request.onreadystatechange = function() { alertContents(http_request,url); };
http_request.open('GET', url, true);
http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
http_request.send(null);
}
function alertContents(http_request,url) {
if (http_request.readyState == 4) {
if (http_request.status == 200) {
 document.getElementById("result_specific").innerHTML = http_request.responseText;
  }
  else
  {
  document.getElementById("result_specific").innerHTML ='';
  }
}
}
function show(thisplace, thiscolor) {

	if(document.all) {
		document.all.mapusa.style.filter="chroma(color="+thiscolor+")"
	}
}

function noshow(){

	if(document.all) {
		document.all.mapusa.style.filter=""
	}
}

function closebox(){
	if(document.all || document.layers) {
		clearTimeout(timer)
	}
}


//function handlerMM(e){
//	x = (document.layers) ? e.pageX : event.clientX
//	y = (document.layers) ? e.pageY : event.clientY
//}
//if (document.layers){
//	document.captureEvents(Event.MOUSEMOVE);
//}
//document.onmousemove = handlerMM;

function showj(obj,msg){
judetBox.style.top=obj.offsetTop
judetBox.style.left=obj.offsetLeft+obj.offsetWidth+5
contents.innerHTML=msg
judetBox.style.display="block"
}
function noshowj(){
judetBox.style.display=""
}

function send()
{document.login.submit()}

function doClick(buttonName,e)
    {
//the purpose of this function is to allow the enter key to 
//point to the correct button to click.
        var key;

         if(window.event)
              key = window.event.keyCode;     //IE
         else
              key = e.which;     //firefox
    
        if (key == 13)
        {
            //Get the button the user wants to have clicked
            var btn = document.getElementById(buttonName);
            if (btn != null)
            { //If we find the button click it
                btn.click();
                event.keyCode = 0
            }
        }
   }
var gradientshadow={}
gradientshadow.depth=6 //Depth of shadow in pixels
gradientshadow.containers=[]

gradientshadow.create=function(){
var a = document.all ? document.all : document.getElementsByTagName('*')
for (var i = 0;i < a.length;i++) {
	if (a[i].className == "shadow") {
		for (var x=0; x<gradientshadow.depth; x++){
			var newSd = document.createElement("DIV")
			newSd.className = "shadow_inner"
			newSd.id="shadow"+gradientshadow.containers.length+"_"+x //Each shadow DIV has an id of "shadowL_X" (L=index of target element, X=index of shadow (depth) 
			if (a[i].getAttribute("rel"))
				newSd.style.background = a[i].getAttribute("rel")
			else
				newSd.style.background = "black" //default shadow color if none specified
			document.body.appendChild(newSd)
		}
	gradientshadow.containers[gradientshadow.containers.length]=a[i]
	}
}
gradientshadow.position()
window.onresize=function(){
	gradientshadow.position()
}
}

gradientshadow.position=function(){
if (gradientshadow.containers.length>0){
	for (var i=0; i<gradientshadow.containers.length; i++){
		for (var x=0; x<gradientshadow.depth; x++){
  		var shadowdiv=document.getElementById("shadow"+i+"_"+x)
			shadowdiv.style.width = gradientshadow.containers[i].offsetWidth + "px"
			shadowdiv.style.height = gradientshadow.containers[i].offsetHeight + "px"
			shadowdiv.style.left = gradientshadow.containers[i].offsetLeft + x + "px"
			shadowdiv.style.top = gradientshadow.containers[i].offsetTop + x + "px"
		}
	}
}
}

if (window.addEventListener)
window.addEventListener("load", gradientshadow.create, false)
else if (window.attachEvent)
window.attachEvent("onload", gradientshadow.create)
else if (document.getElementById)
window.onload=gradientshadow.create