<?php
/**
 * WordPress Feed Convert Admin Item
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 * @see     wp-feed-convert-admin-db.php
 */
class Wp_Feed_Convert_Admin_Item {
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

		if ( isset( $_GET['feed_convert_id'] ) && is_numeric( $_GET['feed_convert_id'] ) ) {
			$db = new Wp_Feed_Convert_Admin_Db();
			$this->page_render( $db );
		}
	}

	/**
	 * LIST Page HTML Render.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   Wp_Feed_Convert_Admin_Db $db
	 */
	private function page_render ( Wp_Feed_Convert_Admin_Db $db ) {
		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . esc_html__( 'Feed Convert Settings Items', $this->text_domain ) . '</h1>';
		echo $html;

		$html  = '<hr>';
		$html .= '<table id="add-table">';
		$html .= '<tr>';
		$html .= '<th scope="row">' . esc_html__( 'Read Master Data', $this->text_domain ) . '</th>';
		$html .= '<th scope="row">' . esc_html__( 'Master Data Name', $this->text_domain ) . '</th>';
		$html .= '</tr>';
		echo $html;

		/** DB table get list */
		$results     = $db->get_list_options();
		$output_item = unserialize( $results[0]->output_item_master );
		$count       = count( $output_item );

		$html  = '';
		$html .= '<tr>';
		$html .= '<td><input type="text" id="add-data" name="add-data" value="" class="regular-text code"></td>';
		$html .= '<td><input type="text" id="add-name" name="add-name" value="" class="regular-text code"><input type="button" id="add-item" value="Add Item"></td>';
		$html .= '</tr>';
		echo $html;

		if ( $output_item ) {
			for ( $i = 0; $i < $count; $i++ ) {
				$html  = '';
				$html .= '<tr>';
				$html .= '<td><input type="text" readonly="readonly" value="' . esc_html( $output_item[$i] ) . '" class="regular-text code"></td>';
				$html .= '<td><input type="text" name="name[]" value="' . esc_html( $output_item[$i] ) . '" class="regular-text code"></td>';
				$html .= '</tr>';
				echo $html;
			}
		}
		$html  = '</table>';
		$html .= '</div>';
		echo $html;
	}
}