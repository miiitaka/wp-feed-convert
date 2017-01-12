<?php
/**
 * WordPress Feed Convert Admin Post
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
 */
class Wp_Feed_Convert_Admin_Post {
	/**
	 * string $text_domain
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private $text_domain;

	/**
	 * array $output_format
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private $output_format = array( 'csv' => 'CSV' );

	/**
	 * Constructor Define.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   String $text_domain
	 */
	public function __construct ( $text_domain ) {
		$this->text_domain = $text_domain;

		/**
		 * Update Status
		 *
		 * "ok" : Successful update
		 */
		$status = "";

		/** DB Connect */
		$db = new Wp_Feed_Convert_Admin_Db();

		/** Set Default Parameter for Array */
		$options = array(
			"id"                    => "",
			"name"                  => "",
			"master_path"           => "",
			"output_file"           => "",
			"read_format"           => "csv",
			"write_format"          => "csv",
			'output_item_count'     => 0,
			'output_item_master'    => "",
			'output_item_extension' => "",
		);

		/** Key Set */
		if ( isset( $_GET['feed_convert_id'] ) && is_numeric( $_GET['feed_convert_id'] ) ) {
			$options['id'] = esc_html( $_GET['feed_convert_id'] );
		}

		/** DataBase Update & Insert Mode */
		if ( isset( $_POST['feed_convert_id'] ) && is_numeric( $_POST['feed_convert_id'] ) ) {
			$db->update_options( $_POST );
			$options['id'] = $_POST['feed_convert_id'];
			$status = "ok";
		} else {
			if ( isset( $_POST['feed_convert_id'] ) && $_POST['feed_convert_id'] === '' ) {
				$options['id'] = $db->insert_options( $_POST );
				$status = "ok";
			}
		}

		/** Mode Judgment */
		if ( isset( $options['id'] ) && is_numeric( $options['id'] ) ) {
			$options = $db->get_options( $options['id'] );
		}

		$this->page_render( $options, $status );
	}

	/**
	 * Post Page HTML Render.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   array  $options
	 * @param   string $status
	 */
	private function page_render ( array $options, $status ) {
		$html  = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . esc_html__( 'Feed Convert Master Settings', $this->text_domain ) . '</h1>';
		echo $html;

		switch ( $status ) {
			case "ok":
				$this->information_render();
				break;
			default:
				break;
		}

		$html  = '<hr>';
		$html .= '<form method="post" action="">';
		$html .= '<input type="hidden" name="feed_convert_id" value="'       . esc_attr( $options['id'] ) . '">';
		$html .= '<input type="hidden" name="output_item_count" value="'     . esc_attr( $options['output_item_count'] ) . '">';
		$html .= '<input type="hidden" name="output_item_master" value="'    . esc_attr( $options['output_item_master'] ) . '">';
		$html .= '<input type="hidden" name="output_item_extension" value="' . esc_attr( $options['output_item_extension'] ) . '">';
		echo $html;

		/** Common settings */
		$html  = '<table class="feed-master-table">';
		$html .= '<tr><th><label for="name">' . esc_html__( 'Name', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="text" name="name" id="name" class="regular-text" required autofocus value="';
		$html .= esc_attr( $options['name'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="master_path">' . esc_html__( 'Master File Path', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="text" name="master_path" id="master_path" class="large-text" required value="';
		$html .= esc_attr( $options['master_path'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="output_file">' . esc_html__( 'Output File Name', $this->text_domain ) . ':</label></th><td>';
		$html .= '<input type="text" name="output_file" id="output_file" class="regular-text" required value="';
		$html .= esc_attr( $options['output_file'] ) . '">';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="read_format">' . esc_html__( 'Read File Format', $this->text_domain ) . ':</label></th><td>';
		$html .= '<select name="read_format" id="read_format">';
		foreach ( $this->output_format as $key =>$value ) {
			$html .= '<option value="' . $key . '"';
			$html .= ( $options['read_format'] === $key ) ? ' selected=selected' : '';
			$html .= '>' . $value;
		}
		$html .= '</select>';
		$html .= '</td></tr>';
		$html .= '<tr><th><label for="write_format">' . esc_html__( 'Write File Format', $this->text_domain ) . ':</label></th><td>';
		$html .= '<select name="write_format" id="write_format">';
		foreach ( $this->output_format as $key =>$value ) {
			$html .= '<option value="' . $key . '"';
			$html .= ( $options['write_format'] === $key ) ? ' selected=selected' : '';
			$html .= '>' . $value;
		}
		$html .= '</select>';
		$html .= '</td></tr>';
		$html .= '</table>';
		echo $html;

		submit_button();

		$html  = '</form></div>';
		echo $html;
	}

	/**
	 * Information Message Render
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private function information_render () {
		$html  = '<div id="message" class="updated notice notice-success is-dismissible below-h2">';
		$html .= '<p>Feed Convert Information Update.</p>';
		$html .= '<button type="button" class="notice-dismiss">';
		$html .= '<span class="screen-reader-text">Dismiss this notice.</span>';
		$html .= '</button>';
		$html .= '</div>';

		echo $html;
	}
}