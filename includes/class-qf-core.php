<?php
class QF_Core {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_quillforms_post_type' ) );
	}

	/**
	 * Register Quill Forms Post Type.
	 *
	 * @since 1.0.0
	 */
	public static function register_quillforms_post_type() {
		$labels   = array(
			'name'                  => __( 'Forms', 'quillforms' ),
			'singular_name'         => __( 'Form', 'quillforms' ),
			'add_new'               => __( 'Add Form', 'quillforms' ),
			'add_new_item'          => __( 'Add Form', 'quillforms' ),
			'edit_item'             => __( 'Edit Form', 'quillforms' ),
			'new_item'              => __( 'Add Form', 'quillforms' ),
			'view_item'             => __( 'View Form', 'quillforms' ),
			'search_items'          => __( 'Search Forms', 'quillforms' ),
			'not_found'             => __( 'No forms found', 'quillforms' ),
			'not_found_in_trash'    => __( 'No forms found in trash', 'quillforms' ),
			'featured_image'        => __( 'Form Featured Image', 'quillforms' ),
			'set_featured_image'    => __( 'Set featured image', 'quillforms' ),
			'remove_featured_image' => __( 'Remove featured image', 'quillforms' ),
			'use_featured_image'    => __( 'Use as featured image', 'quillforms' ),
		);
		$supports = array(
			'title',
			'thumbnail',
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => false,
			'supports'           => $supports,
			'public'             => true,
			'show_in_menu'       => false,
			'show_ui'            => true,
			'map_meta_cap'       => true,
			'publicly_queryable' => true,
			'query_var'          => true,
			'capability_type'    => 'quillform',
			'rewrite'            => array(
				'slug'       => 'quillforms',
				'feeds'      => true,
				'with_front' => false,
			),
			'has_archive'        => true,
			'menu_position'      => 30,
			'show_in_rest'       => true,
		);
		register_post_type( 'quill_forms', $args );
		flush_rewrite_rules();
	}

	/**
	 * Get blocks for a specific form id.
	 *
	 * @param integer $form_id   Form id.
	 *
	 * @return array|null The form blocks
	 *
	 * @since 1.0.0
	 */
	public static function get_blocks( $form_id ) {
		$blocks = maybe_unserialize( get_post_meta( $form_id, 'blocks', true ) );
		$blocks = $blocks ? $blocks : array();
		return $blocks;
	}

	/**
	 * Get messages for a specific form id.
	 *
	 * @param integer $form_id   Form id.
	 *
	 * @return array|null The form messages
	 *
	 * @since 1.0.0
	 */
	public static function get_messages( $form_id ) {
		$messages = maybe_unserialize( get_post_meta( $form_id, 'messages', true ) );
		return $messages;
	}

	/**
	 * Get notifications for a specific form id.
	 *
	 * @param integer $form_id   Form id.
	 *
	 * @return array|null The form notifications
	 *
	 * @since 1.0.0
	 */
	public static function get_notifications( $form_id ) {
		$notifications = get_post_meta( $form_id, 'notifications', true );
		return $notifications;
	}

	/**
	 * Get the theme for a specific form id.
	 *
	 * @param integer $form_id   Form id.
	 *
	 * @return array|null The form theme
	 *
	 * @since 1.0.0
	 */
	public static function get_theme( $form_id ) {
		$theme_id  = get_post_meta( $form_id, 'theme', true );
		$theme_obj = QF_Form_Theme_Model::get_theme( $theme_id );
		if ( ! $theme_obj ) {
			$theme = QF_Form_Theme::get_instance()->prepare_theme_properties_for_render();
		} else {
			$theme_properties = $theme_obj['properties'];
			$theme_properties = $theme_properties ? $theme_properties : array();
			$theme            = QF_Form_Theme::get_instance()->prepare_theme_properties_for_render( $theme_properties );
		}
		return $theme;
	}
}

QF_Core::init();