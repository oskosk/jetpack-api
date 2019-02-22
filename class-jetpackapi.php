<?php
/**
 * Jetpack API
 *
 * @class JetpackApi
 * @package JetpackApi
 */

/**
 * An interface to everything you need from Jetpack
 */
class JetpackApi {
	/**
	 * Version of this Jetpack API interface
	 */
	const VERSION = '1.0.0';

	/**
	 * Builds a URL to the Jetpack connection auth page
	 *
	 * @param bool        $raw If true, URL will not be escaped.
	 * @param bool|string $redirect If true, will redirect back to Jetpack wp-admin landing page after connection.
	 *                              If string, will be a custom redirect.
	 * @param bool|string $from If not false, adds 'from=$from' param to the connect URL.
	 * @param bool        $register If true, will generate a register URL regardless of the existing token, since 4.9.0.
	 *
	 * @return string Connect URL
	 */
	public static function build_connect_url( $raw = false, $redirect = false, $from = false, $register = false ) {
		self::safecheck();
		return Jetpack::init()->build_connect_url( $raw, $redirect, $from, $register );
	}

	/**
	 * Disables Jetpack comments by filtering it out with `jetpack_comment_form_enabled_for_product`.
	 */
	public static function disable_jetpack_comments() {
		add_filter( 'jetpack_comment_form_enabled_for_product', '__return_false' );
	}

	/**
	 * Helps assert that the Jetpack plugin is connected
	 */
	public static function is_connected() {
		return self::is_jetpack_plugin_active() && Jetpack::is_active();
	}

	/**
	 * Catch all function to check for a given feature being available code-wise.
	 * Please, extend at will, with whatever slug you wish to come up with.
	 *
	 * @param  string $feature_slug A nickname to refer to the feature with.
	 *                              It's up to you to choose one and add it to the switch statement.
	 * @return bool   True = the feature is available
	 */
	public static function is_feature_available( $feature_slug ) {
		switch ( $feature_slug ) {
			case 'markdown':
				return class_exists( 'WPCom_Markdown' );
			case 'omnisearch':
				return class_exists( 'Jetpack_Omnisearch_Posts' );
			case 'photon':
				return self::is_module_active( 'photon' );
			default:
				return false;
		}
	}

	/**
	 * The definite way to assert that the Jetpack plugin is active
	 * on the site
	 *
	 * @return bool True = already whitelisted False = not whitelisted
	 */
	public static function is_jetpack_plugin_active() {
		return class_exists( 'Jetpack' );
	}

	/**
	 * Checks whether or not a Jetpack module is active.
	 *
	 * @param string $module The slug of a Jetpack module.
	 * @return bool
	 *
	 * @static
	 */
	public static function is_module_active( $module ) {
		return in_array( $module, Jetpack::get_active_modules(), true );
	}

	/**
	 * Checks whether the home and siteurl specifically are whitelisted
	 * Written so that we don't have re-check $key and $value params every time
	 * we want to check if this site is whitelisted, for example in footer.php
	 *
	 * @return bool True = already whitelisted False = not whitelisted
	 */
	public static function is_stating_site() {
		self::safecheck();
		return Jetpack::is_staging_site();
	}

	/**
	 * If the db version is showing a version other than what it's currently installed and active,
	 * bump it to current.
	 *
	 * @return bool: True if the option was incorrect and updated, false if nothing happened.
	 */
	public static function maybe_set_version_option() {
		self::safecheck( true );
		return Jetpack::maybe_set_version_option();
	}

	/**
	 * Throws an exception if either Jetpack plugin is not active or not is_connected to WordPress.com
	 *
	 * @param  bool $check_if_connected If true, will also check if Jetpack is connected and throw
	 *                                 an exception if it's not.
	 * @throws Exception               THrows if the checks are negative..
	 */
	public static function safecheck( $check_if_connected ) {
		if ( ! self::is_jetpack_plugin_active() ) {
			throw new Exception( 'The Jetpack plugin is not active.' );
		}
		if ( $check_if_connected && ! self::is_connected() ) {
			throw new Exception( 'Jetpack is not connected' );
		}
	}

	/**
	 * Attempts Jetpack registration.
	 * If it fails, a state flag is set: @see Jetpack::admin_page_load()
	 */
	public static function try_registration() {
		self::safecheck();
		return Jetpack::try_registration();
	}

	/**
	 * Definite way to get the installed Jetpack Version
	 *
	 * @return string The version of Jetpack installed on the site.
	 */
	public static function version() {
		return defined( 'JETPACK__VERSION' ) ? JETPACK__VERSION : 'none';
	}
}
