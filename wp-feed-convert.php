<?php
/*
Plugin Name: WordPress Feed Convert
Plugin URI: https://github.com/miiitaka/wp-feed-convert
Description: Feed converter.
Version: 1.0.0
Author: Kazuya Takami
Author URI: http://programp.com/
License: GPLv2 or later
Text Domain: wp-feed-convert
Domain Path: /languages
*/
require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-feed-convert-admin-db.php' );

new Wp_Feed_Convert();

/**
 * Basic Class
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Wp_Feed_Convert {

	/**
	 * string $text_domain
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private $text_domain = 'wp-feed-convert';

	/**
	 * Constructor Define.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function __construct () {
		register_activation_hook( __FILE__, array( $this, 'create_table' ) );
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}
	}

	/**
	 * Create table.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function create_table () {
		$db = new Wp_Feed_Convert_Admin_Db();
		$db->create_table();
	}

	/**
	 * Add Menu to the Admin Screen.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function admin_menu () {
		add_menu_page(
			esc_html__( 'Feed Convert', $this->text_domain ),
			esc_html__( 'Feed Convert', $this->text_domain ),
			'manage_options',
			plugin_basename( __FILE__ ),
			array( $this, 'list_page_render' )
		);
		add_submenu_page(
			__FILE__,
			esc_html__( 'All Settings', $this->text_domain ),
			esc_html__( 'All Settings', $this->text_domain ),
			'manage_options',
			plugin_basename( __FILE__ ),
			array( $this, 'list_page_render' )
		);
		add_submenu_page(
			__FILE__,
			esc_html__( 'Add New', $this->text_domain ),
			esc_html__( 'Add New', $this->text_domain ),
			'manage_options',
			plugin_dir_path( __FILE__ ) . 'includes/wp-feed-convert-admin-post.php',
			array( $this, 'post_page_render' )
		);
	}

	/**
	 * Admin List Page Template Require.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function list_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-feed-convert-admin-list.php' );
		new Wp_Feed_Convert_Admin_List( $this->text_domain );
	}

	/**
	 * Admin Post Page Template Require.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function post_page_render () {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-feed-convert-admin-post.php' );
		new Wp_Feed_Convert_Admin_Post( $this->text_domain );
	}
}