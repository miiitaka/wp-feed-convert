<?php
/**
 * WordPress Feed Convert Admin List
 *
 * @author  Kazuya Takami
 * @version 1.0.0
 * @since   1.0.0
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
		$this->page_render();
	}

	/**
	 * LIST Page HTML Render.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private function page_render () {

	}
}