<?php
/**
 * WordPress Feed Convert Admin List
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 * @see     wp-feed-convert-admin-db.php
 */
class Wp_Feed_Convert_Admin_List {
	/**
	 * string $text_domain
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private $text_domain;

	/**
	 * Constructor Define.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$this->text_domain = $text_domain;

		$db = new Wp_Feed_Convert_Admin_Db();
		$mode = "";

		if ( isset( $_GET['mode'] ) && $_GET['mode'] === 'delete' ) {
			if ( isset( $_GET['feed_convert_id'] ) && is_numeric( $_GET['feed_convert_id'] ) ) {
				$db->delete_options( $_GET['feed_convert_id'] );
				$mode = "delete";
			}
		}

		$this->page_render( $db, $mode );
	}

	/**
	 * LIST Page HTML Render.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   Wp_Feed_Convert_Admin_Db $db
	 * @param   String $mode
	 */
	private function page_render ( Wp_Feed_Convert_Admin_Db $db, $mode = "" ) {
		$post_url = admin_url() . 'admin.php?page=' . $this->text_domain . '/includes/wp-feed-convert-admin-post.php';
		$item_url = admin_url() . 'admin.php?page=' . $this->text_domain . '/includes/wp-feed-convert-admin-item.php';
		$self_url = $_SERVER['PHP_SELF'] . '?' . esc_html( $_SERVER['QUERY_STRING'] );

		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . esc_html__( 'Feed Convert Settings List', $this->text_domain );
		$html .= '<a href="' . $post_url . '" class="page-title-action">' . esc_html__( 'Add New', $this->text_domain ) . '</a>';
		$html .= '</h1>';
		echo $html;

		if ( $mode === "delete" ) {
			$this->information_render();
		}

		$html  = '<hr>';
		$html .= '<table class="wp-list-table widefat fixed striped posts">';
		$html .= '<tr>';
		$html .= '<th scope="row">' . esc_html__( 'Master Data URL', $this->text_domain ) . '</th>';
		$html .= '<th scope="row">&nbsp;</th>';
		$html .= '</tr>';
		echo $html;

		/** DB table get list */
		$results = $db->get_list_options();

		if ( $results ) {
			foreach ( $results as $row ) {
				$html  = '';
				$html .= '<tr>';
				$html .= '<td><input type="text" onfocus="this.select();" readonly="readonly" value="' . esc_url( $row->master_path ) . '" class="large-text code"></td>';
				$html .= '<td>';
				$html .= '<a href="' . $post_url . '&feed_convert_id=' . esc_html( $row->id ) . '" class="feed-convert-button">';
				$html .= esc_html__( 'Master Edit', $this->text_domain );
				$html .= '</a>&nbsp;&nbsp;&nbsp;&nbsp;';
				$html .= '<a href="' . $item_url . '&feed_convert_id=' . esc_html( $row->id ) . '" class="feed-convert-button">';
				$html .= esc_html__( 'Item Edit', $this->text_domain );
				$html .= '</a>&nbsp;&nbsp;&nbsp;&nbsp;';
				$html .= '<a href="' . $self_url . '&mode=delete&feed_convert_id=' . esc_html( $row->id ) . '" class="feed-convert-button">';
				$html .= esc_html__( 'Delete', $this->text_domain );
				$html .= '</a>';
				$html .= '</td>';
				$html .= '</tr>';
				echo $html;
			}
		} else {
			echo '<td colspan="2">' . esc_html__( 'Without registration.', $this->text_domain ) . '</td>';
		}

		$html  = '</table>';
		$html .= '</div>';
		echo $html;
	}

	/**
	 * Information Message Render
	 *
	 * @since 1.0.0
	 */
	private function information_render () {
		$html  = '<div id="message" class="updated notice notice-success is-dismissible below-h2">';
		$html .= '<p>Deletion succeeds.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		echo $html;
	}
}