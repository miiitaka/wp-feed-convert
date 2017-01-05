<?php
/**
 * Admin DB Connection
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Wp_Feed_Convert_Admin_Db {

	/**
	 * Variable definition.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private $table_name;

	/**
	 * Constructor Define.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function __construct () {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'feed_convert';
	}

	/**
	 * Create Table.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function create_table () {
		global $wpdb;

		$prepared     = $wpdb->prepare( "SHOW TABLES LIKE %s", $this->table_name );
		$is_db_exists = $wpdb->get_var( $prepared );

		if ( is_null( $is_db_exists ) ) {
			$charset_collate = $wpdb->get_charset_collate();

			$query  = " CREATE TABLE " . $this->table_name;
			$query .= " (id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY";
			$query .= ",name tinytext NOT NULL";
			$query .= ",master_path text NOT NULL";
			$query .= ",output_file tinytext NOT NULL";
			$query .= ",read_format tinytext NOT NULL";
			$query .= ",write_format tinytext NOT NULL";
			$query .= ",output_item_count int";
			$query .= ",output_item_master text";
			$query .= ",output_item_extension text";
			$query .= ",register_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL";
			$query .= ",update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL";
			$query .= ",UNIQUE KEY id (id)) " . $charset_collate;

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $query );
		}
	}

	/**
	 * Get Data.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   integer $id
	 * @return  array   $args
	 */
	public function get_options ( $id ) {
		global $wpdb;

		$query    = "SELECT * FROM " . $this->table_name . " WHERE id = %d";
		$data     = array( $id );
		$prepared = $wpdb->prepare( $query, $data );

		return (array) $wpdb->get_row( $prepared );
	}

	/**
	 * Get All Data.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  array  $results
	 */
	public function get_list_options () {
		global $wpdb;

		$prepared = "SELECT * FROM " . $this->table_name . " ORDER BY update_date DESC";

		return (array) $wpdb->get_results( $prepared );
	}

	/**
	 * Insert Data.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   array $post($_POST)
	 * @return  integer $id
	 */
	public function insert_options ( array $post ) {
		global $wpdb;

		$post = $this->set_csv_items( $post );

		$data = array(
			'name'                  => strip_tags( $post['name'] ),
			'master_path'           => strip_tags( $post['master_path'] ),
			'output_file'           => strip_tags( $post['output_file'] ),
			'read_format'           => strip_tags( $post['read_format'] ),
			'write_format'          => strip_tags( $post['write_format'] ),
			'output_item_count'     => (int) $post['output_item_count'],
			'output_item_master'    => serialize( $post['output_item_master'] ),
			'output_item_extension' => serialize( array() ),
			'register_date'         => date( "Y-m-d H:i:s" ),
			'update_date'           => date( "Y-m-d H:i:s" )
		);
		$prepared = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%s',
			'%s'
		);

		$wpdb->insert( $this->table_name, $data, $prepared );
		return (int) $wpdb->insert_id;
	}

	/**
	 * Update Data.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   array $post($_POST)
	 */
	public function update_options ( array $post ) {
		global $wpdb;

		$post = $this->set_csv_items( $post );

		$data = array(
			'name'                  => strip_tags( $post['name'] ),
			'master_path'           => strip_tags( $post['master_path'] ),
			'output_file'           => strip_tags( $post['output_file'] ),
			'read_format'           => strip_tags( $post['read_format'] ),
			'write_format'          => strip_tags( $post['write_format'] ),
			'output_item_count'     => (int) $post['output_item_count'],
			'output_item_master'    => serialize( $post['output_item_master'] ),
			'output_item_extension' => serialize( $post['output_item_extension'] ),
			'update_date'           => date( "Y-m-d H:i:s" )
		);
		$key = array( 'id' => esc_html( $post['feed_convert_id'] ) );
		$prepared = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%s',
			'%s'
		);
		$key_prepared = array( '%d' );

		$wpdb->update( $this->table_name, $data, $key, $prepared, $key_prepared );
	}

	/**
	 * Delete Data.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   integer $id
	 */
	public function delete_options ( $id ) {
		global $wpdb;

		$key = array( 'id' => esc_html( $id ) );
		$key_prepared = array( '%d' );

		$wpdb->delete( $this->table_name, $key, $key_prepared );
	}

	/**
	 * Delete Data.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   array $post($_POST)
	 * @return  array $post($_POST)
	 */
	public function set_csv_items ( $post ) {
		$file = new SplFileObject( esc_url( $post['master_path'] ) );
		$file->setFlags( SplFileObject::READ_CSV );
		$file = new NoRewindIterator( $file );

		foreach( $file as $line ) {
			$post['output_item_master'] = $line;
			break;
		}
		return (array) $post;
	}
}