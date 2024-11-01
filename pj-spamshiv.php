<?php
/*
Plugin Name: SpamShiv Lite
Plugin URI: http://www.pixeljar.net/plugins/spamshiv
Description: Jackals build automated bots and spiders to crawl through the world wide web looking for vulnerable email addresses to add to their spam lists. Generally, the victims are unprotected "mailto" links. MailTo links are incredibly useful and often necessary to our sites. So how do you protect them from being harvested and siphons by ne'er-do-wells? Commonly the bots and spiders are looking for the two tell-tale signs of an email address - the "@" and the following "." The solution lies in finding ways to mask an email addresses from harvesters, siphons and phishers, while retaining the intended functionality of the "mailto" link. There are options like using HTML entities or using javascript, and this plugin will take care of that for you.
Version: 1.1
Author: Pixel Jar
Author URI: https://www.pixeljar.com/?utm_source=user&utm_medium=wporg&utm_campaign=conversation_starter&utm_content=plugin
Text Domain: spamshiv
*/

define( 'SPAMSHIV_LITE_VERSION', '1.1' );
define( 'SPAMSHIV_LITE_ASSETS', plugin_dir_url( __FILE__ ) . 'assets/' );

if ( ! class_exists( 'SpamShivLite' ) ) {

	class SpamShivLite {

		///////////////////////////////// / / / / / / / / / / / / / /
		//
		// VARIABLE DEFINITIONS
		//
		///////////////////////////////// / / / / / / / / / / / / / /

		static $email_pattern = '/([^@<>"\':\s]+)@([-a-z0-9]+)\.+([a-z]{2,})/is';
		static $mailto_pattern = '/mailto:+([^@<>"\':\s]+)@([-a-z0-9]+)\.+([a-z]{2,})/is';

		public static function hooks() {

			// Hook into WordPress
			add_action( 'wp_head', array( __CLASS__, 'buffer_start' ) );
			add_action( 'wp_footer', array( __CLASS__, 'buffer_end' ) );

		}

		///////////////////////////////// / / / / / / / / / / / / / /
		//
		// EMAIL CONVERSIONS
		//
		///////////////////////////////// / / / / / / / / / / / / / /

		public static function email_to_entities ( $content = '' ) {

			// create an empty array to catch email addresses
			$emails = array();

			// Catch all email addresses in the entire page
			preg_match_all( self::$email_pattern, $content, $emails );

			// loop through the emails and replace them with super sneaky character entities
			for ( $i = 0; $i < count( $emails[0] ); $i++ ) {

				$content = str_replace( $emails[0][$i], antispambot( $emails[0][$i] ), $content );

			}

			// return the cleaned content
			return $content;

		}

		///////////////////////////////// / / / / / / / / / / / / / /
		//
		// BUFFERING FUNCTIONS
		//
		///////////////////////////////// / / / / / / / / / / / / / /

		public static function buff_and_wax( $buffer = '' ) {

			return self::email_to_entities( $buffer );

		}

		public static function buffer_start() {

			ob_start( array( __CLASS__, 'buff_and_wax' ) );

		}

		public static function buffer_end() {

			ob_end_flush();

		}

	}

	// Pull the string and let her rip!
	SpamShivLite::hooks();

}

if ( ! class_exists( 'SpamShivLite_Changelog' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'lib/changelog.php';
}
