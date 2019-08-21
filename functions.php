<?php

//--------------------------------
// CREATE WATERMARK FUNCTION
//--------------------------------

//define( 'WATERMARK_OVERLAY_IMAGE', '../images/watermark.png' );
//define( 'WATERMARK_OVERLAY_IMAGE_THUMB', '../images/watermark_thumb.png' );
//define( 'WATERMARK_OVERLAY_OPACITY', 50 );
//define( 'WATERMARK_OUTPUT_QUALITY', 80 );

function applyThumbWatermark($source_file_path, $output_file_path)
{
    list($source_width, $source_height, $source_type) = getimagesize($source_file_path);

    if ($source_type === NULL) {
        return false;
    }

    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_file_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_file_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_file_path);
            break;
        default:
            return false;
    }

    $overlay_gd_image = imagecreatefrompng(WATERMARK_OVERLAY_IMAGE_THUMB);
    $overlay_width = imagesx($overlay_gd_image);
    $overlay_height = imagesy($overlay_gd_image);

    imagecopymerge(
        $source_gd_image,
        $overlay_gd_image,
        $source_width - $overlay_width,
        $source_height - $overlay_height,
        0,
        0,
        $overlay_width,
        $overlay_height,
        WATERMARK_OVERLAY_OPACITY
    );

    imagejpeg($source_gd_image, $output_file_path, WATERMARK_OUTPUT_QUALITY);

    imagedestroy($source_gd_image);
    imagedestroy($overlay_gd_image);
}

function applyWatermark($source_file_path, $output_file_path)
{
    list($source_width, $source_height, $source_type) = getimagesize($source_file_path);

    if ($source_type === NULL) {
        return false;
    }

    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_file_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_file_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_file_path);
            break;
        default:
            return false;
    }

    $overlay_gd_image = imagecreatefrompng(WATERMARK_OVERLAY_IMAGE);
    $overlay_width = imagesx($overlay_gd_image);
    $overlay_height = imagesy($overlay_gd_image);

    imagecopymerge(
        $source_gd_image,
        $overlay_gd_image,
        intval($source_width / 2) - intval($overlay_width / 2),
        intval($source_height / 2) - intval($overlay_height / 2),
        0,
        0,
        $overlay_width,
        $overlay_height,
        WATERMARK_OVERLAY_OPACITY
    );

    imagejpeg($source_gd_image, $output_file_path, WATERMARK_OUTPUT_QUALITY);

    imagedestroy($source_gd_image);
    imagedestroy($overlay_gd_image);
}

function show_pages($total, $nr_products_per_page)
{
    $nr_pages = intval($total / $nr_products_per_page) + (($total % $nr_products_per_page > 0) ? 1 : 0);
    $show_pg = "<div>";
    for ($j = 1; $j < ($nr_pages + 1); $j++) {
        $url = end(explode("_", $_SERVER['REQUEST_URI']));
        if (is_numeric($url))
            $uri = str_replace("_" . $url, "_" . $j, $_SERVER['REQUEST_URI']);
        else
            $uri = $_SERVER['REQUEST_URI'] . "_" . $j;
        $page_link = "http://www.christiantransfers.eu" . $uri;
        $show_pg .= '<a href="' . $page_link . '" title="christian transfer page ' . $j . '">' . $j . '</a>&nbsp;';
    }
    $show_pg .= "</div>";
    return $show_pg;
}

function changeLanguageUrl($new_lang, $old_lang)
{

    $uri = $_SERVER['REQUEST_URI'];
    $new_url = "http://" . $new_lang . ".christiantransfers.eu" . $uri;
    //$new_url = str_replace($prefix_old,$prefix_new,$new_url);

    /*switch ($type)
    {
        case 'index':
        break;

        case 'region':
        $sql = "SELECT nume_regiune_".$new_lang." from REGIUNE WHERE nume_regiune_".$old_lang."='".$ident."'";
        $rez = makeQuery($sql);
        $ident = strtolower(str_replace(" ","_",$ident));
        $new_url = str_replace($ident,strtolower(str_replace(" ","_",$rez[0][0])),$new_url);
        break;

        case 'county':
        break;

        case 'city':
        break;

        case 'hotel':
        break;
    }*/

    $new_url = $new_url;//vezi daca e important sa fie cu litere mici strtolower

    return $new_url;
}

function ShowDate($data)//transforms date from mysql: yyyy-mm-dd to dd-mm-yyyy
{
    return substr($data, 8, 2) . "-" . substr($data, 5, 2) . "-" . substr($data, 0, 4);
}

function GettheDate($data)//transforms date from mysql: dd.mm.yyyy to yyyy-mm-dd
{
    $temp = explode("-", $data);
    ($temp[0] < 10 && strlen($temp[0]) == 1) ? $temp[0] = "0" . $temp[0] : $temp[0];
    ($temp[1] < 10 && strlen($temp[1]) == 1) ? $temp[1] = "0" . $temp[1] : $temp[1];
    return $temp[2] . "-" . $temp[1] . "-" . $temp[0];
    //return substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
}

function extraOption($counter, $selected = '')
{
    $rezopt = "";
    for ($i = 1; $i <= $counter; $i++) {
        $select = "";
        if ($i == $selected) $select = "selected";
        $rezopt .= "<option $select value=\"$i\">$i</option>";
    }
    return $rezopt;
}

function ps_clean($description)
{
    $description = str_replace("&quot;", "", $description);
    $description = str_replace("'", "", $description);
    $description = str_replace('"', '123', $description);
    $description = str_replace("?", "", $description);
    $description = str_replace("&", "", $description);
    $description = trim($description);
    return trim(substr($description, 0, 100));
}


function calculateSign($variables, $key)
{
    return strtoupper(romcard_mac($variables, $key));
}

function hmacsha1($key, $data)
{
    $blocksize = 64;
    $hashfunc = 'sha1';

    if (strlen($key) > $blocksize)
        $key = pack('H*', $hashfunc($key));

    $key = str_pad($key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);

    $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));
    return bin2hex($hmac);
}

function romcard_mac($data, $key = NULL)
{
    $str = NULL;
    foreach ($data as $d) {
        if ($d === NULL || strlen($d) == 0) $str .= '-';
        else                               $str .= strlen($d) . $d;
    }
    $key = pack('H*', $key);
    return hmacsha1($key, $str);
}

function addZeros($nr)
{
    $L = 9 - strlen($nr);
    for ($i = 0; $i < $L; $i++) $nr = '0' . $nr;
    return $nr;
}

function makeQuery($query)
{
    $mysql_link = mysqli_connect('localhost', 'brgambal_eu', 'brgambal20!^', 'brgambal_eu')
    or die('error');
    //mysql_select_db('christia_ro');
    $rez = mysqli_query($mysql_link, $query);

    $rez_array = array();
    while ($row = mysqli_fetch_row($rez)) {
        array_push($rez_array, $row);
    }
    return ($rez_array);
}

/*function makeQuery_forum($query)
	{
	mysql_connect('localhost','restabm_restaur','trinity')
		or die('error');
	mysql_select_db('restabm_forum');
	$rez = mysql_query($query);
	
	$rez_array = array();
	while($row = mysql_fetch_row($rez))
		{
			array_push($rez_array,$row);
		}
	mysql_select_db('restabm_rs');
	return($rez_array);
	}*/
function makeUrl($type, $id, $var2 = "")//var2 = cea de dupa "/" exemplu: dom.ro/type/VAR2-id
{


    //get Translation registry
    $translation = Zend_Registry::get('TRANSLATIONS');
    $titluri = Zend_Registry::get('TITLURI');
    //$hotels_translation = $translation['hotels_clean'];

    $url = "http://www";
    //$url .= strtolower($_SESSION['language']);
    $url .= ".christiantransfers.eu/";
    $url .= $type . "/";
    $url .= $var2 . "-";
    $url .= str_replace(" ", "_", $id);
    //$url .= ".html";

    return $url;
}

function ezmakeUrl($type, $place)
{


    //get Translation registry
    $translation = Zend_Registry::get('TRANSLATIONS');
    $titluri = Zend_Registry::get('TITLURI');
    //$hotels_translation = $translation['hotels_clean'];

    $url = "http://www";
    $url .= ".christiantransfers.eu/";
    $url .= $type . "/";
    $url .= str_replace(" ", "_", $place);

    return $url;
}

function shorten_string($string, $words)
{
    $result = "";
    if (isset($string)) {
        $rez = explode(" ", $string);
        for ($i = 0; $i < $words; $i++)
            $result .= (isset($rez[$i])) ? $rez[$i] . " " : "";
    }
    return $result;
}


include('db_mysql.inc');

class CDb extends DB_Sql
{
    var $classname = "CDb";
    var $Host = "localhost";
    var $Database = "brgambal_eu";
    var $User = "brgambal_eu";
    var $Password = "brgambal20!^";


    function haltmsg($msg)
    {
        printf("</td></table><b>Database error:</b> %s<br>\n", $msg);
        printf("<b>MySQL Error</b>: %s (%s)<br>\n", $this->Errno, $this->Error);
        printf("Please contact <your contact email here> and report the ");
        printf("exact error message.<br>\n");
    }
}

function file_delete($file, $delete, $image)
{
    $q = new CDb();
    if (unlink($file)) {
        $query = "update UNITATE set poza$image='' where poza$image='images/unitati/" . $delete . "'";
        $q->query($query);
        return true;
    } else {
        return false;
    }
}

function file_delete_thumb($file)
{
    $q = new CDb();
    if (unlink($file)) {
        return true;
    } else {
        return false;
    }
}

function ditchtn($item, $thumbname)
{
    if (!preg_match("/^" . $thumbname . "/", $item)) {
        $tmparr = $item;
    }
    return $tmparr;
}

function createthumb($name, $filename, $new_w, $new_h)
{
    if (preg_match("/jpg|jpeg/", $name)) {
        $src_img = imagecreatefromjpeg($name);
    }
    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);
    if ($old_x > 120 || $old_y > 90) {
        if ($old_x > $old_y)//landscape picture
        {
            $thumb_w = $new_w;
            $thumb_h = $old_y * ($new_w / $old_x);
        }
        if ($old_x < $old_y) {
            $thumb_w = $old_x * ($new_h / $old_y);
            $thumb_h = $new_h;
        }
        if ($old_x == $old_y) {
            $thumb_w = $new_h;
            $thumb_h = $new_h;
        }
    } else {
        $thumb_w = $old_x;
        $thumb_h = $old_y;
    }
    $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

    //echo $filename;die();
    imagejpeg($dst_img, $filename);
    // Add stamp.
    //applyThumbWatermark($filename,$filename);

    imagedestroy($dst_img);
    imagedestroy($src_img);
}

function createnormal($name, $filename, $new_w, $new_h)
{
    if (preg_match("/jpg|jpeg/", $name)) {
        $src_img = imagecreatefromjpeg($name);
    }
    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);
    if ($old_x > 580 || $old_y > 435) {
        if ($old_x > $old_y) {
            $thumb_w = $new_w;
            $thumb_h = $old_y * ($new_w / $old_x);
        }
        if ($old_x < $old_y) {
            $thumb_w = $old_x * ($new_h / $old_y);
            $thumb_h = $new_h;
        }
        if ($old_x == $old_y) {
            $thumb_w = $new_h;
            $thumb_h = $new_h;
        }
    } else {
        $thumb_w = $old_x;
        $thumb_h = $old_y;
    }
    $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

    //echo $filename;die();
    imagejpeg($dst_img, $filename);
    // Add stamp.
    //applyWatermark($filename,$filename);

    imagedestroy($dst_img);
    imagedestroy($src_img);
}

function get_def_lnk()
{
    $q = new cdb;
    $query = "select * from iframe where member_id='0' and weight>views ";
    $q->query($query);
    if ($q->nf() == 0) {
        $query = "update iframe set views=0 where member_id='$id'";
        $q->query($query);
        $query = "select * from iframe where member_id='$id' and weight>views ";
        $q->query($query);

    }
    $q->next_record();
    $link = $q->f("link");
    $query = "update iframe set views=views+1 where id='" . $q->f("id") . "'";
    $q->query($query);
    return $link;

}

function is_alphachar($text)
{

    /* Start a loop through the text, for the length
    of the text. */
    for ($i = 0; $i < strlen($text); $i++) {

        /* If any characters besides letters and
         numbers are found, then return with 1
         so that you know it contains illegal
         characters. */
        if (!ereg("[A-Za-z0-9]", $text[$i])) {
            return 1;
        }
    }
}

function error_page($error)
{
    global $sitename;
    FFileRead("error.html", $content);
    $content = str_replace("{error}", $error, $content);
    $content = str_replace("{sitename}", $sitename, $content);
    return $content;
}

function error_page_no_back($error)
{
    global $sitename;
    FFileRead("template.error.clicks.htm", $content);
    $content = str_replace("{error}", $error, $content);
    $content = str_replace("{sitename}", $sitename, $content);
    return $content;
}

function URLReadw($tocheck)
{
    $ptr = fsockopen("whois.nsiregistry.net", 43);
    if ($ptr > 0) {
        fputs($ptr, "whois =$tocheck\n");
        while (!feof($ptr)) {
            $output .= fgets($ptr, 1024);
        }
    }
    fclose($ptr);
    return $output;
    // passthru("whois -h whois.nsiregistry.net -p 43 =$tocheck",$out);
    // return $out;
}


function FFileRead($name/*filename*/, &$contents/*returned contents of file*/)
{
    $fd = fopen($name, "r");
    $contents = fread($fd, filesize($name));
    fclose($fd);
}

function openSocket($page)
{ // $page contains the url of the page to open
    $socketArgs = getArgsForSocket($page);
    $timeout = 30;
    $content = '';
    if (!$fp = fsockopen($socketArgs['domain'], $socketArgs['port'], $errno, $errstr, $timeout)) {
        return "$errno: $errstr";
    }
    fwrite($fp, 'GET ' . $socketArgs['file'] . " HTTP/1.0\r\n");
    fwrite($fp, 'Host: ' . $socketArgs['domain'] . "\r\n\r\n");
    while (!feof($fp)) {
        $content .= fread($fp, 512);
    }
    fclose($fp);

    return $content;
}

function getArgsForSocket($page)
{
    $pArray = parse_url($page);
    $socketArgs['domain'] = $pArray['host'];
    $socketArgs['port'] = isset($pArray['port']) ? $pArray['port'] : 80;
    $socketArgs['file'] = '';
    $socketArgs['file'] .= isset($pArray['path']) ? $pArray['path'] : '/';
    $socketArgs['file'] .= isset($pArray['query']) ? '?' . $pArray['query'] : '';
    return $socketArgs;
}


function HTTP_Post($URL, $data, $referrer = "")
{

    // parsing the given URL
    //$URL_Info=parse_url($URL);

    // Building referer
    //if($referer=="") // if not given use this script as referer
    //  $referer=$_SERVER["SCRIPT_URI"];


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_POST, 0);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function get_setting($name)
{
    $q = new CDb;
    $query = "select value from settings where name='$name'";
    $q->query($query);
    if ($q->nf() == 0) return -1;
    else {
        $q->next_record();
        return stripslashes($q->f("value"));
    }
}

function save_setting($name, $value)
{
    $q = new CDb;
    $query = "update settings set value='" . addslashes($value) . "' where name='$name'";
    $q->query($query);
}

function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


function check_domain($tocheck, &$status, &$ed, &$ud)
{
    if (strpos($tocheck, ".org") === false) {
        $output = URLReadw($tocheck);
        ////echo $output;
        $pos = strpos($output, "No match");
        if ($pos === false) {
            $xpos = strpos($output, "ACTIVE");
            if (!($xpos === false)) {
                $status = "2";//Registered
                $pos = strpos($output, "Updated Date:");
                $upddate = substr($output, $pos + 14, 11);
                $pos = strpos($output, "Expiration Date:");

                $expdate = substr($output, $pos + 17, 11);
            } else {
                $status = "3"; //On Hold
                $pos = strpos($output, "Updated Date:");
                $upddate = substr($output, $pos + 14, 11);
                $pos = strpos($output, "Expiration Date:");
                $expdate = substr($output, $pos + 17, 11);
            }

        } else {
            $status = "1"; //Available
        }
        $expdate = trim($expdate);
        $upddate = trim($upddate);
        $ed = date("Ymd", strtotime($expdate));
        $ud = date("Ymd", strtotime($upddate));
        if (date("Ymd") < $ed) $status = 2;
        if ($status == "1") {
            $expdate = "0";
            $upddate = "0";
            $ed = 0;
            $ud = 0;
        }

    } else {
        exec("whois -h whois.networksolutions.com " . $tocheck, $output);
        $output = implode("", $output);
        //echo $output;
        $pos = strpos($output, "NOT FOUND");
        if ($pos === false) {
            $xpos = strpos($output, "Status:OK");
            if (!($xpos === false)) {
                $status = "2";//Registered
                $pos = strpos($output, "Last Updated On:");
                $upddate = substr($output, $pos + 16, 11);
                $pos = strpos($output, "Expiration Date:");
                $expdate = substr($output, $pos + 16, 11);
            } else {
                $status = "3"; //On Hold
                $pos = strpos($output, "Last Updated On:");
                $upddate = substr($output, $pos + 16, 11);
                $pos = strpos($output, "Record expires on ");
                $expdate = substr($output, $pos + 18, 11);
            }

        } else {
            $status = "1"; //Available
        }
        $expdate = trim($expdate);
        $upddate = trim($upddate);
        $ed = date("Ymd", strtotime($expdate));
        $ud = date("Ymd", strtotime($upddate));
        if (date("Ymd") < $ed) $status = 2;
        if ($status == "1") {
            $expdate = "0";
            $upddate = "0";
            $ed = 0;
            $ud = 0;
        }

    }
}

function mobile_device_detect($var_desk = 0, $iphone = true, $ipad = true, $android = true, $opera = true, $blackberry = true, $palm = true, $windows = true, $mobileredirect = false, $desktopredirect = false)
{

    $mobile_browser = false; // set mobile browser as false till we can prove otherwise
    $user_agent = $_SERVER['HTTP_USER_AGENT']; // get the user agent value - this should be cleaned to ensure no nefarious input gets executed
    $accept = $_SERVER['HTTP_ACCEPT']; // get the content accept value - this should be cleaned to ensure no nefarious input gets executed

    switch (true) { // using a switch against the following statements which could return true is more efficient than the previous method of using if statements

        case (preg_match('/ipad/i', $user_agent)); // we find the word ipad in the user agent
            $mobile_browser = $ipad; // mobile browser is either true or false depending on the setting of ipad when calling the function
            $status = 'Apple iPad';
            if (substr($ipad, 0, 4) == 'http') { // does the value of ipad resemble a url
                $mobileredirect = $ipad; // set the mobile redirect url to the url value stored in the ipad value
            } // ends the if for ipad being a url
            break; // break out and skip the rest if we've had a match on the ipad // this goes before the iphone to catch it else it would return on the iphone instead

        case (preg_match('/ipod/i', $user_agent) || preg_match('/iphone/i', $user_agent)); // we find the words iphone or ipod in the user agent
            $mobile_browser = $iphone; // mobile browser is either true or false depending on the setting of iphone when calling the function
            $status = 'Apple';
            if (substr($iphone, 0, 4) == 'http') { // does the value of iphone resemble a url
                $mobileredirect = $iphone; // set the mobile redirect url to the url value stored in the iphone value
            } // ends the if for iphone being a url
            break; // break out and skip the rest if we've had a match on the iphone or ipod

        case (preg_match('/android/i', $user_agent) && $var_desk == 0);  // we find android in the user agent
            $mobile_browser = $android; // mobile browser is either true or false depending on the setting of android when calling the function
            $status = 'Android';
            if (substr($android, 0, 4) == 'http') { // does the value of android resemble a url
                $mobileredirect = $android; // set the mobile redirect url to the url value stored in the android value
            } // ends the if for android being a url
            break; // break out and skip the rest if we've had a match on android

        case (preg_match('/opera mini/i', $user_agent)); // we find opera mini in the user agent
            $mobile_browser = $opera; // mobile browser is either true or false depending on the setting of opera when calling the function
            $status = 'Opera';
            if (substr($opera, 0, 4) == 'http') { // does the value of opera resemble a rul
                $mobileredirect = $opera; // set the mobile redirect url to the url value stored in the opera value
            } // ends the if for opera being a url
            break; // break out and skip the rest if we've had a match on opera

        case (preg_match('/blackberry/i', $user_agent)); // we find blackberry in the user agent
            $mobile_browser = $blackberry; // mobile browser is either true or false depending on the setting of blackberry when calling the function
            $status = 'Blackberry';
            if (substr($blackberry, 0, 4) == 'http') { // does the value of blackberry resemble a rul
                $mobileredirect = $blackberry; // set the mobile redirect url to the url value stored in the blackberry value
            } // ends the if for blackberry being a url
            break; // break out and skip the rest if we've had a match on blackberry

        case (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i', $user_agent)); // we find palm os in the user agent - the i at the end makes it case insensitive
            $mobile_browser = $palm; // mobile browser is either true or false depending on the setting of palm when calling the function
            $status = 'Palm';
            if (substr($palm, 0, 4) == 'http') { // does the value of palm resemble a rul
                $mobileredirect = $palm; // set the mobile redirect url to the url value stored in the palm value
            } // ends the if for palm being a url
            break; // break out and skip the rest if we've had a match on palm os

        case (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i', $user_agent)); // we find windows mobile in the user agent - the i at the end makes it case insensitive
            $mobile_browser = $windows; // mobile browser is either true or false depending on the setting of windows when calling the function
            $status = 'Windows Smartphone';
            if (substr($windows, 0, 4) == 'http') { // does the value of windows resemble a rul
                $mobileredirect = $windows; // set the mobile redirect url to the url value stored in the windows value
            } // ends the if for windows being a url
            break; // break out and skip the rest if we've had a match on windows

        case (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i', $user_agent)); // check if any of the values listed create a match on the user agent - these are some of the most common terms used in agents to identify them as being mobile devices - the i at the end makes it case insensitive
            $mobile_browser = true; // set mobile browser to true
            $status = 'Mobile matched on piped preg_match';
            break; // break out and skip the rest if we've preg_match on the user agent returned true

        case ((strpos($accept, 'text/vnd.wap.wml') > 0) || (strpos($accept, 'application/vnd.wap.xhtml+xml') > 0)); // is the device showing signs of support for text/vnd.wap.wml or application/vnd.wap.xhtml+xml
            $mobile_browser = true; // set mobile browser to true
            $status = 'Mobile matched on content accept header';
            break; // break out and skip the rest if we've had a match on the content accept headers

        case (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])); // is the device giving us a HTTP_X_WAP_PROFILE or HTTP_PROFILE header - only mobile devices would do this
            $mobile_browser = true; // set mobile browser to true
            $status = 'Mobile matched on profile headers being set';
            break; // break out and skip the final step if we've had a return true on the mobile specfic headers

        case (in_array(strtolower(substr($user_agent, 0, 4)), array('1207' => '1207', '3gso' => '3gso', '4thp' => '4thp', '501i' => '501i', '502i' => '502i', '503i' => '503i', '504i' => '504i', '505i' => '505i', '506i' => '506i', '6310' => '6310', '6590' => '6590', '770s' => '770s', '802s' => '802s', 'a wa' => 'a wa', 'acer' => 'acer', 'acs-' => 'acs-', 'airn' => 'airn', 'alav' => 'alav', 'asus' => 'asus', 'attw' => 'attw', 'au-m' => 'au-m', 'aur ' => 'aur ', 'aus ' => 'aus ', 'abac' => 'abac', 'acoo' => 'acoo', 'aiko' => 'aiko', 'alco' => 'alco', 'alca' => 'alca', 'amoi' => 'amoi', 'anex' => 'anex', 'anny' => 'anny', 'anyw' => 'anyw', 'aptu' => 'aptu', 'arch' => 'arch', 'argo' => 'argo', 'bell' => 'bell', 'bird' => 'bird', 'bw-n' => 'bw-n', 'bw-u' => 'bw-u', 'beck' => 'beck', 'benq' => 'benq', 'bilb' => 'bilb', 'blac' => 'blac', 'c55/' => 'c55/', 'cdm-' => 'cdm-', 'chtm' => 'chtm', 'capi' => 'capi', 'cond' => 'cond', 'craw' => 'craw', 'dall' => 'dall', 'dbte' => 'dbte', 'dc-s' => 'dc-s', 'dica' => 'dica', 'ds-d' => 'ds-d', 'ds12' => 'ds12', 'dait' => 'dait', 'devi' => 'devi', 'dmob' => 'dmob', 'doco' => 'doco', 'dopo' => 'dopo', 'el49' => 'el49', 'erk0' => 'erk0', 'esl8' => 'esl8', 'ez40' => 'ez40', 'ez60' => 'ez60', 'ez70' => 'ez70', 'ezos' => 'ezos', 'ezze' => 'ezze', 'elai' => 'elai', 'emul' => 'emul', 'eric' => 'eric', 'ezwa' => 'ezwa', 'fake' => 'fake', 'fly-' => 'fly-', 'fly_' => 'fly_', 'g-mo' => 'g-mo', 'g1 u' => 'g1 u', 'g560' => 'g560', 'gf-5' => 'gf-5', 'grun' => 'grun', 'gene' => 'gene', 'go.w' => 'go.w', 'good' => 'good', 'grad' => 'grad', 'hcit' => 'hcit', 'hd-m' => 'hd-m', 'hd-p' => 'hd-p', 'hd-t' => 'hd-t', 'hei-' => 'hei-', 'hp i' => 'hp i', 'hpip' => 'hpip', 'hs-c' => 'hs-c', 'htc ' => 'htc ', 'htc-' => 'htc-', 'htca' => 'htca', 'htcg' => 'htcg', 'htcp' => 'htcp', 'htcs' => 'htcs', 'htct' => 'htct', 'htc_' => 'htc_', 'haie' => 'haie', 'hita' => 'hita', 'huaw' => 'huaw', 'hutc' => 'hutc', 'i-20' => 'i-20', 'i-go' => 'i-go', 'i-ma' => 'i-ma', 'i230' => 'i230', 'iac' => 'iac', 'iac-' => 'iac-', 'iac/' => 'iac/', 'ig01' => 'ig01', 'im1k' => 'im1k', 'inno' => 'inno', 'iris' => 'iris', 'jata' => 'jata', 'java' => 'java', 'kddi' => 'kddi', 'kgt' => 'kgt', 'kgt/' => 'kgt/', 'kpt ' => 'kpt ', 'kwc-' => 'kwc-', 'klon' => 'klon', 'lexi' => 'lexi', 'lg g' => 'lg g', 'lg-a' => 'lg-a', 'lg-b' => 'lg-b', 'lg-c' => 'lg-c', 'lg-d' => 'lg-d', 'lg-f' => 'lg-f', 'lg-g' => 'lg-g', 'lg-k' => 'lg-k', 'lg-l' => 'lg-l', 'lg-m' => 'lg-m', 'lg-o' => 'lg-o', 'lg-p' => 'lg-p', 'lg-s' => 'lg-s', 'lg-t' => 'lg-t', 'lg-u' => 'lg-u', 'lg-w' => 'lg-w', 'lg/k' => 'lg/k', 'lg/l' => 'lg/l', 'lg/u' => 'lg/u', 'lg50' => 'lg50', 'lg54' => 'lg54', 'lge-' => 'lge-', 'lge/' => 'lge/', 'lynx' => 'lynx', 'leno' => 'leno', 'm1-w' => 'm1-w', 'm3ga' => 'm3ga', 'm50/' => 'm50/', 'maui' => 'maui', 'mc01' => 'mc01', 'mc21' => 'mc21', 'mcca' => 'mcca', 'medi' => 'medi', 'meri' => 'meri', 'mio8' => 'mio8', 'mioa' => 'mioa', 'mo01' => 'mo01', 'mo02' => 'mo02', 'mode' => 'mode', 'modo' => 'modo', 'mot ' => 'mot ', 'mot-' => 'mot-', 'mt50' => 'mt50', 'mtp1' => 'mtp1', 'mtv ' => 'mtv ', 'mate' => 'mate', 'maxo' => 'maxo', 'merc' => 'merc', 'mits' => 'mits', 'mobi' => 'mobi', 'motv' => 'motv', 'mozz' => 'mozz', 'n100' => 'n100', 'n101' => 'n101', 'n102' => 'n102', 'n202' => 'n202', 'n203' => 'n203', 'n300' => 'n300', 'n302' => 'n302', 'n500' => 'n500', 'n502' => 'n502', 'n505' => 'n505', 'n700' => 'n700', 'n701' => 'n701', 'n710' => 'n710', 'nec-' => 'nec-', 'nem-' => 'nem-', 'newg' => 'newg', 'neon' => 'neon', 'netf' => 'netf', 'noki' => 'noki', 'nzph' => 'nzph', 'o2 x' => 'o2 x', 'o2-x' => 'o2-x', 'opwv' => 'opwv', 'owg1' => 'owg1', 'opti' => 'opti', 'oran' => 'oran', 'p800' => 'p800', 'pand' => 'pand', 'pg-1' => 'pg-1', 'pg-2' => 'pg-2', 'pg-3' => 'pg-3', 'pg-6' => 'pg-6', 'pg-8' => 'pg-8', 'pg-c' => 'pg-c', 'pg13' => 'pg13', 'phil' => 'phil', 'pn-2' => 'pn-2', 'pt-g' => 'pt-g', 'palm' => 'palm', 'pana' => 'pana', 'pire' => 'pire', 'pock' => 'pock', 'pose' => 'pose', 'psio' => 'psio', 'qa-a' => 'qa-a', 'qc-2' => 'qc-2', 'qc-3' => 'qc-3', 'qc-5' => 'qc-5', 'qc-7' => 'qc-7', 'qc07' => 'qc07', 'qc12' => 'qc12', 'qc21' => 'qc21', 'qc32' => 'qc32', 'qc60' => 'qc60', 'qci-' => 'qci-', 'qwap' => 'qwap', 'qtek' => 'qtek', 'r380' => 'r380', 'r600' => 'r600', 'raks' => 'raks', 'rim9' => 'rim9', 'rove' => 'rove', 's55/' => 's55/', 'sage' => 'sage', 'sams' => 'sams', 'sc01' => 'sc01', 'sch-' => 'sch-', 'scp-' => 'scp-', 'sdk/' => 'sdk/', 'se47' => 'se47', 'sec-' => 'sec-', 'sec0' => 'sec0', 'sec1' => 'sec1', 'semc' => 'semc', 'sgh-' => 'sgh-', 'shar' => 'shar', 'sie-' => 'sie-', 'sk-0' => 'sk-0', 'sl45' => 'sl45', 'slid' => 'slid', 'smb3' => 'smb3', 'smt5' => 'smt5', 'sp01' => 'sp01', 'sph-' => 'sph-', 'spv ' => 'spv ', 'spv-' => 'spv-', 'sy01' => 'sy01', 'samm' => 'samm', 'sany' => 'sany', 'sava' => 'sava', 'scoo' => 'scoo', 'send' => 'send', 'siem' => 'siem', 'smar' => 'smar', 'smit' => 'smit', 'soft' => 'soft', 'sony' => 'sony', 't-mo' => 't-mo', 't218' => 't218', 't250' => 't250', 't600' => 't600', 't610' => 't610', 't618' => 't618', 'tcl-' => 'tcl-', 'tdg-' => 'tdg-', 'telm' => 'telm', 'tim-' => 'tim-', 'ts70' => 'ts70', 'tsm-' => 'tsm-', 'tsm3' => 'tsm3', 'tsm5' => 'tsm5', 'tx-9' => 'tx-9', 'tagt' => 'tagt', 'talk' => 'talk', 'teli' => 'teli', 'topl' => 'topl', 'hiba' => 'hiba', 'up.b' => 'up.b', 'upg1' => 'upg1', 'utst' => 'utst', 'v400' => 'v400', 'v750' => 'v750', 'veri' => 'veri', 'vk-v' => 'vk-v', 'vk40' => 'vk40', 'vk50' => 'vk50', 'vk52' => 'vk52', 'vk53' => 'vk53', 'vm40' => 'vm40', 'vx98' => 'vx98', 'virg' => 'virg', 'vite' => 'vite', 'voda' => 'voda', 'vulc' => 'vulc', 'w3c ' => 'w3c ', 'w3c-' => 'w3c-', 'wapj' => 'wapj', 'wapp' => 'wapp', 'wapu' => 'wapu', 'wapm' => 'wapm', 'wig ' => 'wig ', 'wapi' => 'wapi', 'wapr' => 'wapr', 'wapv' => 'wapv', 'wapy' => 'wapy', 'wapa' => 'wapa', 'waps' => 'waps', 'wapt' => 'wapt', 'winc' => 'winc', 'winw' => 'winw', 'wonu' => 'wonu', 'x700' => 'x700', 'xda2' => 'xda2', 'xdag' => 'xdag', 'yas-' => 'yas-', 'your' => 'your', 'zte-' => 'zte-', 'zeto' => 'zeto', 'acs-' => 'acs-', 'alav' => 'alav', 'alca' => 'alca', 'amoi' => 'amoi', 'aste' => 'aste', 'audi' => 'audi', 'avan' => 'avan', 'benq' => 'benq', 'bird' => 'bird', 'blac' => 'blac', 'blaz' => 'blaz', 'brew' => 'brew', 'brvw' => 'brvw', 'bumb' => 'bumb', 'ccwa' => 'ccwa', 'cell' => 'cell', 'cldc' => 'cldc', 'cmd-' => 'cmd-', 'dang' => 'dang', 'doco' => 'doco', 'eml2' => 'eml2', 'eric' => 'eric', 'fetc' => 'fetc', 'hipt' => 'hipt', 'http' => 'http', 'ibro' => 'ibro', 'idea' => 'idea', 'ikom' => 'ikom', 'inno' => 'inno', 'ipaq' => 'ipaq', 'jbro' => 'jbro', 'jemu' => 'jemu', 'java' => 'java', 'jigs' => 'jigs', 'kddi' => 'kddi', 'keji' => 'keji', 'kyoc' => 'kyoc', 'kyok' => 'kyok', 'leno' => 'leno', 'lg-c' => 'lg-c', 'lg-d' => 'lg-d', 'lg-g' => 'lg-g', 'lge-' => 'lge-', 'libw' => 'libw', 'm-cr' => 'm-cr', 'maui' => 'maui', 'maxo' => 'maxo', 'midp' => 'midp', 'mits' => 'mits', 'mmef' => 'mmef', 'mobi' => 'mobi', 'mot-' => 'mot-', 'moto' => 'moto', 'mwbp' => 'mwbp', 'mywa' => 'mywa', 'nec-' => 'nec-', 'newt' => 'newt', 'nok6' => 'nok6', 'noki' => 'noki', 'o2im' => 'o2im', 'opwv' => 'opwv', 'palm' => 'palm', 'pana' => 'pana', 'pant' => 'pant', 'pdxg' => 'pdxg', 'phil' => 'phil', 'play' => 'play', 'pluc' => 'pluc', 'port' => 'port', 'prox' => 'prox', 'qtek' => 'qtek', 'qwap' => 'qwap', 'rozo' => 'rozo', 'sage' => 'sage', 'sama' => 'sama', 'sams' => 'sams', 'sany' => 'sany', 'sch-' => 'sch-', 'sec-' => 'sec-', 'send' => 'send', 'seri' => 'seri', 'sgh-' => 'sgh-', 'shar' => 'shar', 'sie-' => 'sie-', 'siem' => 'siem', 'smal' => 'smal', 'smar' => 'smar', 'sony' => 'sony', 'sph-' => 'sph-', 'symb' => 'symb', 't-mo' => 't-mo', 'teli' => 'teli', 'tim-' => 'tim-', 'tosh' => 'tosh', 'treo' => 'treo', 'tsm-' => 'tsm-', 'upg1' => 'upg1', 'upsi' => 'upsi', 'vk-v' => 'vk-v', 'voda' => 'voda', 'vx52' => 'vx52', 'vx53' => 'vx53', 'vx60' => 'vx60', 'vx61' => 'vx61', 'vx70' => 'vx70', 'vx80' => 'vx80', 'vx81' => 'vx81', 'vx83' => 'vx83', 'vx85' => 'vx85', 'wap-' => 'wap-', 'wapa' => 'wapa', 'wapi' => 'wapi', 'wapp' => 'wapp', 'wapr' => 'wapr', 'webc' => 'webc', 'whit' => 'whit', 'winw' => 'winw', 'wmlb' => 'wmlb', 'xda-' => 'xda-',))); // check against a list of trimmed user agents to see if we find a match
            $mobile_browser = true; // set mobile browser to true
            $status = 'Mobile matched on in_array';
            break; // break even though it's the last statement in the switch so there's nothing to break away from but it seems better to include it than exclude it

        default;
            $mobile_browser = false; // set mobile browser to false
            $status = 'Desktop / full capability browser';
            break; // break even though it's the last statement in the switch so there's nothing to break away from but it seems better to include it than exclude it

    } // ends the switch

    // tell adaptation services (transcoders and proxies) to not alter the content based on user agent as it's already being managed by this script, some of them suck though and will disregard this....
    // header('Cache-Control: no-transform'); // http://mobiforge.com/developing/story/setting-http-headers-advise-transcoding-proxies
    // header('Vary: User-Agent, Accept'); // http://mobiforge.com/developing/story/setting-http-headers-advise-transcoding-proxies

    // if redirect (either the value of the mobile or desktop redirect depending on the value of $mobile_browser) is true redirect else we return the status of $mobile_browser
    if ($redirect = ($mobile_browser == true) ? $mobileredirect : $desktopredirect) {
        header('Location: ' . $redirect); // redirect to the right url for this device
        exit;
    } else {
        // a couple of folkas have asked about the status - that's there to help you debug and understand what the script is doing
        if ($mobile_browser == '') {
            return $mobile_browser; // will return either true or false
        } else {
            return array($mobile_browser, $status); // is a mobile so we are returning an array ['0'] is true ['1'] is the $status value
        }
    }

} // ends function mobile_device_detect

?>
