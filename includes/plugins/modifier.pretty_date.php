<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty pretty_date modifier plugin
 *
 * Type:     modifier
 * Name:     pretty_date
 * Purpose:  Return date in "pretty" format: [month] [dat][st|rd|th], [year]
 * Input:    string: input date string (MySQL DATE format YYYY-MM-DD)
 *           format: TO DO - format of output (optional)          
 *           default_date: default date if $string is empty (optional)
 *           input_format: TO DO - format of date $string variable (optional)
 * 
 * Examples:<br>
 * <pre>
 * {$my_date|pretty_date}
 *  where $my_date = 2004-02-13   
 *  outputs Febuary 13th, 2004 
 * </pre>
 * @author Mark Hewitt <mark@formfunction.co.za>
 * @version  0.9
 * @param string $string Date string to format, default is MySQL date format YYYY-MM-DD
 * @param string $format Optional formatting of output [not yet implemented] 
 * @param string $default_date Optional string specifying date to format if $string is empty  
 * @param string $input_format Optional string specifying format of input [not yet implemented]  
 * @return string|null
 */


function smarty_modifier_pretty_date( $string, 
                                      $format="%b %e, %Y", 
                                      $default_date = null,
                                      $input_format = "YYYY-MM-DD"
                                     )
{                                           

    // if no string given use the default
    if ( empty($string) ) $string = $default_date;
     
    // if there is a string now, format it
    if ( !empty($string) )
    {
        // extract the pieces of the date into an array
        $date_portions = _splitDate($string,$input_format); 

        // determine the correct extensions of the day, as in
        // 23rd or 14th or 1st

	$day_mod_10 = $date_portions['day'] % 10;

        // if day is range 10 - 19 always use extension "th"
        // if day is in 0, 4-9 use "th"                
        if ( ($date_portions['day'] >= 10 && $date_portions['day'] <= 19) ||
             ($day_mod_10 == 0) || 
             ( ($day_mod_10 >= 4) && ($day_mod_10 <= 9) )
           )
        {
            $ext = "th";
        }
        // if day is "1st" use the "st" extension
        elseif ( $day_mod_10 == 1 )
        {
            $ext = "st";
        }
	// if its a "2" then use "nd"
	elseif ( $day_mod_10 == 2 )
	{
            $ext = "nd";
	}	
        // otherwise its a "rd" extension
        else
        {
            $ext = "rd";
        }

        // covert month/day/year to timestamp and use strftime to format
        // the month into a string for locale       
        $month = strftime("%B", mktime(0,0,0,$date_portions['month'],
                                       1,
                                       $date_portions['year']
                                       ) 
                         );

	// use intval to cast any leading zeros of the day
        return $month.' '.intval($date_portions['day']).$ext.', '.$date_portions['year'];

    }

}

/**
 * 
 */
function _splitDate($date_string,$format_string)
{
    // TO-DO : use $format_string for format
    // NOTE  : assumes MySQL DATE format YYYY-MM-DD
             
    $date_portions = explode('-',$date_string);
    return array( "year" => $date_portions[0],
                  "month" => $date_portions[1],
                  "day" => $date_portions[2]
                );
}


/* vim: set expandtab: */

?>