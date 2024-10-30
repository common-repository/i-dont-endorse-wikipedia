<?php 
/*
	Plugin Name: I don't endorse Wikipedia
	Plugin URI: https://andreapernici.com/wordpress/nofollow-wikipedia/
	Description: Add rel="nofollow" to all links going to *.Wikipedia.*
	Version: 1.0.2
	Author: Andrea Pernici
	Author URI: https://www.andreapernici.com/
	
	Copyright 2014 Andrea Pernici (andreapernici@gmail.com)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	*/

define( 'NOFOLLOWWIKIPEDIA_VERSION', '1.0.2' );

$pluginurl = plugin_dir_url(__FILE__);
if ( preg_match( '/^https/', $pluginurl ) && !preg_match( '/^https/', get_bloginfo('url') ) )
	$pluginurl = preg_replace( '/^https/', 'http', $pluginurl );
define( 'NOFOLLOWWIKIPEDIA_FRONT_URL', $pluginurl );

define( 'NOFOLLOWWIKIPEDIA_URL', plugin_dir_url(__FILE__) );
define( 'NOFOLLOWWIKIPEDIA_PATH', plugin_dir_path(__FILE__) );
define( 'NOFOLLOWWIKIPEDIA_BASENAME', plugin_basename( __FILE__ ) );

if (!class_exists("AndreaNofollowWikipedia")) {

	class AndreaNofollowWikipedia {
		/**
		 * Class Constructor
		 */
		function AndreaNofollowWikipedia(){
		
		}
		
		/**
		 * Enabled the AndreaNofollowWikipedia plugin with registering all required hooks
		 */
		function Enable() {

			add_filter("the_content", array("AndreaNofollowWikipedia","AutoNofollowWikipedia"));
			
		}
		
		function AutoNofollowWikipedia($content) {	
			
			return preg_replace_callback("#(<a[^>]+?)>#is", create_function(
	            '$m',
	            'global $urls; 
	            $urls = array( 
	            		"wikipedia" => "\.wikipedia\.", 
	            		"wikipedianowww" => "//wikipedia\."
				); 
				$flag = false; $flagdue = false;  
	             	foreach($urls as $url){
		                if(preg_match("#".$url."#i", $m[1])){ 
		                   $flag = true; 
		                }
		            }
	             if (($flag)) $m[1] .= " rel=\"nofollow\"";
	             return $m[1].">";
	            '
	          ), $content);
		}
		
			
		function DefineArray(){
			$arraygoogleprop = array( 
				"wikipedia" => "\.wikipedia\.", 
	            "wikipedianowww" => "//wikipedia\."
			);
		}
	}
}


/*
 * Plugin activation
 */
 
if (class_exists("AndreaNofollowWikipedia")) {
	$anfs = new AndreaNofollowWikipedia();
}


if (isset($anfs)) {
	add_action("init",array("AndreaNofollowWikipedia","Enable"),1000,0);
}

if (!function_exists('andrea_nofollow_google')) {
	function andrea_nofollow_wikipedia() {
		$wikipedia_nofollow = new AndreaNofollowWikipedia();
		return $wikipedia_nofollow->AutoNofollowWikipedia();
	}	
}

?>
