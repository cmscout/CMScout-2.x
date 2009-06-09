<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage actindo_plugins
 */

/**
 * Include the {@link shared.make_timestamp.php} plugin
 */
require_once $smarty->_get_plugin_filepath('shared','make_timestamp');
/**
 * Smarty date_format_pretty modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     date_format_pretty<br>
 * Purpose:  pretty-print file modification times<br>
 * Input:<br>
 *         - string: input date string
 *         - lang: Locale to use (de_DE,en_US, etc. null for default)
 *         - default_date: default date if $string is empty
 * @author Patrick Prasse <pprasse@actindo.de>
 * @version $Revision: 1.3 $
 * @param string
 * @param string
 * @param string
 * @return string|void
 * @uses smarty_make_timestamp()
 */
function smarty_modifier_date_format_pretty($string, $showtime = true, $default_date=null)
{
  if( $string != '' && $string != '0000-00-00' )
    $date = smarty_make_timestamp( $string );
  elseif( isset($default_date) && $default_date != '' )
    $date = smarty_make_timestamp( $default_date );
  else
    return;

  if ($showtime)
  {
      if( $date > strtotime('today 00:00:00') )
        $d = "Today, " . strftime( '%H:%M', $date );
      elseif( $date > strtotime('yesterday 00:00:00') )
        $d = "Yesterday, ".strftime( '%H:%M', $date );
      elseif( $date > strtotime('-2 days 00:00:00'))   // only for de_* locales
        $d = "2 Days Ago, ".strftime( '%H:%M', $date );
      elseif( $date > strtotime('-1 week 00:00:00') )
        $d = strftime( '%A, %H:%M', $date );
      elseif( $date > strtotime('-1 year 00:00:00') )
        $d = strftime( '%d %B, %H:%M', $date );
      else
        $d = strftime( '%d %B %Y, %H:%M', $date );
  }
  else
  {
        if( $date > strtotime('today 00:00:00') )
        $d = "Today";
      elseif( $date > strtotime('yesterday 00:00:00') )
        $d = "Yesterday";
      elseif( $date > strtotime('-2 days 00:00:00'))   // only for de_* locales
        $d = "2 Days Ago, ".strftime( '%H:%M', $date );
      elseif( $date > strtotime('-1 week 00:00:00') )
        $d = strftime( '%A', $date );
      elseif( $date > strtotime('-1 year 00:00:00') )
        $d = strftime( '%d %B', $date );
      else
        $d = strftime( '%d %B %Y', $date );
  }
  
  if( isset($lang) )
    setlocale( LC_TIME, $save_lang );

  return $d;
}
?>