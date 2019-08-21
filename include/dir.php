<?php

//    PHP Random File Picker - v1.0
//    Copyright (C) 2001 Chris Green - Webcreationz
//    Web   : http://webcreationz.recalldigit.co.uk
//    Email : chris.green@webcreationz.co.uk 
//
//    This program is free software; you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation; either version 2 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program; if not, write to the Free Software
//    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

//    The script simply randomly selects a file from a specified directory.
//    The function adir reads the filenames into an array.
//    The function randarray returns a random element from an array.
//    The function pint_array is simply for testing purposes.
//    See random-file-test.php for an example of these functions in action.

function adir($dirname) {
$handle=opendir($dirname); 
$dirarray = array();
while ($file = readdir($handle)) 
{
if ($file > "..") {array_push($dirarray,$file);}
}
closedir($handle);
return $dirarray;
}

function randdir($dirname) 
{
srand ((double) microtime() * 10000000);
$dirarray = adir($dirname);
$rndfile = array_rand($dirarray);
return $dirarray[$rndfile];
}

function print_array($array) { 
if(gettype($array)=="array") { 
echo "<ul>"; 
while (list($index, $subarray) = each($array) ) { 
echo "<li>$index <code>=&gt;</code> "; 
print_array($subarray); 
echo "</li>"; 
} 
echo "</ul>"; 
} else echo $array; 
} 

?>
