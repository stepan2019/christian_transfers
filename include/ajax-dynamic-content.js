/************************************************************************************************************
(C) www.dhtmlgoodies.com, June 2006

This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	

Terms of use:
You are free to use this script as long as the copyright message is kept intact. However, you may not
redistribute, sell or repost it without our permission.

Thank you!

www.dhtmlgoodies.com
Alf Magne Kalleland

************************************************************************************************************/

var dynamicContent_ajaxObjects = new Array(); 
var jsCache = new Array();
var enableCache = true; 

function ajax_loadContent(divId,pathToFile)
{
  if(enableCache && jsCache[pathToFile]){
    document.getElementById(divId).innerHTML = jsCache[pathToFile];
    return;
  }
  
  var ajaxIndex = dynamicContent_ajaxObjects.length;
  //document.getElementById(divId).innerHTML = 'Loading content...';
  dynamicContent_ajaxObjects[ajaxIndex] = new sack();
  dynamicContent_ajaxObjects[ajaxIndex].requestFile = pathToFile;

  dynamicContent_ajaxObjects[ajaxIndex].onCompletion = 
  function(){ ajax_showContent(divId,ajaxIndex,pathToFile); };  

  dynamicContent_ajaxObjects[ajaxIndex].runAJAX();  
  
  
} 

function ajax_showContent(divId,ajaxIndex,pathToFile)
{
  document.getElementById(divId).innerHTML =
    dynamicContent_ajaxObjects[ajaxIndex].response;
  if(enableCache){
    jsCache[pathToFile] = 
    dynamicContent_ajaxObjects[ajaxIndex].response;
  }
  dynamicContent_ajaxObjects[ajaxIndex] = false;
}



