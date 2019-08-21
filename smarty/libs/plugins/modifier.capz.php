<?php
/**
 * Custom Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/**
 * A variant of Smarty capitalize modifier plugin which does not destroy
 * capitalization in case of HTML encoded entitis
 * Author: adrian.spinei@gmail.com
 * Type:     modifier<br>
 * Name:     capitalize<br>
 * Purpose:  capitalize words in the string
 * @param string
 * @return string
 */
function smarty_modifier_capz($string) {
	return ucwords($string);
}
?>

