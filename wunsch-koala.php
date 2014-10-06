<?php
/*
PLUGIN NAME: Wunsch Koala - Joey der Wunschlisten Verwalter
PLUGIN URI: http://www.wunsch-koala.de
DESCRIPTION: Biete deinen Besuchern die Möglichkeit, beliebige Artikel auf ihre Wunschliste beim Wunsch Koala zu setzen.
AUTHOR: Jonas Breuer
AUTHOR URI: http://www.j-breuer.de
VERSION: 0.1.0
Min WP Version: 3.0.0
Max WP Version: 4.0.0
License: GPL3
*/


/* Copyright 2014 Jonas Breuer (email : kontakt@j-breuer.de)
 
This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 3, as
 published by the Free Software Foundation.
 
This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

define('WUNSCH_KOALA_VERSION', '0.1.0');

include_once("wunsch-koala-settings.php");

register_activation_hook(__FILE__, array('Wunsch_Koala', 'activation'));
register_uninstall_hook(__FILE__, array('Wunsch_Koala', 'uninstall'));

add_filter('the_content', array('Wunsch_Koala', 'addAutolinks'));

add_shortcode('wunsch-koala-link', array('Wunsch_Koala', 'addLink'));





class Wunsch_Koala {

	static public function activation() {
		//standard options if this is a new installation
		$options = get_option('wunsch-koala-options');
		if (!$options) {
			$options = array(
				'affiliate-id' => '',
				'autolink-active' => 0,
				'autolink-domains' => 'amazon.de, ebay.de',
				'autolink-html-before' => '<br />',
				'autolink-linktext' => 'Auf die Wunschliste',
				'autolink-wish-name' => 'source',
				'autolink-wish-link' => 'source',
				'autolink-html-after' => ''
				
			);
			update_option('wunsch-koala-options', $options);
		}
	}
	
	
	static public function uninstall() {
		delete_option('wunsch-koala-options');
	}
	
	
	public static function addAutolinks($content) {
		$options = get_option('wunsch-koala-options');
		if (!$options['autolink-active']) return $content;
		
		//name and link are fixed now, should be dynamic later
		$name = get_the_title();
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
		$link = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		$name = urlencode($name);
		$link = urlencode($link);
		
		if (!isset($options['autolink-html-before'])) $options['autolink-html-before'] = '';
		if (!isset($options['autolink-linktext'])) $options['autolink-linktext'] = 'Auf die Wunschliste';
		if (!isset($options['autolink-html-after'])) $options['autolink-html-after'] = '';
		
		if (isset($options['affiliate-id']) && $options['affiliate-id'] > 0) $affiliate_parameter = 'aid='.$options['affiliate-id'].'&';
		else $affiliate_parameter = '';
		
		//this has to be generated in the loop later
		$wishlink = $options['autolink-html-before'].'<a href="http://www.wunsch-koala.de/extern/addwish/?'.$affiliate_parameter.'name='.$name.'&link='.$link.'" target="_blank">'.$options['autolink-linktext'].'</a>'.$options['autolink-html-after'];
		
		$options['autolink-domains'] = str_replace(array(' ', '.'), array('', '\.'), $options['autolink-domains']);
		$arr_domains = explode(',', $options['autolink-domains']);
		foreach ($arr_domains as $domain) {
			$content = preg_replace("@(<a[^>]*href=['\"]https?://[w\.]*".$domain."[^>]*>.*</a>)@", "$1".$wishlink, $content);
		}
		
		return $content;
	}


	public static function addLink($atts, $content) {
		$options = get_option('wunsch-koala-options');
		$output = '';
		
		if (!isset($atts['name'])) {
			$atts['name'] = get_the_title();
		}
		if (!isset($atts['link'])) {
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
			$atts['link'] = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}
		
		$name = urlencode($atts['name']);
		$link = urlencode($atts['link']);
		
		if (isset($options['affiliate-id']) && $options['affiliate-id'] > 0) $affiliate_parameter = 'aid='.$options['affiliate-id'].'&';
		else $affiliate_parameter = '';
		
		$output .= '<a href="http://www.wunsch-koala.de/extern/addwish/?'.$affiliate_parameter.'name='.$name.'&link='.$link.'" target="_blank">'.$content.'</a>';
		
		return $output;
	}






}