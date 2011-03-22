<?php
/**
 * @package Akismet Privacy Policies
 */
/*
Plugin Name: Akismet Privacy Policies
Plugin URI: http://wordpress-deutschland.org/
Description: Ergänzt das Kommentarformular um datenschutzrechtliche Hinweise bei Nutzung des Plugins Akismet.
Version: 1.0.0
Author: Inpsyde GmbH
Author URI: http://inpsyde.com/
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class Akismet_Privacy_Policies {
	
	public $notice = '<strong>Achtung:</strong> Alle Angaben sind freiwillig. 
	Ihre E-Mail-Adresse wird nicht veröffentlicht. Mit dem Absenden Ihres Kommentars 
	erklären Sie sich einverstanden dass alle eingegebenen Daten und Ihre IP-Adresse 
	zum Zweck der Spam-Vermeidung durch das Programm Akismet auf Servern in den USA 
	überprüft und gespeichert werden. Bitte lesen Sie die Datenschutzbestimmungen 
	für Akismet entweder im <a href="http://automattic.com/privacy/">Original, in 
	englischer Sprache</a>, oder in der 
	<a href="http://translate.google.com/translate?hl=de&amp;ie=UTF-8&u=http%3A%2F%2Fautomattic.com%2Fprivacy%2F&amp;sl=en&amp;tl=de" >automatischen deutschen Übersetzung</a>.';
	
	/**
	 * construct
	 * 
	 * @uses add_filter
	 * @access public
	 * @since 0.0.1
	 * @return void
	 */
	public function __construct() {
		
		add_filter( 'comment_form_defaults',    array( $this, 'add_comment_notice' ), 20, 1 );
		add_action( 'akismet_privacy_policies', array( $this, 'add_comment_notice' ) );
	}
	
	/**
	 * return content for policies inlcude markup
	 * use filter hook akismet_privacy_notice_options for change markup or notice
	 * 
	 * @access public
	 * @uses apply_filters
	 * @since 0.0.1
	 * @param array string $arr_comment_defaults
	 * @return array | string $arr_comment_defaults or 4html
	 */
	public function add_comment_notice( $arr_comment_defaults ) {
		
		$defaults = array (
			  'css_class'    => 'privacy-notice'
			, 'html_element' => 'p'
			, 'text'         => $this->notice
			, 'position'     => 'comment_notes_after'
		);
		
		// Make it filterable
		$params = apply_filters( 'akismet_privacy_notice_options', $defaults );
		
		// Create the output
		$html = '<' . $params['html_element'];
		if ( !empty( $params['css_class'] ) )
			$html .= ' class="' . $params['css_class'] . '"';
		$html .= '>' . $params['text'] . '</' . $params['html_element'] . '>';
		
		// Add the text to array
		if ( isset( $arr_comment_defaults['comment_notes_after'] ) ) {
			$arr_comment_defaults['comment_notes_after'] .= $html;
			return $arr_comment_defaults;
		} else { // for custom hook in theme
			$arr_comment_defaults = $html;
			echo $arr_comment_defaults;
		}
	}
	
} // end class

if ( function_exists( 'add_action' ) && class_exists( 'Akismet_Privacy_Policies' ) ) {
	$Akismet_Privacy_Policies = new Akismet_Privacy_Policies();
} else {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>
