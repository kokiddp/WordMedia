<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.elk-lab.com/
 * @since      0.0.1
 *
 * @package    Wordmedia
 * @subpackage Wordmedia/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wordmedia
 * @subpackage Wordmedia/admin
 * @author     Gabriele Coquillard <gabriele@elk-lab.com>
 */
class Wordmedia_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wordmedia-admin.css', array(), time()/*$this->version*/, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wordmedia-admin.js', array( 'jquery' ), time()/*$this->version*/, true );

	}

	/**
	 * Add the shortcodes for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function add_shortcodes() {


	}

	/**
	 * Register the Video custom post type.
	 *
	 * @since    0.0.1
	 * @link 	 http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function register_video_post_type() {
		$labels = array(
			'name'               => __( 'Videos', 'wordmedia' ),
			'singular_name'      => __( 'Video', 'wordmedia' ),
			'add_new'            => __( 'Add Video', 'wordmedia' ),
			'add_new_item'       => __( 'Add Video', 'wordmedia' ),
			'edit_item'          => __( 'Edit Video', 'wordmedia' ),
			'new_item'           => __( 'New Video', 'wordmedia' ),
			'view_item'          => __( 'View Video', 'wordmedia' ),
			'search_items'       => __( 'Search Video', 'wordmedia' ),
			'not_found'          => __( 'No Video found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Video in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'video', 'wordmedia' ) ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-video-alt3',
			'show_in_rest'	  => true
		);

		//filter for altering the args
		$args = apply_filters( 'video_post_type_args', $args );

		register_post_type( 'video', $args );
	}

	/**
	 * Register the metaboxes to be used for the video post type
	 *
	 * @since    0.0.1
	 */
	public function add_video_admin_meta_boxes() {

		global $current_user;
		if($current_user->roles[0] == 'administrator') {
			add_meta_box(
				'video_admin_fields',
				__( 'Admin', 'wordmedia' ),
				array( $this, 'render_video_admin_meta_boxes' ),
				'video',
				'normal',
				'high'
			);
		}
	}

    /**
	 * The HTML for the video metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_video_admin_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$video_width = ! isset( $meta['video_width'][0] ) ? '' : $meta['video_width'][0];
		$video_css = ! isset( $meta['video_css'][0] ) ? '' : htmlspecialchars( $meta['video_css'][0] );

		$video_pic_src = $video_pic != '' ? wp_get_attachment_url( $video_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'video_admin_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="video_meta_box_td" colspan="3">
					<label for="video_width" style="font-weight: bold;"><?php _e( 'Video width', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="video_width" class="regular-text" value="<?= $video_width; ?>" required> px
					<p class="description"><?php _e( 'Example: 800', 'wordmedia' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="video_meta_box_td" colspan="1">
					<label for="video_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="video_css" class="regular-text" rows="10" cols="100"><?= $video_css; ?></textarea>
				</td>
			</tr>

		</table>

		<script>
			$(document).ready(function() {

				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = $( '#image_attachment_id' ).val() != '' ? $( '#image_attachment_id' ).val() : 0;

				$('#upload_video_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Video',
						button: {
							text: 'Scegli',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == 'Video') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#video_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#video_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_video_pic').on('click', function ( event) {
					$( '#video_pic_preview' ).attr( 'src', '' );
					$( '#video_pic' ).val( '' );
				});

		});
		</script>

	<?php }

    /**
	 * Save video description metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_video_admin_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['video_admin_fields'] ) || !wp_verify_nonce( $_POST['video_admin_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['video_width'] = ( isset( $_POST['video_width'] ) ? esc_textarea( $_POST['video_width'] ) : '' );
		$meta['video_css'] = ( isset( $_POST['video_css'] ) ? htmlspecialchars( $_POST['video_css'] ) : '' );


		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the metaboxes to be used for the video post type
	 *
	 * @since    0.0.1
	 */
	public function add_video_meta_boxes() {
		add_meta_box(
			'video_fields',
			__( 'Video', 'wordmedia' ),
			array( $this, 'render_video_meta_boxes' ),
			'video',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the video metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_video_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$video_id = ! isset( $meta['video_id'][0] ) ? '' : $meta['video_id'][0];
		$video_pic = ! isset( $meta['video_pic'][0] ) ? '' : $meta['video_pic'][0];
		$video_title = ! isset( $meta['video_title'][0] ) ? '' : htmlspecialchars( $meta['video_title'][0] );
		$video_text = ! isset( $meta['video_text'][0] ) ? '' : htmlspecialchars( $meta['video_text'][0] );

		$video_pic_src = $video_pic != '' ? wp_get_attachment_url( $video_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'video_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="video_meta_box_td" colspan="3">
					<label for="video_id" style="font-weight: bold;"><?php _e( 'YouTube ID', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="video_id" class="regular-text" value="<?= $video_id; ?>" required>
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); echo ' https://www.youtube.com/watch?v=<b style="font-size: 1.2em; color: black;">KEpeKvSj-54</b>'?></p>
				</td>
			</tr>

			<tr>
				<td class="video_meta_box_td" colspan="1">
					<label for="headline_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="video_pic" id="video_pic" class="regular-text" value="<?php echo $video_pic ?>" required>
					<input id="upload_video_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_video_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="video_pic_preview" src="<?php echo $video_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="video_meta_box_td" colspan="1">
					<label for="video_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="video_title" class="regular-text" rows="5" cols="40"><?= $video_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="video_meta_box_td" colspan="1">
					<label for="video_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="video_text" class="regular-text" rows="5" cols="40"><?= $video_text; ?></textarea>
				</td>
			</tr>

		</table>

		<script>
			$(document).ready(function() {

				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = $( '#image_attachment_id' ).val() != '' ? $( '#image_attachment_id' ).val() : 0;

				$('#upload_video_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Video',
						button: {
							text: 'Scegli',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == 'Video') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#video_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#video_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_video_pic').on('click', function ( event) {
					$( '#video_pic_preview' ).attr( 'src', '' );
					$( '#video_pic' ).val( '' );
				});

		});
		</script>

	<?php }

    /**
	 * Save video description metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_video_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['video_fields'] ) || !wp_verify_nonce( $_POST['video_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['video_id'] = ( isset( $_POST['video_id'] ) ? esc_textarea( $_POST['video_id'] ) : '' );
		$meta['video_pic'] = ( isset( $_POST['video_pic'] ) ? esc_textarea( $_POST['video_pic'] ) : '' );
		$meta['video_title'] = ( isset( $_POST['video_title'] ) ? htmlspecialchars( $_POST['video_title'] ) : '' );
		$meta['video_text'] = ( isset( $_POST['video_text'] ) ? htmlspecialchars( $_POST['video_text'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the Slider custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 * @since    0.0.1
	 */
	public function register_slider_post_type() {
		$labels = array(
			'name'               => __( 'Sliders', 'wordmedia' ),
			'singular_name'      => __( 'Slider', 'wordmedia' ),
			'add_new'            => __( 'Add Slider', 'wordmedia' ),
			'add_new_item'       => __( 'Add Slider', 'wordmedia' ),
			'edit_item'          => __( 'Edit Slider', 'wordmedia' ),
			'new_item'           => __( 'New Slider', 'wordmedia' ),
			'view_item'          => __( 'View Slider', 'wordmedia' ),
			'search_items'       => __( 'Search Slider', 'wordmedia' ),
			'not_found'          => __( 'No Slider found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Slider in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'slider', 'wordmedia' ) ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-slides',
			'show_in_rest'	  => true
		);

		//filter for altering the args
		$args = apply_filters( 'slider_post_type_args', $args );

		register_post_type( 'slider', $args );
	}

	/**
	 * Register the slider design metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_admin_meta_boxes() {

		global $current_user;
		if($current_user->roles[0] == 'administrator') {
			add_meta_box(
				'slider_admin_fields',
				__( 'Design', 'wordmedia' ),
				array( $this, 'render_slider_admin_meta_boxes' ),
				'slider',
				'normal',
				'high'
			);
		}
	}

    /**
	 * The HTML for the slider design metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_admin_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slider_width = ! isset( $meta['slider_width'][0] ) ? 0 : $meta['slider_width'][0];
		$slider_height = ! isset( $meta['slider_height'][0] ) ? 0 : $meta['slider_height'][0];

		$slider_pauseonhover = ! isset( $meta['slider_pauseonhover'][0] ) ? 0 : $meta['slider_pauseonhover'][0];
		$slider_speed = ! isset( $meta['slider_speed'][0] ) ? 1200 : $meta['slider_speed'][0];

		$slider_autoplay = ! isset( $meta['slider_autoplay'][0] ) ? 0 : $meta['slider_autoplay'][0];
		$slider_autoplay_delta = ! isset( $meta['slider_autoplay_delta'][0] ) ? 5000 : $meta['slider_autoplay_delta'][0];

		$slider_swipe = ! isset( $meta['slider_swipe'][0] ) ? 0 : $meta['slider_swipe'][0];

		$slider_logo_pic = ! isset( $meta['slider_logo_pic'][0] ) ? '' : $meta['slider_logo_pic'][0];
        $slider_logo_link = ! isset( $meta['slider_logo_link'][0] ) ? '' : $meta['slider_logo_link'][0];
        $slider_logo_width = ! isset( $meta['slider_logo_width'][0] ) ? 0 : $meta['slider_logo_width'][0];
        $slider_logo_height = ! isset( $meta['slider_logo_height'][0] ) ? 0 : $meta['slider_logo_height'][0];
        $slider_logo_top = ! isset( $meta['slider_logo_top'][0] ) ? '' : $meta['slider_logo_top'][0];
        $slider_logo_right = ! isset( $meta['slider_logo_right'][0] ) ? '' : $meta['slider_logo_right'][0];
        $slider_logo_bottom = ! isset( $meta['slider_logo_bottom'][0] ) ? '' : $meta['slider_logo_bottom'][0];
        $slider_logo_left = ! isset( $meta['slider_logo_left'][0] ) ? '' : $meta['slider_logo_left'][0];

		$slider_next = ! isset( $meta['slider_next'][0] ) ? 0 : $meta['slider_next'][0];
		$slider_next_text = ! isset( $meta['slider_next_text'][0] ) ? '' : $meta['slider_next_text'][0];
		$slider_next_top = ! isset( $meta['slider_next_top'][0] ) ? '' : $meta['slider_next_top'][0];
		$slider_next_right = ! isset( $meta['slider_next_right'][0] ) ? '' : $meta['slider_next_right'][0];
		$slider_next_bottom = ! isset( $meta['slider_next_bottom'][0] ) ? '' : $meta['slider_next_bottom'][0];
		$slider_next_left = ! isset( $meta['slider_next_left'][0] ) ? '' : $meta['slider_next_left'][0];
		$slider_next_css = ! isset( $meta['slider_next_css'][0] ) ? '' : $meta['slider_next_css'][0];

		$slider_pagination = ! isset( $meta['slider_pagination'][0] ) ? 0 : $meta['slider_pagination'][0];
		$slider_pagination_top = ! isset( $meta['slider_pagination_top'][0] ) ? '' : $meta['slider_pagination_top'][0];
		$slider_pagination_right = ! isset( $meta['slider_pagination_right'][0] ) ? '' : $meta['slider_pagination_right'][0];
		$slider_pagination_bottom = ! isset( $meta['slider_pagination_bottom'][0] ) ? '' : $meta['slider_pagination_bottom'][0];
		$slider_pagination_left = ! isset( $meta['slider_pagination_left'][0] ) ? '' : $meta['slider_pagination_left'][0];
		$slider_pagination_css = ! isset( $meta['slider_pagination_css'][0] ) ? '' : $meta['slider_pagination_css'][0];
		$slider_pagination_element_css = ! isset( $meta['slider_pagination_element_css'][0] ) ? '' : $meta['slider_pagination_element_css'][0];
		$slider_pagination_active_element_css = ! isset( $meta['slider_pagination_active_element_css'][0] ) ? '' : $meta['slider_pagination_active_element_css'][0];

		$slider_title_top = ! isset( $meta['slider_title_top'][0] ) ? '' : $meta['slider_title_top'][0];
    	$slider_title_right = ! isset( $meta['slider_title_right'][0] ) ? '' : $meta['slider_title_right'][0];
    	$slider_title_bottom = ! isset( $meta['slider_title_bottom'][0] ) ? '' : $meta['slider_title_bottom'][0];
    	$slider_title_left = ! isset( $meta['slider_title_left'][0] ) ? '' : $meta['slider_title_left'][0];
    	$slider_title_css = ! isset( $meta['slider_title_css'][0] ) ? '' : $meta['slider_title_css'][0];
    	$slider_subtitle_top = ! isset( $meta['slider_subtitle_top'][0] ) ? '' : $meta['slider_subtitle_top'][0];
    	$slider_subtitle_right = ! isset( $meta['slider_subtitle_right'][0] ) ? '' : $meta['slider_subtitle_right'][0];
    	$slider_subtitle_bottom = ! isset( $meta['slider_subtitle_bottom'][0] ) ? '' : $meta['slider_subtitle_bottom'][0];
    	$slider_subtitle_left = ! isset( $meta['slider_subtitle_left'][0] ) ? '' : $meta['slider_subtitle_left'][0];
    	$slider_subtitle_css = ! isset( $meta['slider_subtitle_css'][0] ) ? '' : $meta['slider_subtitle_css'][0];

    	$slider_pic_width = ! isset( $meta['slider_pic_width'][0] ) ? 0 : esc_textarea( $meta['slider_pic_width'][0] );
		$slider_pic_height = ! isset( $meta['slider_pic_height'][0] ) ? 0 : esc_textarea( $meta['slider_pic_height'][0] );
        $slider_pic_top = ! isset( $meta['slider_pic_top'][0] ) ? '' : $meta['slider_pic_top'][0];
    	$slider_pic_right = ! isset( $meta['slider_pic_right'][0] ) ? '' : $meta['slider_pic_right'][0];
    	$slider_pic_bottom = ! isset( $meta['slider_pic_bottom'][0] ) ? '' : $meta['slider_pic_bottom'][0];
    	$slider_pic_left = ! isset( $meta['slider_pic_left'][0] ) ? '' : $meta['slider_pic_left'][0];
		$slider_pic_css = ! isset( $meta['slider_pic_css'][0] ) ? '' : htmlspecialchars( $meta['slider_pic_css'][0] );

		$slider_css = ! isset( $meta['slider_css'][0] ) ? '' : $meta['slider_css'][0];

		$slider_logo_pic_src = $slider_logo_pic != '' ? wp_get_attachment_url( $slider_logo_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_admin_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_width" style="font-weight: bold;"><?php _e( 'Width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_width" class="regular-text" value="<?php echo $slider_width ?>" required /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_height" style="font-weight: bold;"><?php _e( 'Height', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_height" class="regular-text" value="<?php echo $slider_height ?>" required /> px
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pauseonhover" style="font-weight: bold;"><?php _e( 'Pause on hover?', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="checkbox" name="slider_pauseonhover" value="1" <?php checked( 1 == $slider_pauseonhover ); ?> />
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_speed" style="font-weight: bold;"><?php _e( 'Slide transition speed', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_speed" class="regular-text" value="<?php echo $slider_speed ?>" /> ms
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_autoplay" style="font-weight: bold;"><?php _e( 'Autoplay?', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="checkbox" name="slider_autoplay" value="1" <?php checked( 1 == $slider_autoplay ); ?> />
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_autoplay_delta" style="font-weight: bold;"><?php _e( 'Autoscroll time', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_autoplay_delta" class="regular-text" value="<?php echo $slider_autoplay_delta ?>" /> ms
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_swipe" style="font-weight: bold;"><?php _e( 'Swipe?', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="checkbox" name="slider_swipe" value="1" <?php checked( 1 == $slider_swipe ); ?> />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_logo_pic" style="font-weight: bold;"><?php _e( 'Logo', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slider_logo_pic" id="slider_logo_pic" class="regular-text" value="<?php echo $slider_logo_pic ?>" required>
					<input id="upload_slider_logo_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slider_logo_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slider_logo_pic_preview" src="<?php echo $slider_logo_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_logo_link" style="font-weight: bold;"><?php _e( 'Logo link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slider_logo_link" class="regular-text" value="<?= $slider_logo_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_logo_width" style="font-weight: bold;"><?php _e( 'Logo Width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_logo_width" class="regular-text" value="<?php echo $slider_logo_width ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_logo_height" style="font-weight: bold;"><?php _e( 'Logo Height', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_logo_height" class="regular-text"value="<?php echo $slider_logo_height ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_logo_top" style="font-weight: bold;"><?php _e( 'Logo position top', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_logo_top" class="regular-text" value="<?php echo $slider_logo_top ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_logo_right" style="font-weight: bold;"><?php _e( 'Logo position right', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_logo_right" class="regular-text"value="<?php echo $slider_logo_right ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_logo_bottom" style="font-weight: bold;"><?php _e( 'Logo position bottom', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_logo_bottom" class="regular-text" value="<?php echo $slider_logo_bottom ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_logo_left" style="font-weight: bold;"><?php _e( 'Logo position left', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_logo_left" class="regular-text"value="<?php echo $slider_logo_left ?>" /> px
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_title_top" style="font-weight: bold;"><?php _e( 'Title position top', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_title_top" class="regular-text" value="<?php echo $slider_title_top ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_title_right" style="font-weight: bold;"><?php _e( 'Title position right', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_title_right" class="regular-text"value="<?php echo $slider_title_right ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_title_bottom" style="font-weight: bold;"><?php _e( 'Title position botton', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_title_bottom" class="regular-text" value="<?php echo $slider_title_bottom ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_title_left" style="font-weight: bold;"><?php _e( 'Title position left', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_title_left" class="regular-text" value="<?php echo $slider_title_left ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_title_css" style="font-weight: bold;"><?php _e( 'Title CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="slider_title_css" class="regular-text" rows="5" cols="100"><?= $slider_title_css; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_subtitle_top" style="font-weight: bold;"><?php _e( 'Subitle position top', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_subtitle_top" class="regular-text" value="<?php echo $slider_subtitle_top ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_subtitle_right" style="font-weight: bold;"><?php _e( 'Subitle position right', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_subtitle_right" class="regular-text"value="<?php echo $slider_subtitle_right ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_subtitle_bottom" style="font-weight: bold;"><?php _e( 'Subitle position bottom', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_subtitle_bottom" class="regular-text" value="<?php echo $slider_subtitle_bottom ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_subtitle_left" style="font-weight: bold;"><?php _e( 'Subitle position left', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_subtitle_left" class="regular-text"value="<?php echo $slider_subtitle_left ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_subtitle_css" style="font-weight: bold;"><?php _e( 'Subitle CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="slider_subtitle_css" class="regular-text" rows="5" cols="100"><?= $slider_subtitle_css; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pic_width" style="font-weight: bold;"><?php _e( 'Picture Width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pic_width" class="regular-text" value="<?php echo $slider_pic_width ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pic_height" style="font-weight: bold;"><?php _e( 'Picture Height', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pic_height" class="regular-text"value="<?php echo $slider_pic_height ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pic_top" style="font-weight: bold;"><?php _e( 'Picture position top', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pic_top" class="regular-text" value="<?php echo $slider_pic_top ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pic_right" style="font-weight: bold;"><?php _e( 'Picture position right', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pic_right" class="regular-text"value="<?php echo $slider_pic_right ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pic_bottom" style="font-weight: bold;"><?php _e( 'Picture position bottom', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pic_bottom" class="regular-text" value="<?php echo $slider_pic_bottom ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pic_left" style="font-weight: bold;"><?php _e( 'Picture position left', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pic_left" class="regular-text"value="<?php echo $slider_pic_left ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pic_css" style="font-weight: bold;"><?php _e( 'Picture CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="slider_pic_css" class="regular-text" rows="5" cols="100"><?= $slider_pic_css; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_next" style="font-weight: bold;"><?php _e( 'Next trigger?', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="checkbox" name="slider_next" value="1" <?php checked( 1 == $slider_next ); ?> />
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_next_text" style="font-weight: bold;"><?php _e( 'Next trigger text', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="text" name="slider_next_text" class="regular-text" value="<?php echo $slider_next_text ?>" />
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_next_top" style="font-weight: bold;"><?php _e( 'Next trigger position top', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_next_top" class="regular-text" value="<?php echo $slider_next_top ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_next_right" style="font-weight: bold;"><?php _e( 'Next trigger position right', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_next_right" class="regular-text"value="<?php echo $slider_next_right ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_next_bottom" style="font-weight: bold;"><?php _e( 'Next trigger position bottom', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_next_bottom" class="regular-text" value="<?php echo $slider_next_bottom ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_next_left" style="font-weight: bold;"><?php _e( 'Next trigger position left', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_next_left" class="regular-text"value="<?php echo $slider_next_left ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_next_css" style="font-weight: bold;"><?php _e( 'Next CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="slider_next_css" class="regular-text" rows="5" cols="100"><?= $slider_next_css; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pagination" style="font-weight: bold;"><?php _e( 'Pagination?', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="checkbox" name="slider_pagination" value="1" <?php checked( 1 == $slider_pagination ); ?> />
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pagination_top" style="font-weight: bold;"><?php _e( 'Pagination position top', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pagination_top" class="regular-text" value="<?php echo $slider_pagination_top ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pagination_right" style="font-weight: bold;"><?php _e( 'Pagination position right', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pagination_right" class="regular-text"value="<?php echo $slider_pagination_right ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pagination_bottom" style="font-weight: bold;"><?php _e( 'Pagination position bottom', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pagination_bottom" class="regular-text" value="<?php echo $slider_pagination_bottom ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pagination_left" style="font-weight: bold;"><?php _e( 'Pagination position left', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="slider_pagination_left" class="regular-text"value="<?php echo $slider_pagination_left ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pagination_css" style="font-weight: bold;"><?php _e( 'Pagination CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="slider_pagination_css" class="regular-text" rows="5" cols="100"><?= $slider_pagination_css; ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pagination_element_css" style="font-weight: bold;"><?php _e( 'Pagination element CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="slider_pagination_element_css" class="regular-text" rows="5" cols="100"><?= $slider_pagination_element_css; ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_pagination_active_element_css" style="font-weight: bold;"><?php _e( 'Pagination active element CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="slider_pagination_active_element_css" class="regular-text" rows="5" cols="100"><?= $slider_pagination_active_element_css; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slider_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="slider_css" class="regular-text" rows="10" cols="100"><?= $slider_css; ?></textarea>
				</td>
			</tr>

		</table>

		<script>
			jQuery(document).ready(function() {
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				jQuery('#upload_slider_logo_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Slider logo', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Slider logo', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							jQuery( '#slider_logo_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							jQuery( '#slider_logo_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
					file_frame.open();
				});
				jQuery('#remove_slider_logo_pic').on('click', function ( event) {
					jQuery( '#slider_logo_pic_preview' ).attr( 'src', '' );
					jQuery( '#slider_logo_pic' ).val( '' );
				});
			});			
		</script>

	<?php }

    /**
	 * Save slider design metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_admin_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_admin_fields'] ) || !wp_verify_nonce( $_POST['slider_admin_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slider_width'] = ( isset( $_POST['slider_width'] ) ? esc_textarea( $_POST['slider_width'] ) : 0 );
		$meta['slider_height'] = ( isset( $_POST['slider_height'] ) ? esc_textarea( $_POST['slider_height'] ) : 0 );

		$meta['slider_pauseonhover'] = isset( $_POST['slider_pauseonhover'] ) ? $_POST['slider_pauseonhover'] : 0;
		$meta['slider_speed'] = isset( $_POST['slider_speed'] ) ? $_POST['slider_speed'] : 1500;

		$meta['slider_autoplay'] = isset( $_POST['slider_autoplay'] ) ? $_POST['slider_autoplay'] : 0;
		$meta['slider_autoplay_delta'] = isset( $_POST['slider_autoplay_delta'] ) ? $_POST['slider_autoplay_delta'] : 5000;

		$meta['slider_swipe'] = isset( $_POST['slider_swipe'] ) ? $_POST['slider_swipe'] : 0;

		$meta['slider_logo_pic'] = ( isset( $_POST['slider_logo_pic'] ) ? esc_textarea( $_POST['slider_logo_pic'] ) : '' );
		$meta['slider_logo_link'] = ( isset( $_POST['slider_logo_link'] ) ? esc_textarea( $_POST['slider_logo_link'] ) : '' );
		$meta['slider_logo_width'] = ( isset( $_POST['slider_logo_width'] ) ? esc_textarea( $_POST['slider_logo_width'] ) : 0 );
		$meta['slider_logo_height'] = ( isset( $_POST['slider_logo_height'] ) ? esc_textarea( $_POST['slider_logo_height'] ) : 0 );
        $meta['slider_logo_top'] = isset( $_POST['slider_logo_top'] ) ? $_POST['slider_logo_top'] : '';
    	$meta['slider_logo_right'] = isset( $_POST['slider_logo_right'] ) ? $_POST['slider_logo_right'] : '';
    	$meta['slider_logo_bottom'] = isset( $_POST['slider_logo_bottom'] ) ? $_POST['slider_logo_bottom'] : '';
    	$meta['slider_logo_left'] = isset( $_POST['slider_logo_left'] ) ? $_POST['slider_logo_left'] : '';

		$meta['slider_next'] = isset( $_POST['slider_next'] ) ? $_POST['slider_next'] : 0;
		$meta['slider_next_text'] = isset( $_POST['slider_next_text'] ) ? esc_textarea( $_POST['slider_next_text'] ) : '';
		$meta['slider_next_top'] = isset( $_POST['slider_next_top'] ) ? $_POST['slider_next_top'] : 0;
		$meta['slider_next_right'] = isset( $_POST['slider_next_right'] ) ? $_POST['slider_next_right'] : 0;
		$meta['slider_next_bottom'] = isset( $_POST['slider_next_bottom'] ) ? $_POST['slider_next_bottom'] : 0;
		$meta['slider_next_left'] = isset( $_POST['slider_next_left'] ) ? $_POST['slider_next_left'] : 0;
		$meta['slider_next_css'] = isset( $_POST['slider_next_css'] ) ? htmlspecialchars( $_POST['slider_next_css'] ) : '';

		$meta['slider_pagination'] = isset( $_POST['slider_pagination'] ) ? $_POST['slider_pagination'] : 0;
		$meta['slider_pagination_top'] = isset( $_POST['slider_pagination_top'] ) ? $_POST['slider_pagination_top'] : 0;
		$meta['slider_pagination_right'] = isset( $_POST['slider_pagination_right'] ) ? $_POST['slider_pagination_right'] : 0;
		$meta['slider_pagination_bottom'] = isset( $_POST['slider_pagination_bottom'] ) ? $_POST['slider_pagination_bottom'] : 0;
		$meta['slider_pagination_left'] = isset( $_POST['slider_pagination_left'] ) ? $_POST['slider_pagination_left'] : 0;
		$meta['slider_pagination_css'] = isset( $_POST['slider_pagination_css'] ) ? htmlspecialchars( $_POST['slider_pagination_css'] ) : '';
		$meta['slider_pagination_element_css'] = isset( $_POST['slider_pagination_element_css'] ) ? htmlspecialchars( $_POST['slider_pagination_element_css'] ) : '';
		$meta['slider_pagination_active_element_css'] = isset( $_POST['slider_pagination_active_element_css'] ) ? htmlspecialchars( $_POST['slider_pagination_active_element_css'] ) : '';

    	$meta['slider_title_top'] = isset( $_POST['slider_title_top'] ) ? $_POST['slider_title_top'] : 0;
    	$meta['slider_title_right'] = isset( $_POST['slider_title_right'] ) ? $_POST['slider_title_right'] : 0;
    	$meta['slider_title_bottom'] = isset( $_POST['slider_title_bottom'] ) ? $_POST['slider_title_bottom'] : 0;
    	$meta['slider_title_left'] = isset( $_POST['slider_title_left'] ) ? $_POST['slider_title_left'] : 0;
    	$meta['slider_title_css'] = isset( $_POST['slider_title_css'] ) ? htmlspecialchars( $_POST['slider_title_css'] ) : '';
    	$meta['slider_subtitle_top'] = isset( $_POST['slider_subtitle_top'] ) ? $_POST['slider_subtitle_top'] : 0;
    	$meta['slider_subtitle_right'] = isset( $_POST['slider_subtitle_right'] ) ? $_POST['slider_subtitle_right'] : 0;
    	$meta['slider_subtitle_bottom'] = isset( $_POST['slider_subtitle_bottom'] ) ? $_POST['slider_subtitle_bottom'] : 0;
    	$meta['slider_subtitle_left'] = isset( $_POST['slider_subtitle_left'] ) ? $_POST['slider_subtitle_left'] : 0;
    	$meta['slider_subtitle_css'] = isset( $_POST['slider_subtitle_css'] ) ? htmlspecialchars( $_POST['slider_subtitle_css'] ) : '';
    	$meta['slider_text_top'] = isset( $_POST['slider_text_top'] ) ? $_POST['slider_text_top'] : 0;
    	$meta['slider_text_right'] = isset( $_POST['slider_text_right'] ) ? $_POST['slider_text_right'] : 0;
    	$meta['slider_text_bottom'] = isset( $_POST['slider_text_bottom'] ) ? $_POST['slider_text_bottom'] : 0;
    	$meta['slider_text_left'] = isset( $_POST['slider_text_left'] ) ? $_POST['slider_text_left'] : 0;
    	$meta['slider_text_css'] = isset( $_POST['slider_text_css'] ) ? htmlspecialchars( $_POST['slider_text_css'] ) : '';

        $meta['slider_pic_width'] = ( isset( $_POST['slider_pic_width'] ) ? esc_textarea( $_POST['slider_pic_width'] ) : 0 );
		$meta['slider_pic_height'] = ( isset( $_POST['slider_pic_height'] ) ? esc_textarea( $_POST['slider_pic_height'] ) : 0 );
        $meta['slider_pic_top'] = isset( $_POST['slider_pic_top'] ) ? $_POST['slider_pic_top'] : 0;
    	$meta['slider_pic_right'] = isset( $_POST['slider_pic_right'] ) ? $_POST['slider_pic_right'] : 0;
    	$meta['slider_pic_bottom'] = isset( $_POST['slider_pic_bottom'] ) ? $_POST['slider_pic_bottom'] : 0;
    	$meta['slider_pic_left'] = isset( $_POST['slider_pic_left'] ) ? $_POST['slider_pic_left'] : 0;
		$meta['slider_pic_css'] = ( isset( $_POST['slider_pic_css'] ) ? htmlspecialchars( $_POST['slider_pic_css'] ) : '' );

		$meta['slider_css'] = ( isset( $_POST['slider_css'] ) ? htmlspecialchars( $_POST['slider_css'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 1 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide1_meta_boxes() {
		add_meta_box(
			'slider_slide1_fields',
			__( 'Slide 1', 'wordmedia' ),
			array( $this, 'render_slider_slide1_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 1 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide1_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide1_title = ! isset( $meta['slide1_title'][0] ) ? '' : $meta['slide1_title'][0];
		$slide1_subtitle = ! isset( $meta['slide1_subtitle'][0] ) ? '' : $meta['slide1_subtitle'][0];
		$slide1_text = ! isset( $meta['slide1_text'][0] ) ? '' : $meta['slide1_text'][0];
		$slide1_bg = ! isset( $meta['slide1_bg'][0] ) ? '' : $meta['slide1_bg'][0];
		$slide1_pic = ! isset( $meta['slide1_pic'][0] ) ? '' : $meta['slide1_pic'][0];
		$slide1_link = ! isset( $meta['slide1_link'][0] ) ? '' : $meta['slide1_link'][0];

		$slide1_bg_src = $slide1_bg != '' ? wp_get_attachment_url( $slide1_bg ) : '';
		$slide1_pic_src = $slide1_pic != '' ? wp_get_attachment_url( $slide1_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide1_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide1_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide1_title" class="regular-text" rows="5" cols="40" required><?= $slide1_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide1_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide1_subtitle" class="regular-text" rows="5" cols="40" required><?= $slide1_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide1_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide1_text" class="regular-text" rows="5" cols="40" required><?= $slide1_text; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide1_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide1_bg" id="slide1_bg" class="regular-text" value="<?php echo $slide1_bg ?>" />
					<input id="upload_slide1_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide1_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide1_bg_preview" src="<?php echo $slide1_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide1_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide1_pic" id="slide1_pic" class="regular-text" value="<?php echo $slide1_pic ?>" required>
					<input id="upload_slide1_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide1_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide1_pic_preview" src="<?php echo $slide1_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide1_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide1_link" class="regular-text" value="<?= $slide1_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide1_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 1 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 1 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide1_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide1_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide1_bg').on('click', function ( event) {
				jQuery( '#slide1_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide1_bg' ).val( '' );
			});
			jQuery('#upload_slide1_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 1 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 1 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide1_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide1_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide1_pic').on('click', function ( event) {
				jQuery( '#slide1_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide1_pic' ).val( '' );
			});
		</script>


	<?php }

    /**
	 * Save slider 1 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide1_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide1_fields'] ) || !wp_verify_nonce( $_POST['slider_slide1_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide1_title'] = ( isset( $_POST['slide1_title'] ) ? htmlspecialchars( $_POST['slide1_title'] ) : '' );
		$meta['slide1_subtitle'] = ( isset( $_POST['slide1_subtitle'] ) ? htmlspecialchars( $_POST['slide1_subtitle'] ) : '' );
		$meta['slide1_text'] = ( isset( $_POST['slide1_text'] ) ? htmlspecialchars( $_POST['slide1_text'] ) : '' );
		$meta['slide1_pic'] = ( isset( $_POST['slide1_pic'] ) ? esc_textarea( $_POST['slide1_pic'] ) : '' );
		$meta['slide1_bg'] = ( isset( $_POST['slide1_bg'] ) ? esc_textarea( $_POST['slide1_bg'] ) : '' );
		$meta['slide1_link'] = ( isset( $_POST['slide1_link'] ) ? esc_textarea( $_POST['slide1_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 2 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide2_meta_boxes() {
		add_meta_box(
			'slider_slide2_fields',
			__( 'Slide 2', 'wordmedia' ),
			array( $this, 'render_slider_slide2_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 2 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide2_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide2_title = ! isset( $meta['slide2_title'][0] ) ? '' : $meta['slide2_title'][0];
		$slide2_subtitle = ! isset( $meta['slide2_subtitle'][0] ) ? '' : $meta['slide2_subtitle'][0];
		$slide2_text = ! isset( $meta['slide2_text'][0] ) ? '' : $meta['slide2_text'][0];
		$slide2_bg = ! isset( $meta['slide2_bg'][0] ) ? '' : $meta['slide2_bg'][0];
		$slide2_pic = ! isset( $meta['slide2_pic'][0] ) ? '' : $meta['slide2_pic'][0];
		$slide2_link = ! isset( $meta['slide2_link'][0] ) ? '' : $meta['slide2_link'][0];

		$slide2_bg_src = $slide2_bg != '' ? wp_get_attachment_url( $slide2_bg ) : '';
		$slide2_pic_src = $slide2_pic != '' ? wp_get_attachment_url( $slide2_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide2_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide2_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide2_title" class="regular-text" rows="5" cols="40"><?= $slide2_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide2_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide2_subtitle" class="regular-text" rows="5" cols="40"><?= $slide2_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide2_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide2_text" class="regular-text" rows="5" cols="40"><?= $slide2_text; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide2_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide2_bg" id="slide2_bg" class="regular-text" value="<?php echo $slide2_bg ?>" />
					<input id="upload_slide2_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide2_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide2_bg_preview" src="<?php echo $slide2_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide2_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide2_pic" id="slide2_pic" class="regular-text" value="<?php echo $slide2_pic ?>">
					<input id="upload_slide2_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide2_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide2_pic_preview" src="<?php echo $slide2_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide2_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide2_link" class="regular-text" value="<?= $slide2_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide2_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 2 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 2 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide2_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide2_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide2_bg').on('click', function ( event) {
				jQuery( '#slide2_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide2_bg' ).val( '' );
			});
			jQuery('#upload_slide2_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 2 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 2 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide2_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide2_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide2_pic').on('click', function ( event) {
				jQuery( '#slide2_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide2_pic' ).val( '' );
			});
		</script>

	<?php }

    /**
	 * Save slider 2 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide2_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide2_fields'] ) || !wp_verify_nonce( $_POST['slider_slide2_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide2_title'] = ( isset( $_POST['slide2_title'] ) ? esc_textarea( $_POST['slide2_title'] ) : '' );
		$meta['slide2_subtitle'] = ( isset( $_POST['slide2_subtitle'] ) ? esc_textarea( $_POST['slide2_subtitle'] ) : '' );
		$meta['slide2_text'] = ( isset( $_POST['slide2_text'] ) ? esc_textarea( $_POST['slide2_text'] ) : '' );
		$meta['slide2_bg'] = ( isset( $_POST['slide2_bg'] ) ? esc_textarea( $_POST['slide2_bg'] ) : '' );
		$meta['slide2_pic'] = ( isset( $_POST['slide2_pic'] ) ? esc_textarea( $_POST['slide2_pic'] ) : '' );
		$meta['slide2_link'] = ( isset( $_POST['slide2_link'] ) ? esc_textarea( $_POST['slide2_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 3 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide3_meta_boxes() {
		add_meta_box(
			'slider_slide3_fields',
			__( 'Slide 3', 'wordmedia' ),
			array( $this, 'render_slider_slide3_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 3 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide3_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide3_title = ! isset( $meta['slide3_title'][0] ) ? '' : $meta['slide3_title'][0];
		$slide3_subtitle = ! isset( $meta['slide3_subtitle'][0] ) ? '' : $meta['slide3_subtitle'][0];
		$slide3_text = ! isset( $meta['slide3_text'][0] ) ? '' : $meta['slide3_text'][0];
		$slide3_bg = ! isset( $meta['slide3_bg'][0] ) ? '' : $meta['slide3_bg'][0];
		$slide3_pic = ! isset( $meta['slide3_pic'][0] ) ? '' : $meta['slide3_pic'][0];
		$slide3_link = ! isset( $meta['slide3_link'][0] ) ? '' : $meta['slide3_link'][0];

		$slide3_bg_src = $slide3_bg != '' ? wp_get_attachment_url( $slide3_bg ) : '';
		$slide3_pic_src = $slide3_pic != '' ? wp_get_attachment_url( $slide3_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide3_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide3_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide3_title" class="regular-text" rows="5" cols="40"><?= $slide3_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide3_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide3_subtitle" class="regular-text" rows="5" cols="40"><?= $slide3_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide3_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide3_subtitle" class="regular-text" rows="5" cols="40"><?= $slide3_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide3_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide3_bg" id="slide3_bg" class="regular-text" value="<?php echo $slide3_bg ?>" />
					<input id="upload_slide3_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide3_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide3_bg_preview" src="<?php echo $slide3_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide3_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide3_pic" id="slide3_pic" class="regular-text" value="<?php echo $slide3_pic ?>">
					<input id="upload_slide3_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide3_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide3_pic_preview" src="<?php echo $slide3_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide3_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide3_link" class="regular-text" value="<?= $slide3_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide3_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 3 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 3 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide3_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide3_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide3_bg').on('click', function ( event) {
				jQuery( '#slide3_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide3_bg' ).val( '' );
			});
			jQuery('#upload_slide3_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 3 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 3 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide3_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide3_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide3_pic').on('click', function ( event) {
				jQuery( '#slide3_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide3_pic' ).val( '' );
			});
		</script>

	<?php }

    /**
	 * Save slider 3 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide3_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide3_fields'] ) || !wp_verify_nonce( $_POST['slider_slide3_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide3_title'] = ( isset( $_POST['slide3_title'] ) ? esc_textarea( $_POST['slide3_title'] ) : '' );
		$meta['slide3_subtitle'] = ( isset( $_POST['slide3_subtitle'] ) ? esc_textarea( $_POST['slide3_subtitle'] ) : '' );
		$meta['slide3_text'] = ( isset( $_POST['slide3_text'] ) ? esc_textarea( $_POST['slide3_text'] ) : '' );
		$meta['slide3_bg'] = ( isset( $_POST['slide3_bg'] ) ? esc_textarea( $_POST['slide3_bg'] ) : '' );
		$meta['slide3_pic'] = ( isset( $_POST['slide3_pic'] ) ? esc_textarea( $_POST['slide3_pic'] ) : '' );
		$meta['slide3_link'] = ( isset( $_POST['slide3_link'] ) ? esc_textarea( $_POST['slide3_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 4 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide4_meta_boxes() {
		add_meta_box(
			'slider_slide4_fields',
			__( 'Slide 4', 'wordmedia' ),
			array( $this, 'render_slider_slide4_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 4 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide4_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide4_title = ! isset( $meta['slide4_title'][0] ) ? '' : $meta['slide4_title'][0];
		$slide4_subtitle = ! isset( $meta['slide4_subtitle'][0] ) ? '' : $meta['slide4_subtitle'][0];
		$slide4_text = ! isset( $meta['slide4_text'][0] ) ? '' : $meta['slide4_text'][0];
		$slide4_bg = ! isset( $meta['slide4_bg'][0] ) ? '' : $meta['slide4_bg'][0];
		$slide4_pic = ! isset( $meta['slide4_pic'][0] ) ? '' : $meta['slide4_pic'][0];
		$slide4_link = ! isset( $meta['slide4_link'][0] ) ? '' : $meta['slide4_link'][0];

		$slide4_bg_src = $slide4_bg != '' ? wp_get_attachment_url( $slide4_bg ) : '';
		$slide4_pic_src = $slide4_pic != '' ? wp_get_attachment_url( $slide4_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide4_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide4_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide4_title" class="regular-text" rows="5" cols="40"><?= $slide4_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide4_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide4_subtitle" class="regular-text" rows="5" cols="40"><?= $slide4_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide4_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide4_text" class="regular-text" rows="5" cols="40"><?= $slide4_text; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide4_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide4_bg" id="slide4_bg" class="regular-text" value="<?php echo $slide4_bg ?>" />
					<input id="upload_slide4_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide4_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide4_bg_preview" src="<?php echo $slide4_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide4_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide4_pic" id="slide4_pic" class="regular-text" value="<?php echo $slide4_pic ?>">
					<input id="upload_slide4_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide4_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide4_pic_preview" src="<?php echo $slide4_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide4_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide4_link" class="regular-text" value="<?= $slide4_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide4_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 4 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 4 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide4_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide4_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide4_bg').on('click', function ( event) {
				jQuery( '#slide4_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide4_bg' ).val( '' );
			});
			jQuery('#upload_slide4_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 4 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 4 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide4_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide4_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide4_pic').on('click', function ( event) {
				jQuery( '#slide4_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide4_pic' ).val( '' );
			});
		</script>

	<?php }

    /**
	 * Save slider 4 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide4_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide4_fields'] ) || !wp_verify_nonce( $_POST['slider_slide4_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide4_title'] = ( isset( $_POST['slide4_title'] ) ? esc_textarea( $_POST['slide4_title'] ) : '' );
		$meta['slide4_subtitle'] = ( isset( $_POST['slide4_subtitle'] ) ? esc_textarea( $_POST['slide4_subtitle'] ) : '' );
		$meta['slide4_text'] = ( isset( $_POST['slide4_text'] ) ? esc_textarea( $_POST['slide4_text'] ) : '' );
		$meta['slide4_bg'] = ( isset( $_POST['slide4_bg'] ) ? esc_textarea( $_POST['slide4_bg'] ) : '' );
		$meta['slide4_pic'] = ( isset( $_POST['slide4_pic'] ) ? esc_textarea( $_POST['slide4_pic'] ) : '' );
		$meta['slide4_link'] = ( isset( $_POST['slide4_link'] ) ? esc_textarea( $_POST['slide4_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 5 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide5_meta_boxes() {
		add_meta_box(
			'slider_slide5_fields',
			__( 'Slide 5', 'wordmedia' ),
			array( $this, 'render_slider_slide5_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 5 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide5_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide5_title = ! isset( $meta['slide5_title'][0] ) ? '' : $meta['slide5_title'][0];
		$slide5_subtitle = ! isset( $meta['slide5_subtitle'][0] ) ? '' : $meta['slide5_subtitle'][0];
		$slide5_text = ! isset( $meta['slide5_text'][0] ) ? '' : $meta['slide5_text'][0];
		$slide5_bg = ! isset( $meta['slide5_bg'][0] ) ? '' : $meta['slide5_bg'][0];
		$slide5_pic = ! isset( $meta['slide5_pic'][0] ) ? '' : $meta['slide5_pic'][0];
		$slide5_link = ! isset( $meta['slide5_link'][0] ) ? '' : $meta['slide5_link'][0];

		$slide5_bg_src = $slide5_bg != '' ? wp_get_attachment_url( $slide5_bg ) : '';
		$slide5_pic_src = $slide5_pic != '' ? wp_get_attachment_url( $slide5_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide5_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide5_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide5_title" class="regular-text" rows="5" cols="40"><?= $slide5_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide5_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide5_subtitle" class="regular-text" rows="5" cols="40"><?= $slide5_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide5_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide5_text" class="regular-text" rows="5" cols="40"><?= $slide5_text; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide5_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide5_bg" id="slide5_bg" class="regular-text" value="<?php echo $slide5_bg ?>" />
					<input id="upload_slide5_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide5_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide5_bg_preview" src="<?php echo $slide5_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide5_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide5_pic" id="slide5_pic" class="regular-text" value="<?php echo $slide5_pic ?>">
					<input id="upload_slide5_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide5_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide5_pic_preview" src="<?php echo $slide5_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide5_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide5_link" class="regular-text" value="<?= $slide5_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide5_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 5 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 5 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide5_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide5_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide5_bg').on('click', function ( event) {
				jQuery( '#slide5_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide5_bg' ).val( '' );
			});
			jQuery('#upload_slide5_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 5 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 5 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide5_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide5_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide5_pic').on('click', function ( event) {
				jQuery( '#slide5_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide5_pic' ).val( '' );
			});
		</script>

	<?php }

    /**
	 * Save slider 5 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide5_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide5_fields'] ) || !wp_verify_nonce( $_POST['slider_slide5_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide5_title'] = ( isset( $_POST['slide5_title'] ) ? esc_textarea( $_POST['slide5_title'] ) : '' );
		$meta['slide5_subtitle'] = ( isset( $_POST['slide5_subtitle'] ) ? esc_textarea( $_POST['slide5_subtitle'] ) : '' );
		$meta['slide5_text'] = ( isset( $_POST['slide5_text'] ) ? esc_textarea( $_POST['slide5_text'] ) : '' );
		$meta['slide5_bg'] = ( isset( $_POST['slide5_bg'] ) ? esc_textarea( $_POST['slide5_bg'] ) : '' );
		$meta['slide5_pic'] = ( isset( $_POST['slide5_pic'] ) ? esc_textarea( $_POST['slide5_pic'] ) : '' );
		$meta['slide5_link'] = ( isset( $_POST['slide5_link'] ) ? esc_textarea( $_POST['slide5_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 6 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide6_meta_boxes() {
		add_meta_box(
			'slider_slide6_fields',
			__( 'Slide 6', 'wordmedia' ),
			array( $this, 'render_slider_slide6_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 6 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide6_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide6_title = ! isset( $meta['slide6_title'][0] ) ? '' : $meta['slide6_title'][0];
		$slide6_subtitle = ! isset( $meta['slide6_subtitle'][0] ) ? '' : $meta['slide6_subtitle'][0];
		$slide6_text = ! isset( $meta['slide6_text'][0] ) ? '' : $meta['slide6_text'][0];
		$slide6_bg = ! isset( $meta['slide6_bg'][0] ) ? '' : $meta['slide6_bg'][0];
		$slide6_pic = ! isset( $meta['slide6_pic'][0] ) ? '' : $meta['slide6_pic'][0];
		$slide6_link = ! isset( $meta['slide6_link'][0] ) ? '' : $meta['slide6_link'][0];

		$slide6_bg_src = $slide6_bg != '' ? wp_get_attachment_url( $slide6_bg ) : '';
		$slide6_pic_src = $slide6_pic != '' ? wp_get_attachment_url( $slide6_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide6_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide6_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide6_title" class="regular-text" rows="5" cols="40"><?= $slide6_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide6_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide6_subtitle" class="regular-text" rows="5" cols="40"><?= $slide6_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide6_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide6_text" class="regular-text" rows="5" cols="40"><?= $slide6_text; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide6_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide6_bg" id="slide6_bg" class="regular-text" value="<?php echo $slide6_bg ?>" />
					<input id="upload_slide6_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide6_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide6_bg_preview" src="<?php echo $slide6_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide6_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide6_pic" id="slide6_pic" class="regular-text" value="<?php echo $slide6_pic ?>">
					<input id="upload_slide6_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide6_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide6_pic_preview" src="<?php echo $slide6_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide6_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide6_link" class="regular-text" value="<?= $slide6_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide6_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 6 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 6 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide6_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide6_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide6_bg').on('click', function ( event) {
				jQuery( '#slide6_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide6_bg' ).val( '' );
			});
			jQuery('#upload_slide6_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 6 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 6 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide6_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide6_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide6_pic').on('click', function ( event) {
				jQuery( '#slide6_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide6_pic' ).val( '' );
			});
		</script>

	<?php }

    /**
	 * Save slider 6 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide6_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide6_fields'] ) || !wp_verify_nonce( $_POST['slider_slide6_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide6_title'] = ( isset( $_POST['slide6_title'] ) ? esc_textarea( $_POST['slide6_title'] ) : '' );
		$meta['slide6_subtitle'] = ( isset( $_POST['slide6_subtitle'] ) ? esc_textarea( $_POST['slide6_subtitle'] ) : '' );
		$meta['slide6_text'] = ( isset( $_POST['slide6_text'] ) ? esc_textarea( $_POST['slide6_text'] ) : '' );
		$meta['slide6_bg'] = ( isset( $_POST['slide6_bg'] ) ? esc_textarea( $_POST['slide6_bg'] ) : '' );
		$meta['slide6_pic'] = ( isset( $_POST['slide6_pic'] ) ? esc_textarea( $_POST['slide6_pic'] ) : '' );
		$meta['slide6_link'] = ( isset( $_POST['slide6_link'] ) ? esc_textarea( $_POST['slide6_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 7 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide7_meta_boxes() {
		add_meta_box(
			'slider_slide7_fields',
			__( 'Slide 7', 'wordmedia' ),
			array( $this, 'render_slider_slide7_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 7 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide7_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide7_title = ! isset( $meta['slide7_title'][0] ) ? '' : $meta['slide7_title'][0];
		$slide7_subtitle = ! isset( $meta['slide7_subtitle'][0] ) ? '' : $meta['slide7_subtitle'][0];
		$slide7_text = ! isset( $meta['slide7_text'][0] ) ? '' : $meta['slide7_text'][0];
		$slide7_bg = ! isset( $meta['slide7_bg'][0] ) ? '' : $meta['slide7_bg'][0];
		$slide7_pic = ! isset( $meta['slide7_pic'][0] ) ? '' : $meta['slide7_pic'][0];
		$slide7_link = ! isset( $meta['slide7_link'][0] ) ? '' : $meta['slide7_link'][0];

		$slide7_bg_src = $slide7_bg != '' ? wp_get_attachment_url( $slide7_bg ) : '';
		$slide7_pic_src = $slide7_pic != '' ? wp_get_attachment_url( $slide7_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide7_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide7_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide7_title" class="regular-text" rows="5" cols="40"><?= $slide7_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide7_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide7_subtitle" class="regular-text" rows="5" cols="40"><?= $slide7_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide7_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide7_text" class="regular-text" rows="5" cols="40"><?= $slide7_text; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide7_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide7_bg" id="slide7_bg" class="regular-text" value="<?php echo $slide7_bg ?>" />
					<input id="upload_slide7_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide7_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide7_bg_preview" src="<?php echo $slide7_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide7_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide7_pic" id="slide7_pic" class="regular-text" value="<?php echo $slide7_pic ?>">
					<input id="upload_slide7_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide7_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide7_pic_preview" src="<?php echo $slide7_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide7_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide7_link" class="regular-text" value="<?= $slide7_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide7_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 7 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 7 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide7_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide7_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide7_bg').on('click', function ( event) {
				jQuery( '#slide7_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide7_bg' ).val( '' );
			});
			jQuery('#upload_slide7_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 7 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 7 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide7_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide7_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide7_pic').on('click', function ( event) {
				jQuery( '#slide7_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide7_pic' ).val( '' );
			});
		</script>

	<?php }

    /**
	 * Save slider 7 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide7_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide7_fields'] ) || !wp_verify_nonce( $_POST['slider_slide7_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide7_title'] = ( isset( $_POST['slide7_title'] ) ? esc_textarea( $_POST['slide7_title'] ) : '' );
		$meta['slide7_subtitle'] = ( isset( $_POST['slide7_subtitle'] ) ? esc_textarea( $_POST['slide7_subtitle'] ) : '' );
		$meta['slide7_text'] = ( isset( $_POST['slide7_text'] ) ? esc_textarea( $_POST['slide7_text'] ) : '' );
		$meta['slide7_bg'] = ( isset( $_POST['slide7_bg'] ) ? esc_textarea( $_POST['slide7_bg'] ) : '' );
		$meta['slide7_pic'] = ( isset( $_POST['slide7_pic'] ) ? esc_textarea( $_POST['slide7_pic'] ) : '' );
		$meta['slide7_link'] = ( isset( $_POST['slide7_link'] ) ? esc_textarea( $_POST['slide7_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 8 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide8_meta_boxes() {
		add_meta_box(
			'slider_slide8_fields',
			__( 'Slide 8', 'wordmedia' ),
			array( $this, 'render_slider_slide8_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 8 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide8_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide8_title = ! isset( $meta['slide8_title'][0] ) ? '' : $meta['slide8_title'][0];
		$slide8_subtitle = ! isset( $meta['slide8_subtitle'][0] ) ? '' : $meta['slide8_subtitle'][0];
		$slide8_text = ! isset( $meta['slide8_text'][0] ) ? '' : $meta['slide8_text'][0];
		$slide8_bg = ! isset( $meta['slide8_bg'][0] ) ? '' : $meta['slide8_bg'][0];
		$slide8_pic = ! isset( $meta['slide8_pic'][0] ) ? '' : $meta['slide8_pic'][0];
		$slide8_link = ! isset( $meta['slide8_link'][0] ) ? '' : $meta['slide8_link'][0];

		$slide8_bg_src = $slide8_bg != '' ? wp_get_attachment_url( $slide8_bg ) : '';
		$slide8_pic_src = $slide8_pic != '' ? wp_get_attachment_url( $slide8_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide8_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide8_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide8_title" class="regular-text" rows="5" cols="40"><?= $slide8_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide8_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide8_subtitle" class="regular-text" rows="5" cols="40"><?= $slide8_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide8_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide8_text" class="regular-text" rows="5" cols="40"><?= $slide8_text; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide8_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide8_bg" id="slide8_bg" class="regular-text" value="<?php echo $slide8_bg ?>" />
					<input id="upload_slide8_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide8_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide8_bg_preview" src="<?php echo $slide8_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide8_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide8_pic" id="slide8_pic" class="regular-text" value="<?php echo $slide8_pic ?>">
					<input id="upload_slide8_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide8_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide8_pic_preview" src="<?php echo $slide8_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide8_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide8_link" class="regular-text" value="<?= $slide8_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide8_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 8 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 8 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide8_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide8_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide8_bg').on('click', function ( event) {
				jQuery( '#slide8_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide8_bg' ).val( '' );
			});
			jQuery('#upload_slide8_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 8 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 8 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide8_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide8_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide8_pic').on('click', function ( event) {
				jQuery( '#slide8_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide8_pic' ).val( '' );
			});
		</script>

	<?php }

    /**
	 * Save slider 8 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide8_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide8_fields'] ) || !wp_verify_nonce( $_POST['slider_slide8_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide8_title'] = ( isset( $_POST['slide8_title'] ) ? esc_textarea( $_POST['slide8_title'] ) : '' );
		$meta['slide8_subtitle'] = ( isset( $_POST['slide8_subtitle'] ) ? esc_textarea( $_POST['slide8_subtitle'] ) : '' );
		$meta['slide8_text'] = ( isset( $_POST['slide8_text'] ) ? esc_textarea( $_POST['slide8_text'] ) : '' );
		$meta['slide8_bg'] = ( isset( $_POST['slide8_bg'] ) ? esc_textarea( $_POST['slide8_bg'] ) : '' );
		$meta['slide8_pic'] = ( isset( $_POST['slide8_pic'] ) ? esc_textarea( $_POST['slide8_pic'] ) : '' );
		$meta['slide8_link'] = ( isset( $_POST['slide8_link'] ) ? esc_textarea( $_POST['slide8_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 9 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide9_meta_boxes() {
		add_meta_box(
			'slider_slide9_fields',
			__( 'Slide 9', 'wordmedia' ),
			array( $this, 'render_slider_slide9_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 9 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide9_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide9_title = ! isset( $meta['slide9_title'][0] ) ? '' : $meta['slide9_title'][0];
		$slide9_subtitle = ! isset( $meta['slide9_subtitle'][0] ) ? '' : $meta['slide9_subtitle'][0];
		$slide9_text = ! isset( $meta['slide9_text'][0] ) ? '' : $meta['slide9_text'][0];
		$slide9_bg = ! isset( $meta['slide9_bg'][0] ) ? '' : $meta['slide9_bg'][0];
		$slide9_pic = ! isset( $meta['slide9_pic'][0] ) ? '' : $meta['slide9_pic'][0];
		$slide9_link = ! isset( $meta['slide9_link'][0] ) ? '' : $meta['slide9_link'][0];

		$slide9_bg_src = $slide9_bg != '' ? wp_get_attachment_url( $slide9_bg ) : '';
		$slide9_pic_src = $slide9_pic != '' ? wp_get_attachment_url( $slide9_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide9_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide9_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide9_title" class="regular-text" rows="5" cols="40"><?= $slide9_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide9_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide9_subtitle" class="regular-text" rows="5" cols="40"><?= $slide9_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide9_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide9_text" class="regular-text" rows="5" cols="40"><?= $slide9_text; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide9_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide9_bg" id="slide9_bg" class="regular-text" value="<?php echo $slide9_bg ?>" />
					<input id="upload_slide9_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide9_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide9_bg_preview" src="<?php echo $slide9_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide9_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide9_pic" id="slide9_pic" class="regular-text" value="<?php echo $slide9_pic ?>">
					<input id="upload_slide9_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide9_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide9_pic_preview" src="<?php echo $slide9_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide9_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide9_link" class="regular-text" value="<?= $slide9_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide9_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 9 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 9 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide9_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide9_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide9_bg').on('click', function ( event) {
				jQuery( '#slide9_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide9_bg' ).val( '' );
			});
			jQuery('#upload_slide9_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 9 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 9 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide9_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide9_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide9_pic').on('click', function ( event) {
				jQuery( '#slide9_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide9_pic' ).val( '' );
			});
		</script>

	<?php }

    /**
	 * Save slider 9 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide9_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide9_fields'] ) || !wp_verify_nonce( $_POST['slider_slide9_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide9_title'] = ( isset( $_POST['slide9_title'] ) ? esc_textarea( $_POST['slide9_title'] ) : '' );
		$meta['slide9_subtitle'] = ( isset( $_POST['slide9_subtitle'] ) ? esc_textarea( $_POST['slide9_subtitle'] ) : '' );
		$meta['slide9_text'] = ( isset( $_POST['slide9_text'] ) ? esc_textarea( $_POST['slide9_text'] ) : '' );
		$meta['slide9_bg'] = ( isset( $_POST['slide9_bg'] ) ? esc_textarea( $_POST['slide9_bg'] ) : '' );
		$meta['slide9_pic'] = ( isset( $_POST['slide9_pic'] ) ? esc_textarea( $_POST['slide9_pic'] ) : '' );
		$meta['slide9_link'] = ( isset( $_POST['slide9_link'] ) ? esc_textarea( $_POST['slide9_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the slider 10 metaboxes to be used for the Slider post type
	 *
	 * @since    0.0.1
	 */
	public function add_slider_slide10_meta_boxes() {
		add_meta_box(
			'slider_slide10_fields',
			__( 'Slide 10', 'wordmedia' ),
			array( $this, 'render_slider_slide10_meta_boxes' ),
			'slider',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the slider 10 metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_slider_slide10_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$slide10_title = ! isset( $meta['slide10_title'][0] ) ? '' : $meta['slide10_title'][0];
		$slide10_subtitle = ! isset( $meta['slide10_subtitle'][0] ) ? '' : $meta['slide10_subtitle'][0];
		$slide10_text = ! isset( $meta['slide10_text'][0] ) ? '' : $meta['slide10_text'][0];
		$slide10_bg = ! isset( $meta['slide10_bg'][0] ) ? '' : $meta['slide10_bg'][0];
		$slide10_pic = ! isset( $meta['slide10_pic'][0] ) ? '' : $meta['slide10_pic'][0];
		$slide10_link = ! isset( $meta['slide10_link'][0] ) ? '' : $meta['slide10_link'][0];

		$slide10_bg_src = $slide10_bg != '' ? wp_get_attachment_url( $slide10_bg ) : '';
		$slide10_pic_src = $slide10_pic != '' ? wp_get_attachment_url( $slide10_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'slider_slide10_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide10_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide10_title" class="regular-text" rows="5" cols="40"><?= $slide10_title; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide10_subtitle" style="font-weight: bold;"><?php _e( 'Subtitle', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide10_subtitle" class="regular-text" rows="5" cols="40"><?= $slide10_subtitle; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide10_text" style="font-weight: bold;"><?php _e( 'Text', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="slide10_text" class="regular-text" rows="5" cols="40"><?= $slide10_text; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide10_bg" style="font-weight: bold;"><?php _e( 'Background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide10_bg" id="slide10_bg" class="regular-text" value="<?php echo $slide10_bg ?>" />
					<input id="upload_slide10_bg" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide10_bg" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide10_bg_preview" src="<?php echo $slide10_bg_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide10_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="slide10_pic" id="slide10_pic" class="regular-text" value="<?php echo $slide10_pic ?>">
					<input id="upload_slide10_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_slide10_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="slide10_pic_preview" src="<?php echo $slide10_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="slider_meta_box_td" colspan="1">
					<label for="slide10_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="slide10_link" class="regular-text" value="<?= $slide10_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

		<script>
			jQuery('#upload_slide10_bg').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 10 Background', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 10 Background', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide10_bg_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide10_bg' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide10_bg').on('click', function ( event) {
				jQuery( '#slide10_bg_preview' ).attr( 'src', '' );
				jQuery( '#slide10_bg' ).val( '' );
			});
			jQuery('#upload_slide10_pic').on('click', function( event ){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = jQuery( '#image_attachment_id' ).val() != '' ? jQuery( '#image_attachment_id' ).val() : 0;

				event.preventDefault();
				wp.media.model.settings.post.id = set_to_post_id;
				file_frame = wp.media.frames.file_frame = wp.media({
					title: '<?php _e( 'Slide 10 Picture', 'wordmedia' ); ?>',
					button: {
						text: '<?php _e( 'Select', 'wordmedia' ); ?>',
					},
					multiple: false
				});
				file_frame.on( 'select', function() {
					if (file_frame.options.title == '<?php _e( 'Slide 10 Picture', 'wordmedia' ); ?>') {
						var attachment = file_frame.state().get('selection').first().toJSON();
						jQuery( '#slide10_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						jQuery( '#slide10_pic' ).val( attachment.id );
						wp.media.model.settings.post.id = wp_media_post_id;
					}
				});
					file_frame.open();
			});
			jQuery('#remove_slide10_pic').on('click', function ( event) {
				jQuery( '#slide10_pic_preview' ).attr( 'src', '' );
				jQuery( '#slide10_pic' ).val( '' );
			});
		</script>

	<?php }

    /**
	 * Save slider 10 metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_slider_slide10_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['slider_slide10_fields'] ) || !wp_verify_nonce( $_POST['slider_slide10_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['slide10_title'] = ( isset( $_POST['slide10_title'] ) ? esc_textarea( $_POST['slide10_title'] ) : '' );
		$meta['slide10_subtitle'] = ( isset( $_POST['slide10_subtitle'] ) ? esc_textarea( $_POST['slide10_subtitle'] ) : '' );
		$meta['slide10_text'] = ( isset( $_POST['slide10_text'] ) ? esc_textarea( $_POST['slide10_text'] ) : '' );
		$meta['slide10_bg'] = ( isset( $_POST['slide10_bg'] ) ? esc_textarea( $_POST['slide10_bg'] ) : '' );
		$meta['slide10_pic'] = ( isset( $_POST['slide10_pic'] ) ? esc_textarea( $_POST['slide10_pic'] ) : '' );
		$meta['slide10_link'] = ( isset( $_POST['slide10_link'] ) ? esc_textarea( $_POST['slide10_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the PowerGallery custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function register_powergallery_post_type() {
		$labels = array(
			'name'               => __( 'PowerGalleries', 'wordmedia' ),
			'singular_name'      => __( 'PowerGallery', 'wordmedia' ),
			'add_new'            => __( 'Add PowerGallery', 'wordmedia' ),
			'add_new_item'       => __( 'Add PowerGallery', 'wordmedia' ),
			'edit_item'          => __( 'Edit PowerGallery', 'wordmedia' ),
			'new_item'           => __( 'New PowerGallery', 'wordmedia' ),
			'view_item'          => __( 'View PowerGallery', 'wordmedia' ),
			'search_items'       => __( 'Search PowerGallery', 'wordmedia' ),
			'not_found'          => __( 'No PowerGallery found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No PowerGallery in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'powergallery', 'wordmedia' ) ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-images-alt2',
			'show_in_rest'	  => true
		);

		//filter for altering the args
		$args = apply_filters( 'powergallery_post_type_args', $args );

		register_post_type( 'powergallery', $args );
	}

	/**
	 * Register the design metaboxes to be used for the PowerGallery post type
	 *
	 * @since    0.0.1
	 */
	public function add_powergallery_admin_meta_boxes() {

		global $current_user;
		if($current_user->roles[0] == 'administrator') {
			add_meta_box(
				'powergallery_admin_fields',
				__( 'Design', 'wordmedia' ),
				array( $this, 'render_powergallery_admin_meta_boxes' ),
				'powergallery',
				'normal',
				'high'
			);
		}
	}

    /**
	 * The HTML for the PowerGallery design metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_powergallery_admin_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		
		$powergallery_type = ! isset( $meta['powergallery_type'][0] ) ? 0 : $meta['powergallery_type'][0];

		$powergallery_width = ! isset( $meta['powergallery_width'][0] ) ? 1200 : $meta['powergallery_width'][0];
		$powergallery_height = ! isset( $meta['powergallery_height'][0] ) ? 300 : $meta['powergallery_height'][0];

		$powergallery_columns = ! isset( $meta['powergallery_columns'][0] ) ? 3 : $meta['powergallery_columns'][0];

		$powergallery_zoom = ! isset( $meta['powergallery_zoom'][0] ) ? 0 : $meta['powergallery_zoom'][0];

		$powergallery_border = ! isset( $meta['powergallery_border'][0] ) ? 0 : $meta['powergallery_border'][0];
		$powergallery_border_color = ! isset( $meta['powergallery_border_color'][0] ) ? '' : $meta['powergallery_border_color'][0];
		$powergallery_margin = ! isset( $meta['powergallery_margin'][0] ) ? 0 : $meta['powergallery_margin'][0];

		$powergallery_css = ! isset( $meta['powergallery_css'][0] ) ? '' : $meta['powergallery_css'][0];

		wp_nonce_field( basename( __FILE__ ), 'powergallery_admin_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="powergallery_meta_box_td" colspan="1">
					<label for="powergallery_type" style="font-weight: bold;"><?php _e( 'Powergallery type', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<select name="powergallery_type">
						<option	value="0" <?php if ( $powergallery_type == 0 ) { echo 'selected'; } ?> ><?php _e( 'Simple', 'wordmedia' ); ?></option>
						<option	value="1" <?php if ( $powergallery_type == 1 ) { echo 'selected'; } ?> ><?php _e( 'Autoalpina', 'wordmedia' ); ?></option>
						<option	value="2" <?php if ( $powergallery_type == 2 ) { echo 'selected'; } ?> ><?php _e( 'Chaumière', 'wordmedia' ); ?></option>
						<option	value="3" <?php if ( $powergallery_type == 3 ) { echo 'selected'; } ?> ><?php _e( 'Dimo', 'wordmedia' ); ?></option>
					</select>
				</td>
			</tr>

			<tr>
				<td class="powergallery_meta_box_td" colspan="1">
					<label for="powergallery_width" style="font-weight: bold;"><?php _e( 'Gallery width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="powergallery_width" class="regular-text" value="<?php echo $powergallery_width ?>" required /> px
				</td>
			</tr>
			<tr>
				<td class="powergallery_meta_box_td" colspan="1">
					<label for="powergallery_height" style="font-weight: bold;"><?php _e( 'Row height', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="powergallery_height" class="regular-text" value="<?php echo $powergallery_height ?>" required /> px
				</td>
			</tr>

			<tr>
				<td class="powergallery_meta_box_td" colspan="1">
					<label for="powergallery_columns" style="font-weight: bold;"><?php _e( 'Columns (pictures per row)', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="powergallery_columns" class="regular-text" value="<?php echo $powergallery_columns ?>" required />
				</td>
			</tr>

			<tr>
				<td class="powergallery_meta_box_td" colspan="1">
					<label for="powergallery_zoom" style="font-weight: bold;"><?php _e( 'Zoom on hover?', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="checkbox" name="powergallery_zoom" value="1" <?php checked( 1 == $powergallery_zoom ); ?> />
				</td>
			</tr>

			<tr>
				<td class="powergallery_meta_box_td" colspan="1">
					<label for="powergallery_margin" style="font-weight: bold;"><?php _e( 'Margin width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="powergallery_margin" class="regular-text" value="<?php echo $powergallery_margin ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="powergallery_meta_box_td" colspan="1">
					<label for="powergallery_border" style="font-weight: bold;"><?php _e( 'Border width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="powergallery_border" class="regular-text" value="<?php echo $powergallery_border ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="powergallery_meta_box_td" colspan="1">
					<label for="powergallery_border_color" style="font-weight: bold;"><?php _e( 'Border color', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="powergallery_border_color" value="<?php echo $powergallery_border_color ?>" />
				</td>
			</tr>
			

			<tr>
				<td class="powergallery_meta_box_td" colspan="1">
					<label for="powergallery_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="powergallery_css" class="regular-text" rows="10" cols="100"><?= $powergallery_css; ?></textarea>
				</td>
			</tr>

		</table>

	<?php }

    /**
	 * Save slider design metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_powergallery_admin_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['powergallery_admin_fields'] ) || !wp_verify_nonce( $_POST['powergallery_admin_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['powergallery_type'] = ( isset( $_POST['powergallery_type'] ) ? esc_textarea( $_POST['powergallery_type'] ) : 0 );

		$meta['powergallery_width'] = ( isset( $_POST['powergallery_width'] ) ? esc_textarea( $_POST['powergallery_width'] ) : 1500 );
		$meta['powergallery_height'] = ( isset( $_POST['powergallery_height'] ) ? esc_textarea( $_POST['powergallery_height'] ) : 300 );

		$meta['powergallery_columns'] = ( isset( $_POST['powergallery_columns'] ) ? esc_textarea( $_POST['powergallery_columns'] ) : 0 );

		$meta['powergallery_border'] = ( isset( $_POST['powergallery_border'] ) ? esc_textarea( $_POST['powergallery_border'] ) : 0 );
		$meta['powergallery_border_color'] = ( isset( $_POST['powergallery_border_color'] ) ? esc_textarea( $_POST['powergallery_border_color'] ) : '' );
		$meta['powergallery_margin'] = ( isset( $_POST['powergallery_margin'] ) ? esc_textarea( $_POST['powergallery_margin'] ) : 0 );

		$meta['powergallery_zoom'] = ( isset( $_POST['powergallery_zoom'] ) ? $_POST['powergallery_zoom'] : 0 );
		
		$meta['powergallery_css'] = ( isset( $_POST['powergallery_css'] ) ? htmlspecialchars( $_POST['powergallery_css'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the metaboxes to be used for the PowerGallery post type
	 *
	 * @since    0.0.1
	 */
	public function add_powergallery_meta_boxes() {
		add_meta_box(
			'powergallery_fields',
			__( 'PowerGallery', 'wordmedia' ),
			array( $this, 'render_powergallery_meta_boxes' ),
			'powergallery',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the PowerGallery metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_powergallery_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$powergallery_pics = ! isset( $meta['powergallery_pics'][0] ) ? array() :  maybe_unserialize( $meta['powergallery_pics'][0] );

		$powergallery_pics_src = array();
		foreach ($powergallery_pics as $key => $value) {
			if ( isset( $powergallery_pics[$key] ) ) {
				$powergallery_pics_src[$key] = wp_get_attachment_url($value);
			}
		}

		wp_nonce_field( basename( __FILE__ ), 'powergallery_fields' ); ?>


		<a class="button-primary button" style="display: block; text-align: center; width: 80px;" id="add-pic"><?php _e( 'Add', 'wordmedia' ); ?></a>

		<table class="form-table form-table-powergallery">

			<?php foreach ( $powergallery_pics as $key => $value ) { ?>
				
				<tr data-powergallery="<?php echo $key; ?>" style="border: 2px dotted #666666">
					<td class="powergallery_meta_box_td" colspan="1">
						<label for="powergallery_pics[<?php echo $key; ?>]" style="font-weight: bold;"><?php _e( 'Picture ', 'wordmedia' ); echo ( $key + 1 ); ?></label>
					</td>
					<td colspan="4">
						<input type="hidden" name="powergallery_pics[<?php echo $key; ?>]" id="powergallery_pics_<?php echo $key; ?>" class="regular-text" value="<?php echo $powergallery_pics[$key] ?>">
						<input class="powergallery_upload button" id="powergallery_upload_<?php echo $key; ?>" type="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
						<input class="powergallery_remove button" id="powergallery_remove_<?php echo $key; ?>" type="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
						<img id="powergallery_pics_<?php echo $key; ?>_preview" src="<?php echo $powergallery_pics_src[$key] ?>" style="max-height:200px;" />
						<a class="button-primary button remove-pic" style="display: inline-block; text-align: center; width: 80px;"><?php _e( 'Remove', 'wordmedia' ); ?></a>
					</td>					
				</tr>

				<script>
					jQuery(document).ready(function($){
						var file_frame;
						var wp_media_post_id = wp.media.model.settings.post.id;
						var set_to_post_id = $( '#image_attachment_id' ).val() != '' && $( '#image_attachment_id' ).val() != undefined && $( '#image_attachment_id' ).val() != null ? $( '#image_attachment_id' ).val() : 0;

						$('#powergallery_upload_<?php echo $key; ?>').on('click', function( event ){
							event.preventDefault();
							wp.media.model.settings.post.id = set_to_post_id;
							file_frame = wp.media.frames.file_frame = wp.media({
								title: 'PowerGallery <?php echo ( $key + 1 ); ?>',
								button: {
									text: 'Scegli',
								},
								multiple: false
							});
							file_frame.on( 'select', function() {
								if (file_frame.options.title == 'PowerGallery <?php echo ( $key + 1 ); ?>') {
									var attachment = file_frame.state().get('selection').first().toJSON();
									$( '#powergallery_pics_<?php echo $key; ?>_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );									
									$( '#powergallery_pics_<?php echo $key; ?>' ).val( attachment.id );
									wp.media.model.settings.post.id = wp_media_post_id;
								}
							});
								file_frame.open();
						});
						$('#powergallery_remove_<?php echo $key; ?>').on('click', function ( event) {
							$( '#powergallery_pics_<?php echo $key; ?>_preview' ).attr( 'src', '' );
							$( '#powergallery_pics_<?php echo $key; ?>' ).val( '' );
						});
						$('.remove-pic').on('click', function(event){
							$(this).parent().parent().remove();
						});
					});
					
				</script>

			<?php } ?>

		</table>

		<script>
			jQuery(document).ready(function($){
				$('#add-pic').on('click', function(event){
					event.preventDefault();

					var last = $('.form-table-powergallery td.powergallery_meta_box_td').parent('tr').last();

					var id = last.length == 0 ? 0 : parseInt(last.attr('data-powergallery')) + 1;

					var nextTr = $('<tr></tr>').attr('data-powergallery', id).css('border', '2px dotted #666666');
					var firstTd = $('<td></td>').addClass('powergallery_meta_box_td').attr('colspan', '1');
					var label = $('<label></label>').attr('for', 'powergallery_pics_' + id ).css('font-weight', 'bold').text('<?php _e( 'Picture ', 'wordmedia' ); ?> ' + (id + 1));
					var secondTd = $('<td></td>').attr('colspan', '4');
					var hiddenInput = $('<input></input>').attr('type', 'hidden').attr('id', 'powergallery_pics_' + id ).attr('name', 'powergallery_pics[' + id + ']').addClass('regular-text').val('');
					var uploadInput = $('<input></input>').attr('type', 'button').addClass('powergallery_upload').attr('id', 'powergallery_upload_' + id).addClass('button').val('<?php _e( 'Upload picture', 'wordmedia' ); ?>');
					var removeInput = $('<input></input>').attr('type', 'button').addClass('powergallery_remove').attr('id', 'powergallery_remove_' + id).addClass('button').val('<?php _e( 'Remove picture', 'wordmedia' ); ?>');
					var previewImg = $('<img></img>').css('max-height', '200px').attr('id', 'powergallery_pics_' + id + '_preview').attr('src', '');
					var removeButton = $('<a></a>').css('display', 'inline-block').css('text-align', 'center').css('width', '80px').addClass('button').addClass('button-primary').addClass('remove-pic').text('<?php _e( 'Remove', 'wordmedia' ); ?>');

					label.appendTo(firstTd);
					hiddenInput.appendTo(secondTd);
					uploadInput.appendTo(secondTd);
					removeInput.appendTo(secondTd);
					previewImg.appendTo(secondTd);
					removeButton.appendTo(secondTd);
					firstTd.appendTo(nextTr);
					secondTd.appendTo(nextTr);

					if (last.length == 0) {
						nextTr.appendTo($('.form-table-powergallery'));
					}
					else {
						nextTr.appendTo(last.parent());
					}

					$(document).ready(function($){
						var file_frame;
						var wp_media_post_id = wp.media.model.settings.post.id;
						var set_to_post_id = $( '#image_attachment_id' ).val() != '' && $( '#image_attachment_id' ).val() != undefined && $( '#image_attachment_id' ).val() != null ? $( '#image_attachment_id' ).val() : 0;

						$('#powergallery_upload_' + id).on('click', function( event ){
							event.preventDefault();
							wp.media.model.settings.post.id = set_to_post_id;
							file_frame = wp.media.frames.file_frame = wp.media({
								title: 'PowerGallery ' + ( id + 1 ),
								button: {
									text: 'Scegli',
								},
								multiple: false
							});
							file_frame.on( 'select', function() {
								if (file_frame.options.title == 'PowerGallery ' + ( id + 1 ) ) {
									var attachment = file_frame.state().get('selection').first().toJSON();
									$( '#powergallery_pics_' + id + '_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
									$( '#powergallery_pics_' + id ).val( attachment.id );
									wp.media.model.settings.post.id = wp_media_post_id;
								}
							});
								file_frame.open();
						});
						$('#powergallery_remove_' + id).on('click', function ( event) {
							$( '#powergallery_pics_' + id + '_preview' ).attr( 'src', '' );
							$( '#powergallery_pics_' + id ).val( '' );
						});
					});
					$('.remove-pic').on('click', function(event){
						$(this).parent().parent().remove();
					});
				});
			});

		</script>


	<?php }

    /**
	 * Save PowerGallery metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_powergallery_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['powergallery_fields'] ) || !wp_verify_nonce( $_POST['powergallery_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}
		
		$meta = array();

		$meta['powergallery_pics'] = ( isset( $_POST['powergallery_pics'] ) ? $_POST['powergallery_pics'] : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the MenuBar custom post type.
	 *
	 * @since    0.0.1
	 * @link 	 http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function register_menubar_post_type() {
		$labels = array(
			'name'               => __( 'Menu Bars', 'wordmedia' ),
			'singular_name'      => __( 'Menu Bar', 'wordmedia' ),
			'add_new'            => __( 'Add Menu Bar', 'wordmedia' ),
			'add_new_item'       => __( 'Add Menu Bar', 'wordmedia' ),
			'edit_item'          => __( 'Edit Menu Bar', 'wordmedia' ),
			'new_item'           => __( 'New Menu Bar', 'wordmedia' ),
			'view_item'          => __( 'View Menu Bar', 'wordmedia' ),
			'search_items'       => __( 'Search Menu Bar', 'wordmedia' ),
			'not_found'          => __( 'No Menu Bar found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Menu Bar in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'menubar', 'wordmedia' ) ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-share-alt',
			'show_in_rest'	  => true,
			'capabilities' 	  => array(
				    'edit_post'          => 'update_core',
				    'read_post'          => 'update_core',
				    'delete_post'        => 'update_core',
				    'edit_posts'         => 'update_core',
				    'edit_others_posts'  => 'update_core',
				    'delete_posts'       => 'update_core',
				    'publish_posts'      => 'update_core',
				    'read_private_posts' => 'update_core'
			)
		);

		//filter for altering the args
		$args = apply_filters( 'menubar_post_type_args', $args );

		register_post_type( 'menubar', $args );
	}

	/**
	 * Register the metaboxes to be used for the MenuBar post type
	 *
	 * @since    0.0.1
	 */
	public function add_menubar_meta_boxes() {
		add_meta_box(
			'menubar_fields',
			__( 'Menu Bar', 'wordmedia' ),
			array( $this, 'render_menubar_meta_boxes' ),
			'menubar',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the MenuBar metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_menubar_meta_boxes( $post ) {

		$menus = get_registered_nav_menus();

		$meta = get_post_custom( $post->ID );

		$menubar_width = ! isset( $meta['menubar_width'][0] ) ? 1500 : $meta['menubar_width'][0];
       	$menubar_height = ! isset( $meta['menubar_height'][0] ) ? 150 : $meta['menubar_height'][0];
        $menubar_background = ! isset( $meta['menubar_background'][0] ) ? '' : $meta['menubar_background'][0];

        $menubar_menu_id = ! isset( $meta['menubar_menu_id'][0] ) ? '' : $meta['menubar_menu_id'][0];
        $menubar_menu_type = ! isset( $meta['menubar_menu_type'][0] ) ? 0 : $meta['menubar_menu_type'][0];
	    $menubar_menu_style = ! isset( $meta['menubar_menu_style'][0] ) ? 0 : $meta['menubar_menu_style'][0];
	    $menubar_menu_theme = ! isset( $meta['menubar_menu_theme'][0] ) ? 0 : $meta['menubar_menu_theme'][0];

		$menubar_hamburger_pic = ! isset( $meta['menubar_hamburger_pic'][0] ) ? '' : $meta['menubar_hamburger_pic'][0];
		$menubar_hamburger_caption = ! isset( $meta['menubar_hamburger_caption'][0] ) ? '' : $meta['menubar_hamburger_caption'][0];
		$menubar_hamburger_width = ! isset( $meta['menubar_hamburger_width'][0] ) ? 30 : $meta['menubar_hamburger_width'][0];
		$menubar_hamburger_height = ! isset( $meta['menubar_hamburger_height'][0] ) ? 30 : $meta['menubar_hamburger_height'][0];
		$menubar_hamburger_top = ! isset( $meta['menubar_hamburger_top'][0] ) ? '' : $meta['menubar_hamburger_top'][0];
		$menubar_hamburger_right = ! isset( $meta['menubar_hamburger_right'][0] ) ? '' : $meta['menubar_hamburger_right'][0];
		$menubar_hamburger_bottom = ! isset( $meta['menubar_hamburger_bottom'][0] ) ? '' : $meta['menubar_hamburger_bottom'][0];
		$menubar_hamburger_left = ! isset( $meta['menubar_hamburger_left'][0] ) ? '' : $meta['menubar_hamburger_left'][0];

		$menubar_social_width = ! isset( $meta['menubar_social_width'][0] ) ? 30 : $meta['menubar_social_width'][0];
		$menubar_social_height = ! isset( $meta['menubar_social_height'][0] ) ? 30 : $meta['menubar_social_height'][0];
		$menubar_social_top = ! isset( $meta['menubar_social_top'][0] ) ? '' : $meta['menubar_social_top'][0];
		$menubar_social_right = ! isset( $meta['menubar_social_right'][0] ) ? '' : $meta['menubar_social_right'][0];
		$menubar_social_bottom = ! isset( $meta['menubar_social_bottom'][0] ) ? '' : $meta['menubar_social_bottom'][0];
		$menubar_social_left = ! isset( $meta['menubar_social_left'][0] ) ? '' : $meta['menubar_social_left'][0];
		$menubar_social_margin = ! isset( $meta['menubar_social_margin'][0] ) ? 0 : $meta['menubar_social_margin'][0];

		$menubar_social_fb_pic = ! isset( $meta['menubar_social_fb_pic'][0] ) ? '' : $meta['menubar_social_fb_pic'][0];
		$menubar_social_fb_link = ! isset( $meta['menubar_social_fb_link'][0] ) ? '' : $meta['menubar_social_fb_link'][0];
		$menubar_social_yt_pic = ! isset( $meta['menubar_social_yt_pic'][0] ) ? '' : $meta['menubar_social_yt_pic'][0];
		$menubar_social_yt_link = ! isset( $meta['menubar_social_yt_link'][0] ) ? '' : $meta['menubar_social_yt_link'][0];
		$menubar_social_tw_pic = ! isset( $meta['menubar_social_tw_pic'][0] ) ? '' : $meta['menubar_social_tw_pic'][0];
		$menubar_social_tw_link = ! isset( $meta['menubar_social_tw_link'][0] ) ? '' : $meta['menubar_social_tw_link'][0];
		$menubar_social_ig_pic = ! isset( $meta['menubar_social_ig_pic'][0] ) ? '' : $meta['menubar_social_ig_pic'][0];
		$menubar_social_ig_link = ! isset( $meta['menubar_social_ig_link'][0] ) ? '' : $meta['menubar_social_ig_link'][0];

		$menubar_hamburger_pic_src = $menubar_hamburger_pic != '' ? wp_get_attachment_url( $menubar_hamburger_pic ) : '';
		$menubar_social_fb_pic_src = $menubar_social_fb_pic != '' ? wp_get_attachment_url( $menubar_social_fb_pic ) : '';
		$menubar_social_yt_pic_src = $menubar_social_yt_pic != '' ? wp_get_attachment_url( $menubar_social_yt_pic ) : '';
		$menubar_social_tw_pic_src = $menubar_social_tw_pic != '' ? wp_get_attachment_url( $menubar_social_tw_pic ) : '';
		$menubar_social_ig_pic_src = $menubar_social_ig_pic != '' ? wp_get_attachment_url( $menubar_social_ig_pic ) : '';

		$menubar_css = ! isset( $meta['menubar_css'][0] ) ? '' : $meta['menubar_css'][0];

		wp_nonce_field( basename( __FILE__ ), 'menubar_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_width" style="font-weight: bold;"><?php _e( 'Bar width', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_width" class="regular-text" value="<?= $menubar_width; ?>" required> px
					<p class="description"><?php _e( 'Example: 1500', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_height" style="font-weight: bold;"><?php _e( 'Bar height', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_height" class="regular-text" value="<?= $menubar_height; ?>" required> px
					<p class="description"><?php _e( 'Example: 150', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_background" style="font-weight: bold;"><?php _e( 'Bar background', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="color" name="menubar_background" value="<?= $menubar_background; ?>">
				</td>
			</tr>

			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_menu_id" style="font-weight: bold;"><?php _e( 'Menu', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<select name="menubar_menu_id">
						<?php foreach ($menus as $location => $description): ?>
							<option	value="<?= $location ?>" <?php if ( $location == $menubar_menu_id ) { echo 'selected'; } ?> ><?= $description ?></option>
						<?php endforeach ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_menu_type" style="font-weight: bold;"><?php _e( 'Menu type', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<select name="menubar_menu_type">
						<option	value="0" <?php if ( $menubar_menu_type == 0 ) { echo 'selected'; } ?> ><?php _e( 'Slideout', 'wordmedia' ); ?></option>
						<option	value="1" <?php if ( $menubar_menu_type == 1 ) { echo 'selected'; } ?> ><?php _e( 'Dropdown', 'wordmedia' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_menu_style" style="font-weight: bold;"><?php _e( 'Menu style', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<select name="menubar_menu_style">
						<option	value="0" <?php if ( $menubar_menu_style == 0 ) { echo 'selected'; } ?> ><?php _e( 'Slide', 'wordmedia' ); ?></option>
						<option	value="1" <?php if ( $menubar_menu_style == 1 ) { echo 'selected'; } ?> ><?php _e( 'Fade', 'wordmedia' ); ?></option>
						<option	value="2" <?php if ( $menubar_menu_style == 2 ) { echo 'selected'; } ?> ><?php _e( 'Zoom', 'wordmedia' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_menu_theme" style="font-weight: bold;"><?php _e( 'Menu theme', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<select name="menubar_menu_theme">
						<option	value="0" <?php if ( $menubar_menu_theme == 0 ) { echo 'selected'; } ?> ><?php _e( 'Light', 'wordmedia' ); ?></option>
						<option	value="1" <?php if ( $menubar_menu_theme == 1 ) { echo 'selected'; } ?> ><?php _e( 'Dark', 'wordmedia' ); ?></option>
						<option	value="2" <?php if ( $menubar_menu_theme == 2 ) { echo 'selected'; } ?> ><?php _e( 'White', 'wordmedia' ); ?></option>
						<option	value="3" <?php if ( $menubar_menu_theme == 3 ) { echo 'selected'; } ?> ><?php _e( 'Black', 'wordmedia' ); ?></option>
					</select>
				</td>
			</tr>

			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_hamburger_pic" style="font-weight: bold;"><?php _e( 'Hamburger picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="menubar_hamburger_pic" id="menubar_hamburger_pic" class="regular-text" value="<?php echo $menubar_hamburger_pic ?>">
					<input id="upload_menubar_hamburger_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_menubar_hamburger_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="menubar_hamburger_pic_preview" src="<?php echo $menubar_hamburger_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_hamburger_caption" style="font-weight: bold;"><?php _e( 'Hamburger caption', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="menubar_hamburger_caption" class="regular-text" value="<?= $menubar_hamburger_caption; ?>">
					<p class="description"><?php _e( 'Example: Menu', 'wordmedia' ); ?></p>
				</td>
			</tr>			
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_hamburger_width" style="font-weight: bold;"><?php _e( 'Hamburger width', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_hamburger_width" class="regular-text" value="<?= $menubar_hamburger_width; ?>" required> px
					<p class="description"><?php _e( 'Example: 30', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_hamburger_height" style="font-weight: bold;"><?php _e( 'Hamburger height', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_hamburger_height" class="regular-text" value="<?= $menubar_hamburger_height; ?>" required> px
					<p class="description"><?php _e( 'Example: 30', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_hamburger_top" style="font-weight: bold;"><?php _e( 'Hamburger position top', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_hamburger_top" class="regular-text" value="<?= $menubar_hamburger_top; ?>"> px
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_hamburger_right" style="font-weight: bold;"><?php _e( 'Hamburger position right', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_hamburger_right" class="regular-text" value="<?= $menubar_hamburger_right; ?>"> px
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_hamburger_bottom" style="font-weight: bold;"><?php _e( 'Hamburger position bottom', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_hamburger_bottom" class="regular-text" value="<?= $menubar_hamburger_bottom; ?>"> px
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_hamburger_left" style="font-weight: bold;"><?php _e( 'Hamburger position left', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_hamburger_left" class="regular-text" value="<?= $menubar_hamburger_left; ?>"> px
				</td>
			</tr>

			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_width" style="font-weight: bold;"><?php _e( 'Social width', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_social_width" class="regular-text" value="<?= $menubar_social_width; ?>" required> px
					<p class="description"><?php _e( 'Example: 30', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_height" style="font-weight: bold;"><?php _e( 'Social height', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_social_height" class="regular-text" value="<?= $menubar_social_height; ?>" required> px
					<p class="description"><?php _e( 'Example: 30', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_top" style="font-weight: bold;"><?php _e( 'Social position top', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_social_top" class="regular-text" value="<?= $menubar_social_top; ?>"> px
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_right" style="font-weight: bold;"><?php _e( 'Social position: right', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_social_right" class="regular-text" value="<?= $menubar_social_right; ?>"> px
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_bottom" style="font-weight: bold;"><?php _e( 'Social position bottom', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_social_bottom" class="regular-text" value="<?= $menubar_social_bottom; ?>"> px
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_right" style="font-weight: bold;"><?php _e( 'Social position: right', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_social_right" class="regular-text" value="<?= $menubar_social_right; ?>"> px
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_margin" style="font-weight: bold;"><?php _e( 'Social margin', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="menubar_social_margin" class="regular-text" value="<?= $menubar_social_margin; ?>"> px
					<p class="description"><?php _e( 'Example: 30', 'wordmedia' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_fb_link" style="font-weight: bold;"><?php _e( 'Facebook Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="menubar_social_fb_link" class="regular-text" value="<?= $menubar_social_fb_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); echo ' https://www.facebook.com/<b>VisaMultimedia-240882866021318</b>'?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_social_fb_pic" style="font-weight: bold;"><?php _e( 'Facebook Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="menubar_social_fb_pic" id="menubar_social_fb_pic" class="regular-text" value="<?php echo $menubar_social_fb_pic ?>">
					<input id="upload_menubar_social_fb_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_menubar_social_fb_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="menubar_social_fb_pic_preview" src="<?php echo $menubar_social_fb_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_yt_link" style="font-weight: bold;"><?php _e( 'YouTube Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="menubar_social_yt_link" class="regular-text" value="<?= $menubar_social_yt_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); echo ' https://www.youtube.com/user/<b>visamultimedia</b>'?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_social_yt_pic" style="font-weight: bold;"><?php _e( 'Youtube Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="menubar_social_yt_pic" id="menubar_social_yt_pic" class="regular-text" value="<?php echo $menubar_social_yt_pic ?>">
					<input id="upload_menubar_social_yt_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_menubar_social_yt_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="menubar_social_yt_pic_preview" src="<?php echo $menubar_social_yt_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_tw_link" style="font-weight: bold;"><?php _e( 'Twitter Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="menubar_social_tw_link" class="regular-text" value="<?= $menubar_social_tw_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); _e( '@<b>yourname</b>' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_social_tw_pic" style="font-weight: bold;"><?php _e( 'Twitter Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="menubar_social_tw_pic" id="menubar_social_tw_pic" class="regular-text" value="<?php echo $menubar_social_tw_pic ?>">
					<input id="upload_menubar_social_tw_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_menubar_social_tw_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="menubar_social_tw_pic_preview" src="<?php echo $menubar_social_tw_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="3">
					<label for="menubar_social_ig_link" style="font-weight: bold;"><?php _e( 'Instagram Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="menubar_social_ig_link" class="regular-text" value="<?= $menubar_social_ig_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); _e( '@<b>yourname</b>' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_social_ig_pic" style="font-weight: bold;"><?php _e( 'Instagram Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="menubar_social_ig_pic" id="menubar_social_ig_pic" class="regular-text" value="<?php echo $menubar_social_ig_pic ?>">
					<input id="upload_menubar_social_ig_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_menubar_social_ig_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="menubar_social_ig_pic_preview" src="<?php echo $menubar_social_ig_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>

			<tr>
				<td class="menubar_meta_box_td" colspan="1">
					<label for="menubar_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="menubar_css" class="regular-text" rows="10" cols="100"><?= $menubar_css; ?></textarea>
				</td>
			</tr>

		</table>

		<script>
			jQuery(document).ready(function($){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = $( '#image_attachment_id' ).val() != '' && $( '#image_attachment_id' ).val() != undefined && $( '#image_attachment_id' ).val() != null ? $( '#image_attachment_id' ).val() : 0;

				$('#upload_menubar_hamburger_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Hamburger Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Hamburger Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#menubar_hamburger_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#menubar_hamburger_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_menubar_hamburger_pic').on('click', function ( event) {
					$( '#menubar_hamburger_pic_preview' ).attr( 'src', '' );
					$( '#menubar_hamburger_pic' ).val( '' );
				});

				$('#upload_menubar_social_fb_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social Facebook Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social Facebook Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#menubar_social_fb_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#menubar_social_fb_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_menubar_social_fb_pic').on('click', function ( event) {
					$( '#menubar_social_fb_pic_preview' ).attr( 'src', '' );
					$( '#menubar_social_fb_pic' ).val( '' );
				});
				$('#upload_menubar_social_yt_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social YouTube Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social YouTube Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#menubar_social_yt_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#menubar_social_yt_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_menubar_social_yt_pic').on('click', function ( event) {
					$( '#menubar_social_yt_pic_preview' ).attr( 'src', '' );
					$( '#menubar_social_yt_pic' ).val( '' );
				});
				$('#upload_menubar_social_tw_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social Twitter Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social Twitter Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#menubar_social_tw_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#menubar_social_tw_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_menubar_social_tw_pic').on('click', function ( event) {
					$( '#menubar_social_tw_pic_preview' ).attr( 'src', '' );
					$( '#menubar_social_tw_pic' ).val( '' );
				});
				$('#upload_menubar_social_ig_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social Instagram Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social Instagram Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#menubar_social_ig_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#menubar_social_ig_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_menubar_social_ig_pic').on('click', function ( event) {
					$( '#menubar_social_ig_pic_preview' ).attr( 'src', '' );
					$( '#menubar_social_ig_pic' ).val( '' );
				});
			});
		</script>

	<?php }

    /**
	 * Save menubar description metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_menubar_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['menubar_fields'] ) || !wp_verify_nonce( $_POST['menubar_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['menubar_width'] = ( isset( $_POST['menubar_width'] ) ? esc_textarea( $_POST['menubar_width'] ) : 1500 );
		$meta['menubar_height'] = ( isset( $_POST['menubar_height'] ) ? esc_textarea( $_POST['menubar_height'] ) : 150 );
		$meta['menubar_background'] = ( isset( $_POST['menubar_background'] ) ? esc_textarea( $_POST['menubar_background'] ) : '' );

		$meta['menubar_menu_id'] = ( isset( $_POST['menubar_menu_id'] ) ? $_POST['menubar_menu_id'] : '' );
		$meta['menubar_menu_type'] = ( isset( $_POST['menubar_menu_type'] ) ? $_POST['menubar_menu_type'] : 0 );
		$meta['menubar_menu_style'] = ( isset( $_POST['menubar_menu_style'] ) ? $_POST['menubar_menu_style'] : 0 );
		$meta['menubar_menu_theme'] = ( isset( $_POST['menubar_menu_theme'] ) ? $_POST['menubar_menu_theme'] : 0 );

		$meta['menubar_hamburger_pic'] = ( isset( $_POST['menubar_hamburger_pic'] ) ? esc_textarea( $_POST['menubar_hamburger_pic'] ) : '' );
		$meta['menubar_hamburger_caption'] = ( isset( $_POST['menubar_hamburger_caption'] ) ? esc_textarea( $_POST['menubar_hamburger_caption'] ) : '' );
		$meta['menubar_hamburger_width'] = ( isset( $_POST['menubar_hamburger_width'] ) ? esc_textarea( $_POST['menubar_hamburger_width'] ) : 30 );
		$meta['menubar_hamburger_height'] = ( isset( $_POST['menubar_hamburger_height'] ) ? esc_textarea( $_POST['menubar_hamburger_height'] ) : 30 );
		$meta['menubar_hamburger_top'] = ( isset( $_POST['menubar_hamburger_top'] ) ? esc_textarea( $_POST['menubar_hamburger_top'] ) : 0 );
		$meta['menubar_hamburger_right'] = ( isset( $_POST['menubar_hamburger_right'] ) ? esc_textarea( $_POST['menubar_hamburger_right'] ) : 0 );
		$meta['menubar_hamburger_bottom'] = ( isset( $_POST['menubar_hamburger_bottom'] ) ? esc_textarea( $_POST['menubar_hamburger_bottom'] ) : 0 );
		$meta['menubar_hamburger_left'] = ( isset( $_POST['menubar_hamburger_left'] ) ? esc_textarea( $_POST['menubar_hamburger_left'] ) : 0 );

		$meta['menubar_social_width'] = ( isset( $_POST['menubar_social_width'] ) ? esc_textarea( $_POST['menubar_social_width'] ) : 30 );
		$meta['menubar_social_height'] = ( isset( $_POST['menubar_social_height'] ) ? esc_textarea( $_POST['menubar_social_height'] ) : 30 );
		$meta['menubar_social_top'] = ( isset( $_POST['menubar_social_top'] ) ? esc_textarea( $_POST['menubar_social_top'] ) : 0 );
		$meta['menubar_social_right'] = ( isset( $_POST['menubar_social_right'] ) ? esc_textarea( $_POST['menubar_social_right'] ) : 0 );
		$meta['menubar_social_bottom'] = ( isset( $_POST['menubar_social_bottom'] ) ? esc_textarea( $_POST['menubar_social_bottom'] ) : 0 );
		$meta['menubar_social_left'] = ( isset( $_POST['menubar_social_left'] ) ? esc_textarea( $_POST['menubar_social_left'] ) : 0 );
		$meta['menubar_social_margin'] = ( isset( $_POST['menubar_social_margin'] ) ? esc_textarea( $_POST['menubar_social_margin'] ) : 0 );

		$meta['menubar_social_fb_pic'] = ( isset( $_POST['menubar_social_fb_pic'] ) ? esc_textarea( $_POST['menubar_social_fb_pic'] ) : '' );
		$meta['menubar_social_fb_link'] = ( isset( $_POST['menubar_social_fb_link'] ) ? esc_textarea( $_POST['menubar_social_fb_link'] ) : '' );
		$meta['menubar_social_yt_pic'] = ( isset( $_POST['menubar_social_yt_pic'] ) ? esc_textarea( $_POST['menubar_social_yt_pic'] ) : '' );
		$meta['menubar_social_yt_link'] = ( isset( $_POST['menubar_social_yt_link'] ) ? esc_textarea( $_POST['menubar_social_yt_link'] ) : '' );
		$meta['menubar_social_tw_pic'] = ( isset( $_POST['menubar_social_tw_pic'] ) ? esc_textarea( $_POST['menubar_social_tw_pic'] ) : '' );
		$meta['menubar_social_tw_link'] = ( isset( $_POST['menubar_social_tw_link'] ) ? esc_textarea( $_POST['menubar_social_tw_link'] ) : '' );
		$meta['menubar_social_ig_pic'] = ( isset( $_POST['menubar_social_ig_pic'] ) ? esc_textarea( $_POST['menubar_social_ig_pic'] ) : '' );
		$meta['menubar_social_ig_link'] = ( isset( $_POST['menubar_social_ig_link'] ) ? esc_textarea( $_POST['menubar_social_ig_link'] ) : '' );

		$meta['menubar_css'] = ( isset( $_POST['menubar_css'] ) ? esc_textarea( $_POST['menubar_css'] ) : '' );


		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the Evidences custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function register_evidences_post_type() {
		$labels = array(
			'name'               => __( 'Evidences', 'wordmedia' ),
			'singular_name'      => __( 'Evidence', 'wordmedia' ),
			'add_new'            => __( 'Add Evidence', 'wordmedia' ),
			'add_new_item'       => __( 'Add Evidence', 'wordmedia' ),
			'edit_item'          => __( 'Edit Evidence', 'wordmedia' ),
			'new_item'           => __( 'New Evidence', 'wordmedia' ),
			'view_item'          => __( 'View Evidence', 'wordmedia' ),
			'search_items'       => __( 'Search Evidence', 'wordmedia' ),
			'not_found'          => __( 'No Evidence found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Evidence in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'evidences', 'wordmedia' ) ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-star-empty',
			'show_in_rest'	  => true
		);

		//filter for altering the args
		$args = apply_filters( 'evidences_post_type_args', $args );

		register_post_type( 'evidences', $args );
	}

	/**
	 * Register the design metaboxes to be used for the Evidences post type
	 *
	 * @since    0.0.1
	 */
	public function add_evidences_admin_meta_boxes() {

		global $current_user;
		if($current_user->roles[0] == 'administrator') {
			add_meta_box(
				'evidences_admin_fields',
				__( 'Design', 'wordmedia' ),
				array( $this, 'render_evidences_admin_meta_boxes' ),
				'evidences',
				'normal',
				'high'
			);
		}
	}

    /**
	 * The HTML for the Evidences design metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_evidences_admin_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$evidences_title = ! isset( $meta['evidences_title'][0] ) ? '' : $meta['evidences_title'][0];

		$evidences_type = ! isset( $meta['evidences_type'][0] ) ? 0 : $meta['evidences_type'][0];

		$evidences_width = ! isset( $meta['evidences_width'][0] ) ? 1200 : $meta['evidences_width'][0];
		$evidences_height = ! isset( $meta['evidences_height'][0] ) ? 300 : $meta['evidences_height'][0];

		$evidences_columns = ! isset( $meta['evidences_columns'][0] ) ? 3 : $meta['evidences_columns'][0];

		$evidences_border = ! isset( $meta['evidences_border'][0] ) ? 0 : $meta['evidences_border'][0];
		$evidences_border_color = ! isset( $meta['evidences_border_color'][0] ) ? '' : $meta['evidences_border_color'][0];
		$evidences_margin = ! isset( $meta['evidences_margin'][0] ) ? 0 : $meta['evidences_margin'][0];

		$evidences_text_background = ! isset( $meta['evidences_text_background'][0] ) ? '' : $meta['evidences_text_background'][0];
		$evidences_extra_background = ! isset( $meta['evidences_extra_background'][0] ) ? '' : $meta['evidences_extra_background'][0];

		$evidences_pagination = ! isset( $meta['evidences_pagination'][0] ) ? 0 : $meta['evidences_pagination'][0];
    	$evidences_pagination_top = ! isset( $meta['evidences_pagination_top'][0] ) ? '' : $meta['evidences_pagination_top'][0];
		$evidences_pagination_right = ! isset( $meta['evidences_pagination_right'][0] ) ? '' : $meta['evidences_pagination_right'][0];
		$evidences_pagination_bottom = ! isset( $meta['evidences_pagination_bottom'][0] ) ? '' : $meta['evidences_pagination_bottom'][0];
		$evidences_pagination_left = ! isset( $meta['evidences_pagination_left'][0] ) ? '' : $meta['evidences_pagination_left'][0];
    	$evidences_pagination_css = ! isset( $meta['evidences_pagination_css'][0] ) ? '' : $meta['evidences_pagination_css'][0];
    	$evidences_pagination_element_css = ! isset( $meta['evidences_pagination_element_css'][0] ) ? '' : $meta['evidences_pagination_element_css'][0];
    	$evidences_pagination_active_element_css = ! isset( $meta['evidences_pagination_active_element_css'][0] ) ? '' : $meta['evidences_pagination_active_element_css'][0];

		$evidences_css = ! isset( $meta['evidences_css'][0] ) ? '' : $meta['evidences_css'][0];

		wp_nonce_field( basename( __FILE__ ), 'evidences_admin_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="evidences_title" class="regular-text" value="<?php echo $evidences_title; ?>" />
				</td>
			</tr>

			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_type" style="font-weight: bold;"><?php _e( 'Type', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<select name="evidences_type">
						<option	value="0" <?php if ( $evidences_type == '0' || $evidences_type == '' ) { echo 'selected'; } ?> ><?php _e( 'Tooltip', 'wordmedia' ); ?></option>
						<option	value="1" <?php if ( $evidences_type == '1' ) { echo 'selected'; } ?> ><?php _e( 'Slide', 'wordmedia' ); ?></option>
						<!--<option	value="1" <?php if ( $evidences_type == '2' ) { echo 'selected'; } ?> ><?php _e( 'Flip', 'wordmedia' ); ?></option>-->
					</select>
				</td>
			</tr>

			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_width" style="font-weight: bold;"><?php _e( 'Module width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="evidences_width" class="regular-text" value="<?php echo $evidences_width ?>" required /> px
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_height" style="font-weight: bold;"><?php _e( 'Row height', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="evidences_height" class="regular-text" value="<?php echo $evidences_height ?>" required /> px
				</td>
			</tr>

			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_columns" style="font-weight: bold;"><?php _e( 'Columns (elements per row)', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="evidences_columns" class="regular-text" value="<?php echo $evidences_columns ?>" required />
				</td>
			</tr>

			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_margin" style="font-weight: bold;"><?php _e( 'Margin width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="evidences_margin" class="regular-text" value="<?php echo $evidences_margin ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_border" style="font-weight: bold;"><?php _e( 'Border width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="evidences_border" class="regular-text" value="<?php echo $evidences_border ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_border_color" style="font-weight: bold;"><?php _e( 'Border color', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="evidences_border_color" value="<?php echo $evidences_border_color ?>" />
				</td>
			</tr>

			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_text_background" style="font-weight: bold;"><?php _e( 'Text background', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="evidences_text_background" value="<?php echo $evidences_text_background ?>" />
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_extra_background" style="font-weight: bold;"><?php _e( 'Extra background', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="evidences_extra_background" value="<?php echo $evidences_extra_background ?>" />
				</td>
			</tr>
			
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_pagination" style="font-weight: bold;"><?php _e( 'Pagination?', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="checkbox" name="evidences_pagination" value="1" <?php checked( 1 == $evidences_pagination ); ?> />
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_pagination_top" style="font-weight: bold;"><?php _e( 'Pagination position top', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="evidences_pagination_top" class="regular-text" value="<?php echo $evidences_pagination_top ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_pagination_right" style="font-weight: bold;"><?php _e( 'Pagination position right', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="evidences_pagination_right" class="regular-text"value="<?php echo $evidences_pagination_right ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_pagination_bottom" style="font-weight: bold;"><?php _e( 'Pagination position bottom', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="evidences_pagination_bottom" class="regular-text" value="<?php echo $evidences_pagination_bottom ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_pagination_left" style="font-weight: bold;"><?php _e( 'Pagination position left', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="evidences_pagination_left" class="regular-text"value="<?php echo $evidences_pagination_left ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_pagination_css" style="font-weight: bold;"><?php _e( 'Pagination CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="evidences_pagination_css" class="regular-text" rows="5" cols="100"><?= $evidences_pagination_css; ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_pagination_element_css" style="font-weight: bold;"><?php _e( 'Pagination element CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="evidences_pagination_element_css" class="regular-text" rows="5" cols="100"><?= $evidences_pagination_element_css; ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_pagination_active_element_css" style="font-weight: bold;"><?php _e( 'Pagination active element CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="evidences_pagination_active_element_css" class="regular-text" rows="5" cols="100"><?= $evidences_pagination_active_element_css; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="evidences_meta_box_td" colspan="1">
					<label for="evidences_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="evidences_css" class="regular-text" rows="10" cols="100"><?= $evidences_css; ?></textarea>
				</td>
			</tr>

		</table>

	<?php }

    /**
	 * Save Evidences design metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_evidences_admin_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['evidences_admin_fields'] ) || !wp_verify_nonce( $_POST['evidences_admin_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['evidences_title'] = ( isset( $_POST['evidences_title'] ) ? esc_textarea( $_POST['evidences_title'] ) : '' );

		$meta['evidences_type'] = ( isset( $_POST['evidences_type'] ) ? esc_textarea( $_POST['evidences_type'] ) : 0 );

		$meta['evidences_width'] = ( isset( $_POST['evidences_width'] ) ? esc_textarea( $_POST['evidences_width'] ) : 1200 );
		$meta['evidences_height'] = ( isset( $_POST['evidences_height'] ) ? esc_textarea( $_POST['evidences_height'] ) : 300 );

		$meta['evidences_columns'] = ( isset( $_POST['evidences_columns'] ) ? esc_textarea( $_POST['evidences_columns'] ) : 3 );

		$meta['evidences_border'] = ( isset( $_POST['evidences_border'] ) ? esc_textarea( $_POST['evidences_border'] ) : 0 );
		$meta['evidences_border_color'] = ( isset( $_POST['evidences_border_color'] ) ? esc_textarea( $_POST['evidences_border_color'] ) : '' );
		$meta['evidences_margin'] = ( isset( $_POST['evidences_margin'] ) ? esc_textarea( $_POST['evidences_margin'] ) : 0 );

		$meta['evidences_text_background'] = ( isset( $_POST['evidences_text_background'] ) ? esc_textarea( $_POST['evidences_text_background'] ) : '' );
		$meta['evidences_extra_background'] = ( isset( $_POST['evidences_extra_background'] ) ? esc_textarea( $_POST['evidences_extra_background'] ) : '' );

		$meta['evidences_pagination'] = isset( $_POST['evidences_pagination'] ) ? $_POST['evidences_pagination'] : 0;
		$meta['evidences_pagination_top'] = isset( $_POST['evidences_pagination_top'] ) ? $_POST['evidences_pagination_top'] : 0;
		$meta['evidences_pagination_right'] = isset( $_POST['evidences_pagination_right'] ) ? $_POST['evidences_pagination_right'] : 0;
		$meta['evidences_pagination_bottom'] = isset( $_POST['evidences_pagination_bottom'] ) ? $_POST['evidences_pagination_bottom'] : 0;
		$meta['evidences_pagination_left'] = isset( $_POST['evidences_pagination_left'] ) ? $_POST['evidences_pagination_left'] : 0;
		$meta['evidences_pagination_css'] = isset( $_POST['evidences_pagination_css'] ) ? htmlspecialchars( $_POST['evidences_pagination_css'] ) : '';
		$meta['evidences_pagination_element_css'] = isset( $_POST['evidences_pagination_element_css'] ) ? htmlspecialchars( $_POST['evidences_pagination_element_css'] ) : '';
		$meta['evidences_pagination_active_element_css'] = isset( $_POST['evidences_pagination_active_element_css'] ) ? htmlspecialchars( $_POST['evidences_pagination_active_element_css'] ) : '';
		
		$meta['evidences_css'] = ( isset( $_POST['evidences_css'] ) ? htmlspecialchars( $_POST['evidences_css'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the metaboxes to be used for the Evidences post type
	 *
	 * @since    0.0.1
	 */
	public function add_evidences_meta_boxes() {
		add_meta_box(
			'evidences_fields',
			__( 'Evidences', 'wordmedia' ),
			array( $this, 'render_evidences_meta_boxes' ),
			'evidences',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the Evidences metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_evidences_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$evidences_text = ! isset( $meta['evidences_text'][0] ) ? array() :  maybe_unserialize( $meta['evidences_text'][0] );
		$evidences_extra = ! isset( $meta['evidences_extra'][0] ) ? array() :  maybe_unserialize( $meta['evidences_extra'][0] );
		$evidences_link = ! isset( $meta['evidences_link'][0] ) ? array() :  maybe_unserialize( $meta['evidences_link'][0] );

		wp_nonce_field( basename( __FILE__ ), 'evidences_fields' ); ?>

		<a class="button-primary button" style="display: block; text-align: center; width: 80px;" id="add-evidence"><?php _e( 'Add', 'wordmedia' ); ?></a>

		<table class="form-table">

			<?php foreach ($evidences_text as $key => $value): ?>

				<tr data-evidences="<?php echo $key; ?>" style="border: 2px dotted #666666;">
					<td class="evidences_meta_box_td" rowspan="1">
						<label style="font-weight: bold;"><?= __( '#', 'wordmedia' ) . ( $key + 1 ) ?></label>
					</td>
					<td class="evidences_meta_box_td" colspan="2">
						<textarea type="text" name="evidences_text[<?php echo $key; ?>]" id="evidences_text[<?php echo $key; ?>]" rows="3" class="regular-text" required><?= $evidences_text[$key]; ?></textarea>
						<p class="description"><?php _e( 'Main text', 'wordmedia' ); ?></p>
						<textarea type="text" name="evidences_extra[<?php echo $key; ?>]" id="evidences_extra[<?php echo $key; ?>]" class="regular-text"><?= $evidences_extra[$key]; ?></textarea>
						<p class="description"><?php _e( 'Extra text', 'wordmedia' ); ?></p>
						<input type="text" name="evidences_link[<?php echo $key; ?>]" value="<?= isset( $evidences_link[$key] ) ? $evidences_link[$key] : ''; ?>" class="regular-text" />
						<p class="description"><?php _e( 'Link', 'wordmedia' ); ?></p>
					</td>
					<td class="evidences_meta_box_td" colspan="1">
						<a class="button-primary button remove-evidence" style="display: inline-block; text-align: center; width: 80px;"><?php _e( 'Remove', 'wordmedia' ); ?></a>
					</td>
				</tr>

			<?php endforeach ?>

		</table>

		<script>
			jQuery(document).ready(function($){
				$('a.remove-evidence').on('click', function(event){
					$(this).parent().parent().remove();
				});

				$('#add-evidence').on('click', function(event){
					event.preventDefault();

					var last = $('tr[data-evidences]').last();

					var id = last.length == 0 ? 0 : parseInt(last.attr('data-evidences')) + 1;

					var nextTr = $('<tr></tr>').attr('data-evidences', id).css('border', '2px dotted #666666');

					var numberTd = $('<td></td>').addClass('evidences_meta_box_td').attr('colspan', '1' );
					var numberLabel = $('<label></label>').css('font-weight', 'bold').text('<?php _e( '#', 'wordmedia' ); ?>' + (id+1));

					var mainTd = $('<td></td>').addClass('evidences_meta_box_td').attr('colspan', '2' );
					var titleInput = $('<textarea></textarea>').attr('type', 'text' ).attr('name', 'evidences_text[' + id + ']' ).attr('id', 'evidences_text[' + id + ']' ).addClass('regular-text').prop('required', true);
					var titleLabel = $('<p></p>').text('<?php _e( 'Main text', 'wordmedia' ); ?>').addClass('description');
					var extraInput = $('<textarea></textarea>').attr('type', 'text' ).attr('name', 'evidences_extra[' + id + ']' ).attr('id', 'evidences_extra[' + id + ']' ).addClass('regular-text').prop('required', true);
					var extraLabel = $('<p></p>').text('<?php _e( 'Extra text', 'wordmedia' ); ?>').addClass('description');
					var linkInput = $('<input></input>').attr('type', 'text' ).attr('name', 'evidences_link[' + id + ']' ).attr('id', 'evidences_link[' + id + ']' ).addClass('regular-text');
					var linkLabel = $('<p></p>').text('<?php _e( 'Text', 'wordmedia' ); ?>').addClass('description');

					var removeTd = $('<td></td>').addClass('evidences_meta_box_td').attr('colspan', '1' );
					var removeButton = $('<a></a>').css('display', 'inline-block').css('text-align', 'center').css('width', '80px').addClass('button').addClass('button-primary').addClass('remove-evidence').text('<?php _e( 'Remove', 'wordmedia' ); ?>');


					numberLabel.appendTo(numberTd);

					titleInput.appendTo(mainTd);
					titleLabel.appendTo(mainTd);
					extraInput.appendTo(mainTd);
					extraLabel.appendTo(mainTd);
					linkInput.appendTo(mainTd);
					linkLabel.appendTo(mainTd);

					removeButton.appendTo(removeTd);

					numberTd.appendTo(nextTr);
					mainTd.appendTo(nextTr);
					removeTd.appendTo(nextTr);				

					if (last.length == 0) {
						nextTr.appendTo('#evidences_fields table.form-table').first();
					}
					else {
						nextTr.appendTo(last.parent());
					}

					$('a.remove-evidence').on('click', function(event){
						$(this).parent().parent().remove();
					});
				});
			});
		</script>

	<?php }

    /**
	 * Save Evidences metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_evidences_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['evidences_fields'] ) || !wp_verify_nonce( $_POST['evidences_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}
		
		$meta = array();

		$meta['evidences_text'] = ( isset( $_POST['evidences_text'] ) ? $_POST['evidences_text'] : '' );
		$meta['evidences_extra'] = ( isset( $_POST['evidences_extra'] ) ? $_POST['evidences_extra'] : '' );
		$meta['evidences_link'] = ( isset( $_POST['evidences_link'] ) ? $_POST['evidences_link'] : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the Headline custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 * @since    0.0.1
	 */
	public function register_headline_post_type() {
		$labels = array(
			'name'               => __( 'Headlines', 'wordmedia' ),
			'singular_name'      => __( 'Headline', 'wordmedia' ),
			'add_new'            => __( 'Add Headline', 'wordmedia' ),
			'add_new_item'       => __( 'Add Headline', 'wordmedia' ),
			'edit_item'          => __( 'Edit Headline', 'wordmedia' ),
			'new_item'           => __( 'New Headline', 'wordmedia' ),
			'view_item'          => __( 'View Headline', 'wordmedia' ),
			'search_items'       => __( 'Search Headline', 'wordmedia' ),
			'not_found'          => __( 'No Headline found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Headline in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'headline', 'wordmedia' ) ),
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-image-filter',
			'show_in_rest'	  => true
		);

		$args = apply_filters( 'headline_post_type_args', $args );

		register_post_type( 'headline', $args );
	}

	/**
	 * Register the metaboxes to be used for the Headline post type
	 *
	 * @since    0.0.1
	 */
	public function add_headline_admin_meta_boxes() {

		global $current_user;
		if($current_user->roles[0] == 'administrator') {
			add_meta_box(
				'headline_admin_fields',
				__( 'Admin', 'wordmedia' ),
				array( $this, 'render_headline_admin_meta_boxes' ),
				'headline',
				'normal',
				'high'
			);
		}
	}

    /**
	 * The HTML for the Headline design metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_headline_admin_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$headline_width = ! isset( $meta['headline_width'][0] ) ? 1500 : $meta['headline_width'][0];
        $headline_height = ! isset( $meta['headline_height'][0] ) ? 600 : $meta['headline_height'][0];

		$headline_logo_pic = ! isset( $meta['headline_logo_pic'][0] ) ? '' : $meta['headline_logo_pic'][0];
    	$headline_logo_link = ! isset( $meta['headline_logo_link'][0] ) ? '' : $meta['headline_logo_link'][0];
    	$headline_logo_width = ! isset( $meta['headline_logo_width'][0] ) ? 0 : $meta['headline_logo_width'][0];
        $headline_logo_height = ! isset( $meta['headline_logo_height'][0] ) ? 0 : $meta['headline_logo_height'][0];
        $headline_logo_top = ! isset( $meta['headline_logo_top'][0] ) ? '' : $meta['headline_logo_top'][0];
        $headline_logo_right = ! isset( $meta['headline_logo_right'][0] ) ? '' : $meta['headline_logo_right'][0];
        $headline_logo_bottom = ! isset( $meta['headline_logo_bottom'][0] ) ? '' : $meta['headline_logo_bottom'][0];
        $headline_logo_left = ! isset( $meta['headline_logo_left'][0] ) ? '' : $meta['headline_logo_left'][0];

		$headline_css = ! isset( $meta['headline_css'][0] ) ? '' : $meta['headline_css'][0];

		$headline_logo_pic_src = $headline_logo_pic != '' ? wp_get_attachment_url( $headline_logo_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'headline_admin_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_width" style="font-weight: bold;"><?php _e( 'Headline Width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="headline_width" class="regular-text" value="<?php echo $headline_width ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_height" style="font-weight: bold;"><?php _e( 'Headline Height', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="headline_height" class="regular-text" value="<?php echo $headline_height ?>" /> px
				</td>
			</tr>

			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_logo_pic" style="font-weight: bold;"><?php _e( 'Logo', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="headline_logo_pic" id="headline_logo_pic" class="regular-text" value="<?php echo $headline_logo_pic ?>" required>
					<input id="upload_headline_logo_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_headline_logo_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="headline_logo_pic_preview" src="<?php echo $headline_logo_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>
			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_logo_link" style="font-weight: bold;"><?php _e( 'Logo link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="headline_logo_link" class="regular-text" value="<?= $headline_logo_link; ?>">
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_logo_width" style="font-weight: bold;"><?php _e( 'Logo Width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="headline_logo_width" class="regular-text" value="<?php echo $headline_logo_width ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_logo_height" style="font-weight: bold;"><?php _e( 'Logo Height', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="headline_logo_height" class="regular-text"value="<?php echo $headline_logo_height ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_logo_top" style="font-weight: bold;"><?php _e( 'Logo position top', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="headline_logo_top" class="regular-text" value="<?php echo $headline_logo_top ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_logo_right" style="font-weight: bold;"><?php _e( 'Logo position right', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="headline_logo_bottom" class="regular-text"value="<?php echo $headline_logo_bottom ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_logo_bottom" style="font-weight: bold;"><?php _e( 'Logo position bottom', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="headline_logo_bottom" class="regular-text" value="<?php echo $headline_logo_bottom ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_logo_left" style="font-weight: bold;"><?php _e( 'Logo position left', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="headline_logo_left" class="regular-text"value="<?php echo $headline_logo_left ?>" /> px
				</td>
			</tr>

			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="headline_css" class="regular-text" rows="10" cols="100"><?= $headline_css; ?></textarea>
				</td>
			</tr>

		</table>

		<script>
			jQuery(document).ready(function($){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = $( '#image_attachment_id' ).val() != '' && $( '#image_attachment_id' ).val() != undefined && $( '#image_attachment_id' ).val() != null ? $( '#image_attachment_id' ).val() : 0;
				$('#upload_headline_logo_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Headline Logo', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Headline Logo', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#headline_logo_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#headline_logo_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_headline_logo_pic').on('click', function ( event) {
					$( '#headline_logo_pic_preview' ).attr( 'src', '' );
					$( '#headline_logo_pic' ).val( '' );
				});
			});
		</script>


	<?php }

    /**
	 * Save Headline metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_headline_admin_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['headline_admin_fields'] ) || !wp_verify_nonce( $_POST['headline_admin_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['headline_width'] = ( isset( $_POST['headline_width'] ) ? esc_textarea( $_POST['headline_width'] ) : 1500 );
		$meta['headline_height'] = ( isset( $_POST['headline_height'] ) ? esc_textarea( $_POST['headline_height'] ) : 600 );

		$meta['headline_logo_pic'] = ( isset( $_POST['headline_logo_pic'] ) ? esc_textarea( $_POST['headline_logo_pic'] ) : '' );
		$meta['headline_logo_link'] = ( isset( $_POST['headline_logo_link'] ) ? esc_textarea( $_POST['headline_logo_link'] ) : '' );
		$meta['headline_logo_width'] = ( isset( $_POST['headline_logo_width'] ) ? esc_textarea( $_POST['headline_logo_width'] ) : 0 );
		$meta['headline_logo_height'] = ( isset( $_POST['headline_logo_height'] ) ? esc_textarea( $_POST['headline_logo_height'] ) : 0 );
        $meta['headline_logo_top'] = isset( $_POST['headline_logo_top'] ) ? $_POST['headline_logo_top'] : '';
    	$meta['headline_logo_right'] = isset( $_POST['headline_logo_right'] ) ? $_POST['headline_logo_right'] : '';
    	$meta['headline_logo_bottom'] = isset( $_POST['headline_logo_bottom'] ) ? $_POST['headline_logo_bottom'] : '';
    	$meta['headline_logo_left'] = isset( $_POST['headline_logo_left'] ) ? $_POST['headline_logo_left'] : '';

		$meta['headline_css'] = ( isset( $_POST['headline_css'] ) ? htmlspecialchars( $_POST['headline_css'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the metaboxes to be used for the Headline post type
	 *
	 * @since    0.0.1
	 */
	public function add_headline_meta_boxes() {
		add_meta_box(
			'headline_fields',
			__( 'Headline', 'wordmedia' ),
			array( $this, 'render_headline_meta_boxes' ),
			'headline',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the Headline design metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_headline_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$headline_title = ! isset( $meta['headline_title'][0] ) ? '' : $meta['headline_title'][0];
		$headline_pic = ! isset( $meta['headline_pic'][0] ) ? '' : $meta['headline_pic'][0];

		$headline_pic_src = $headline_pic != '' ? wp_get_attachment_url( $headline_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'headline_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="headline_title" class="regular-text" value="<?= $headline_title; ?>">
					<p class="description"><?php _e( 'Example: Concessionaria di Aosta', 'alb' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="headline_meta_box_td" colspan="1">
					<label for="headline_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="headline_pic" id="headline_pic" class="regular-text" value="<?php echo $headline_pic ?>" required>
					<input id="upload_headline_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_headline_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="headline_pic_preview" src="<?php echo $headline_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

		</table>

		<script>
			jQuery(document).ready(function($){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = $( '#image_attachment_id' ).val() != '' && $( '#image_attachment_id' ).val() != undefined && $( '#image_attachment_id' ).val() != null ? $( '#image_attachment_id' ).val() : 0;
				$('#upload_headline_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Headline Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Headline Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#headline_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#headline_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_headline_pic').on('click', function ( event) {
					$( '#headline_pic_preview' ).attr( 'src', '' );
					$( '#headline_pic' ).val( '' );
				});
			});
		</script>


	<?php }

    /**
	 * Save Headline metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_headline_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['headline_fields'] ) || !wp_verify_nonce( $_POST['headline_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['headline_title'] = ( isset( $_POST['headline_title'] ) ? esc_textarea( $_POST['headline_title'] ) : '' );
		$meta['headline_pic'] = ( isset( $_POST['headline_pic'] ) ? esc_textarea( $_POST['headline_pic'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the Footer custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 * @since    0.0.1
	 */
	public function register_footer_post_type() {
		$labels = array(
			'name'               => __( 'Footers', 'wordmedia' ),
			'singular_name'      => __( 'Footer', 'wordmedia' ),
			'add_new'            => __( 'Add Footer', 'wordmedia' ),
			'add_new_item'       => __( 'Add Footer', 'wordmedia' ),
			'edit_item'          => __( 'Edit Footer', 'wordmedia' ),
			'new_item'           => __( 'New Footer', 'wordmedia' ),
			'view_item'          => __( 'View Footer', 'wordmedia' ),
			'search_items'       => __( 'Search Footer', 'wordmedia' ),
			'not_found'          => __( 'No Footer found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Footer in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'footer', 'wordmedia' ) ),
			'menu_position'   => 30,
			'menu_icon'       => plugin_dir_url( __FILE__ ) . 'img/footer.png',
			'show_in_rest'	  => true,
			'capabilities' 	  => array(
				    'edit_post'          => 'update_core',
				    'read_post'          => 'update_core',
				    'delete_post'        => 'update_core',
				    'edit_posts'         => 'update_core',
				    'edit_others_posts'  => 'update_core',
				    'delete_posts'       => 'update_core',
				    'publish_posts'      => 'update_core',
				    'read_private_posts' => 'update_core'
			)
		);

		$args = apply_filters( 'footer_post_type_args', $args );

		register_post_type( 'footer', $args );
	}

	/**
	 * Register the metaboxes to be used for the Footer post type
	 *
	 * @since    0.0.1
	 */
	public function add_footer_meta_boxes() {
		add_meta_box(
			'footer_fields',
			__( 'Footer', 'wordmedia' ),
			array( $this, 'render_footer_meta_boxes' ),
			'footer',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the Footer design metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_footer_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$footer_css = ! isset( $meta['footer_css'][0] ) ? '' : $meta['footer_css'][0];

		wp_nonce_field( basename( __FILE__ ), 'footer_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="footer_meta_box_td" colspan="1">
					<label for="footer_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="footer_css" class="regular-text" rows="10" cols="100"><?= $footer_css; ?></textarea>
				</td>
			</tr>

		</table>


	<?php }

    /**
	 * Save Footer metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_footer_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['footer_fields'] ) || !wp_verify_nonce( $_POST['footer_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['footer_css'] = ( isset( $_POST['footer_css'] ) ? htmlspecialchars( $_POST['footer_css'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the Call to Action custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 * @since    0.0.1
	 */
	public function register_calltoaction_post_type() {
		$labels = array(
			'name'               => __( 'Calls to Action', 'wordmedia' ),
			'singular_name'      => __( 'Call to Action', 'wordmedia' ),
			'add_new'            => __( 'Add Call to Action', 'wordmedia' ),
			'add_new_item'       => __( 'Add Call to Action', 'wordmedia' ),
			'edit_item'          => __( 'Edit Call to Action', 'wordmedia' ),
			'new_item'           => __( 'New Call to Action', 'wordmedia' ),
			'view_item'          => __( 'View Call to Action', 'wordmedia' ),
			'search_items'       => __( 'Search Call to Action', 'wordmedia' ),
			'not_found'          => __( 'No Call to Action found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Call to Action in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'calltoaction', 'wordmedia' ) ),
			'menu_position'   => 30,
			'menu_icon'       => plugin_dir_url( __FILE__ ) . 'img/calltoaction.png',
			'show_in_rest'	  => true
		);

		$args = apply_filters( 'calltoaction_post_type_args', $args );

		register_post_type( 'calltoaction', $args );
	}

	/**
	 * Register the admin metaboxes to be used for the Call to Action post type
	 *
	 * @since    0.0.1
	 */
	public function add_calltoaction_admin_meta_boxes() {

		global $current_user;
		if($current_user->roles[0] == 'administrator') {
	        add_meta_box(
				'calltoaction_admin_fields',
				__( 'Admin', 'wordmedia' ),
				array( $this, 'render_calltoaction_admin_meta_boxes' ),
				'calltoaction',
				'normal',
				'high'
			);
	    }
	}

    /**
	 * The HTML for the Call to Action admin metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_calltoaction_admin_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

    	$calltoaction_width = ! isset( $meta['calltoaction_width'][0] ) ? '' : $meta['calltoaction_width'][0];
    	$calltoaction_height = ! isset( $meta['calltoaction_height'][0] ) ? '' : $meta['calltoaction_height'][0];

    	$calltoaction_text_size = ! isset( $meta['calltoaction_text_size'][0] ) ? '' : $meta['calltoaction_text_size'][0];
    	$calltoaction_text_color = ! isset( $meta['calltoaction_text_color'][0] ) ? '' : $meta['calltoaction_text_color'][0];
    	$calltoaction_text_weight = ! isset( $meta['calltoaction_hover_text_weight'][0] ) ? 400 : $meta['calltoaction_hover_text_weight'][0];
    	$calltoaction_text_decoration = ! isset( $meta['calltoaction_text_decoration'][0] ) ? 'none' : $meta['calltoaction_text_decoration'][0];
    	$calltoaction_bg_color = ! isset( $meta['calltoaction_bg_color'][0] ) ? '' : $meta['calltoaction_bg_color'][0];
    	$calltoaction_border_radius = ! isset( $meta['calltoaction_border_radius'][0] ) ? 0 : $meta['calltoaction_border_radius'][0];
    	$calltoaction_border_color = ! isset( $meta['calltoaction_border_color'][0] ) ? '' : $meta['calltoaction_border_color'][0];
    	$calltoaction_border_width = ! isset( $meta['calltoaction_border_width'][0] ) ? 0 : $meta['calltoaction_border_width'][0];

    	$calltoaction_hover_effect = ! isset( $meta['calltoaction_hover_effect'][0] ) ? 0 : $meta['calltoaction_hover_effect'][0];

    	$calltoaction_hover_duration = ! isset( $meta['calltoaction_hover_duration'][0] ) ? 0 : $meta['calltoaction_hover_duration'][0];
    	$calltoaction_hover_text_weight = ! isset( $meta['calltoaction_hover_text_weight'][0] ) ? 400 : $meta['calltoaction_hover_text_weight'][0];
    	$calltoaction_hover_text_decoration = ! isset( $meta['calltoaction_hover_text_decoration'][0] ) ? 'none' : $meta['calltoaction_hover_text_decoration'][0];
    	$calltoaction_hover_text_color = ! isset( $meta['calltoaction_hover_text_color'][0] ) ? '' : $meta['calltoaction_hover_text_color'][0];
    	$calltoaction_hover_border_color = ! isset( $meta['calltoaction_hover_border_color'][0] ) ? '' : $meta['calltoaction_hover_border_color'][0];
    	$calltoaction_hover_bg_color = ! isset( $meta['calltoaction_hover_bg_color'][0] ) ? '' : $meta['calltoaction_hover_bg_color'][0];

		$calltoaction_css = ! isset( $meta['calltoation_css'][0] ) ? '' : $meta['calltoation_css'][0];

		wp_nonce_field( basename( __FILE__ ), 'calltoaction_admin_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_width" style="font-weight: bold;"><?php _e( 'Button width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="calltoaction_width" class="regular-text" value="<?php echo $calltoaction_width ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_height" style="font-weight: bold;"><?php _e( 'Button height', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="calltoaction_height" class="regular-text" value="<?php echo $calltoaction_height ?>" /> px
				</td>
			</tr>

			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_text_size" style="font-weight: bold;"><?php _e( 'Text size', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" step="0.1" name="calltoaction_text_size" class="regular-text" value="<?php echo $calltoaction_text_size ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_text_color" style="font-weight: bold;"><?php _e( 'Text color', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="calltoaction_text_color" value="<?php echo $calltoaction_text_color ?>" />
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_text_weight" style="font-weight: bold;"><?php _e( 'Text weight', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="calltoaction_text_weight" class="regular-text" value="<?php echo $calltoaction_text_weight ?>" />
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_text_decoration" style="font-weight: bold;"><?php _e( 'Text decoration', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<select name="calltoaction_text_decoration">
						<option	value="none" <?php if ( $calltoaction_text_decoration == 'none' || $calltoaction_text_decoration == '' ) { echo 'selected'; } ?> ><?php _e( 'None', 'wordmedia' ); ?></option>
						<option	value="underline" <?php if ( $calltoaction_text_decoration == 'underline' ) { echo 'selected'; } ?> ><?php _e( 'Underline', 'wordmedia' ); ?></option>
						<option	value="overline" <?php if ( $calltoaction_text_decoration == 'overline' ) { echo 'selected'; } ?> ><?php _e( 'Overline', 'wordmedia' ); ?></option>
						<option	value="line-through" <?php if ( $calltoaction_text_decoration == 'line-through' ) { echo 'selected'; } ?> ><?php _e( 'Line through', 'wordmedia' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_bg_color" style="font-weight: bold;"><?php _e( 'Background color', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="calltoaction_bg_color" value="<?php echo $calltoaction_bg_color ?>" />
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_border_radius" style="font-weight: bold;"><?php _e( 'Border radius', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="calltoaction_border_radius" class="regular-text" value="<?php echo $calltoaction_border_radius ?>" /> px
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_border_color" style="font-weight: bold;"><?php _e( 'Border color', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="calltoaction_border_color" value="<?php echo $calltoaction_border_color ?>" />
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_border_width" style="font-weight: bold;"><?php _e( 'Border width', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="calltoaction_border_width" class="regular-text" value="<?php echo $calltoaction_border_width ?>" />
				</td>
			</tr>

			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_hover_effect" style="font-weight: bold;"><?php _e( 'Hover effect', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<select name="calltoaction_hover_effect">
						<option	value="0" <?php if ( $calltoaction_hover_effect == '0' || $calltoaction_hover_effect == '' ) { echo 'selected'; } ?> ><?php _e( 'None', 'wordmedia' ); ?></option>
						<option	value="1" <?php if ( $calltoaction_hover_effect == '1' ) { echo 'selected'; } ?> ><?php _e( 'Basic', 'wordmedia' ); ?></option>
						<option	value="2" <?php if ( $calltoaction_hover_effect == '2' ) { echo 'selected'; } ?> ><?php _e( 'Slide', 'wordmedia' ); ?></option>
					</select>
				</td>
			</tr>

			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_hover_duration" style="font-weight: bold;"><?php _e( 'Hover transition duration', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" step="0.1" name="calltoaction_hover_duration" class="regular-text" value="<?php echo $calltoaction_hover_duration ?>" /> s
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_hover_text_weight" style="font-weight: bold;"><?php _e( 'Hover text weight', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="number" name="calltoaction_hover_text_weight" class="regular-text" value="<?php echo $calltoaction_hover_text_weight ?>" />
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_hover_text_decoration" style="font-weight: bold;"><?php _e( 'Hover text decoration', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<select name="calltoaction_hover_text_decoration">
						<option	value="none" <?php if ( $calltoaction_hover_text_decoration == 'none' || $calltoaction_hover_text_decoration == '' ) { echo 'selected'; } ?> ><?php _e( 'None', 'wordmedia' ); ?></option>
						<option	value="underline" <?php if ( $calltoaction_hover_text_decoration == 'underline' ) { echo 'selected'; } ?> ><?php _e( 'Underline', 'wordmedia' ); ?></option>
						<option	value="overline" <?php if ( $calltoaction_hover_text_decoration == 'overline' ) { echo 'selected'; } ?> ><?php _e( 'Overline', 'wordmedia' ); ?></option>
						<option	value="line-through" <?php if ( $calltoaction_hover_text_decoration == 'line-through' ) { echo 'selected'; } ?> ><?php _e( 'Line through', 'wordmedia' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_hover_text_color" style="font-weight: bold;"><?php _e( 'Hover text color', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="calltoaction_hover_text_color" value="<?php echo $calltoaction_hover_text_color ?>" />
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_hover_border_color" style="font-weight: bold;"><?php _e( 'Hover border color', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="calltoaction_hover_border_color" value="<?php echo $calltoaction_hover_border_color ?>" />
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_hover_bg_color" style="font-weight: bold;"><?php _e( 'Hover background color', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="color" name="calltoaction_hover_bg_color" value="<?php echo $calltoaction_hover_bg_color ?>" />
				</td>
			</tr>

			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<textarea name="calltoaction_css" class="regular-text" rows="10" cols="100"><?= $calltoaction_css; ?></textarea>
				</td>
			</tr>

		</table>


	<?php }

    /**
	 * Save Call to Action metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_calltoaction_admin_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['calltoaction_admin_fields'] ) || !wp_verify_nonce( $_POST['calltoaction_admin_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['calltoaction_css'] = ( isset( $_POST['calltoaction_css'] ) ? $_POST['calltoaction_css'] : '' );

		$meta['calltoaction_width'] = ( isset( $_POST['calltoaction_width'] ) ? $_POST['calltoaction_width'] : '' );
		$meta['calltoaction_height'] = ( isset( $_POST['calltoaction_height'] ) ? $_POST['calltoaction_height'] : '' );

		$meta['calltoaction_text_size'] = ( isset( $_POST['calltoaction_text_size'] ) ? $_POST['calltoaction_text_size'] : '' );
		$meta['calltoaction_text_color'] = ( isset( $_POST['calltoaction_text_color'] ) ? $_POST['calltoaction_text_color'] : '' );
		$meta['calltoaction_text_weight'] = ( isset( $_POST['calltoaction_text_weight'] ) ? $_POST['calltoaction_text_weight'] : 400 );
		$meta['calltoaction_text_decoration'] = ( isset( $_POST['calltoaction_text_decoration'] ) ? $_POST['calltoaction_text_decoration'] : 'none' );
		$meta['calltoaction_bg_color'] = ( isset( $_POST['calltoaction_bg_color'] ) ? $_POST['calltoaction_bg_color'] : '' );
		$meta['calltoaction_border_radius'] = ( isset( $_POST['calltoaction_border_radius'] ) ? $_POST['calltoaction_border_radius'] : '' );
		$meta['calltoaction_border_color'] = ( isset( $_POST['calltoaction_border_color'] ) ? $_POST['calltoaction_border_color'] : '' );
		$meta['calltoaction_border_width'] = ( isset( $_POST['calltoaction_border_width'] ) ? $_POST['calltoaction_border_width'] : '' );

		$meta['calltoaction_hover_effect'] = ( isset( $_POST['calltoaction_hover_effect'] ) ? $_POST['calltoaction_hover_effect'] : 0 );

		$meta['calltoaction_hover_duration'] = ( isset( $_POST['calltoaction_hover_duration'] ) ? $_POST['calltoaction_hover_duration'] : '' );
		$meta['calltoaction_hover_text_weight'] = ( isset( $_POST['calltoaction_hover_text_weight'] ) ? $_POST['calltoaction_hover_text_weight'] : 400 );
		$meta['calltoaction_hover_text_decoration'] = ( isset( $_POST['calltoaction_hover_text_decoration'] ) ? $_POST['calltoaction_hover_text_decoration'] : 'none' );
		$meta['calltoaction_hover_text_color'] = ( isset( $_POST['calltoaction_hover_text_color'] ) ? $_POST['calltoaction_hover_text_color'] : '' );
		$meta['calltoaction_hover_border_color'] = ( isset( $_POST['calltoaction_hover_border_color'] ) ? $_POST['calltoaction_hover_border_color'] : '' );
		$meta['calltoaction_hover_bg_color'] = ( isset( $_POST['calltoaction_hover_bg_color'] ) ? $_POST['calltoaction_hover_bg_color'] : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the metaboxes to be used for the Call to Action post type
	 *
	 * @since    0.0.1
	 */
	public function add_calltoaction_meta_boxes() {
		add_meta_box(
			'calltoaction_fields',
			__( 'Call to Action', 'wordmedia' ),
			array( $this, 'render_calltoaction_meta_boxes' ),
			'calltoaction',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the Call to Action design metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_calltoaction_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$calltoaction_text = ! isset( $meta['calltoaction_text'][0] ) ? '' : $meta['calltoaction_text'][0];
    	$calltoaction_link = ! isset( $meta['calltoaction_link'][0] ) ? '' : $meta['calltoaction_link'][0];

		wp_nonce_field( basename( __FILE__ ), 'calltoaction_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_text" style="font-weight: bold;"><?php _e( 'Button text', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="text" name="calltoaction_text" class="regular-text" value="<?php echo $calltoaction_text ?>" />
				</td>
			</tr>
			<tr>
				<td class="calltoaction_meta_box_td" colspan="1">
					<label for="calltoaction_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?></label>
				</td>
				<td colspan="4">
					<input type="text" name="calltoaction_link" class="regular-text" value="<?php echo $calltoaction_link ?>" />
				</td>
			</tr>

		</table>


	<?php }

    /**
	 * Save Call to Action metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_calltoaction_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['calltoaction_fields'] ) || !wp_verify_nonce( $_POST['calltoaction_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['calltoaction_text'] = ( isset( $_POST['calltoaction_text'] ) ? $_POST['calltoaction_text'] : '' );
		$meta['calltoaction_link'] = ( isset( $_POST['calltoaction_link'] ) ? $_POST['calltoaction_link'] : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the Social Buttons custom post type.
	 *
	 * @since    0.0.1
	 * @link 	 http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function register_socialbuttons_post_type() {
		$labels = array(
			'name'               => __( 'Social Buttons', 'wordmedia' ),
			'singular_name'      => __( 'Social Buttons', 'wordmedia' ),
			'add_new'            => __( 'Add Social Buttons', 'wordmedia' ),
			'add_new_item'       => __( 'Add Social Buttons', 'wordmedia' ),
			'edit_item'          => __( 'Edit Social Buttons', 'wordmedia' ),
			'new_item'           => __( 'New Social Buttons', 'wordmedia' ),
			'view_item'          => __( 'View Social Buttons', 'wordmedia' ),
			'search_items'       => __( 'Search Social Buttons', 'wordmedia' ),
			'not_found'          => __( 'No Social Buttons found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Social Buttons in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'socialbuttons', 'wordmedia' ) ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-networking',
			'show_in_rest'	  => true
		);

		//filter for altering the args
		$args = apply_filters( 'socialbuttons_post_type_args', $args );

		register_post_type( 'socialbuttons', $args );
	}

	/**
	 * Register the admin metaboxes to be used for the Social buttons post type
	 *
	 * @since    0.0.1
	 */
	public function add_socialbuttons_admin_meta_boxes() {

		global $current_user;
		if($current_user->roles[0] == 'administrator') {
	        add_meta_box(
				'socialbuttons_admin_fields',
				__( 'Admin', 'wordmedia' ),
				array( $this, 'render_socialbuttons_admin_meta_boxes' ),
				'socialbuttons',
				'normal',
				'high'
			);
	    }
	}

    /**
	 * The HTML for the Social buttons admin metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_socialbuttons_admin_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$socialbuttons_width = ! isset( $meta['socialbuttons_width'][0] ) ? 30 : $meta['socialbuttons_width'][0];
		$socialbuttons_height = ! isset( $meta['socialbuttons_height'][0] ) ? 30 : $meta['socialbuttons_height'][0];
		$socialbuttons_margin = ! isset( $meta['socialbuttons_margin'][0] ) ? 20 : $meta['socialbuttons_margin'][0];

		$socialbuttons_css = ! isset( $meta['socialbuttons_css'][0] ) ? '' : htmlspecialchars( $meta['socialbuttons_css'][0] );

		wp_nonce_field( basename( __FILE__ ), 'socialbuttons_admin_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="socialbuttons_meta_box_td" colspan="3">
					<label for="socialbuttons_width" style="font-weight: bold;"><?php _e( 'Icon width', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="socialbuttons_width" class="regular-text" value="<?= $socialbuttons_width; ?>" required> px
					<p class="description"><?php _e( 'Example: 30', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="3">
					<label for="socialbuttons_height" style="font-weight: bold;"><?php _e( 'Icon height', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="socialbuttons_height" class="regular-text" value="<?= $socialbuttons_height; ?>" required> px
					<p class="description"><?php _e( 'Example: 30', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="3">
					<label for="socialbuttons_margin" style="font-weight: bold;"><?php _e( 'Margin between icons', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="socialbuttons_margin" class="regular-text" value="<?= $socialbuttons_margin; ?>" required> px
					<p class="description"><?php _e( 'Example: 20', 'wordmedia' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="socialbuttons_meta_box_td" colspan="1">
					<label for="socialbuttons_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="socialbuttons_css" class="regular-text" rows="10" cols="100"><?= $socialbuttons_css; ?></textarea>
				</td>
			</tr>

		</table>

	<?php }

    /**
	 * Save Social buttons admin metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_socialbuttons_admin_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['socialbuttons_admin_fields'] ) || !wp_verify_nonce( $_POST['socialbuttons_admin_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['socialbuttons_width'] = ( isset( $_POST['socialbuttons_width'] ) ? esc_textarea( $_POST['socialbuttons_width'] ) : 30 );
		$meta['socialbuttons_height'] = ( isset( $_POST['socialbuttons_height'] ) ? esc_textarea( $_POST['socialbuttons_height'] ) : 30 );
		$meta['socialbuttons_margin'] = ( isset( $_POST['socialbuttons_margin'] ) ? esc_textarea( $_POST['socialbuttons_margin'] ) : 20 );

		$meta['socialbuttons_css'] = ( isset( $_POST['socialbuttons_css'] ) ? htmlspecialchars( $_POST['socialbuttons_css'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the metaboxes to be used for the Social buttons post type
	 *
	 * @since    0.0.1
	 */
	public function add_socialbuttons_meta_boxes() {
		add_meta_box(
			'socialbuttons_fields',
			__( 'Social Buttons', 'wordmedia' ),
			array( $this, 'render_socialbuttons_meta_boxes' ),
			'socialbuttons',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the Social buttons metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_socialbuttons_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$socialbuttons_fb_pic = ! isset( $meta['socialbuttons_fb_pic'][0] ) ? '' : $meta['socialbuttons_fb_pic'][0];
		$socialbuttons_fb_link = ! isset( $meta['socialbuttons_fb_link'][0] ) ? '' : $meta['socialbuttons_fb_link'][0];
		$socialbuttons_yt_pic = ! isset( $meta['socialbuttons_yt_pic'][0] ) ? '' : $meta['socialbuttons_yt_pic'][0];
		$socialbuttons_yt_link = ! isset( $meta['socialbuttons_yt_link'][0] ) ? '' : $meta['socialbuttons_yt_link'][0];
		$socialbuttons_tw_pic = ! isset( $meta['socialbuttons_tw_pic'][0] ) ? '' : $meta['socialbuttons_tw_pic'][0];
		$socialbuttons_tw_link = ! isset( $meta['socialbuttons_tw_link'][0] ) ? '' : $meta['socialbuttons_tw_link'][0];
		$socialbuttons_ig_pic = ! isset( $meta['socialbuttons_ig_pic'][0] ) ? '' : $meta['socialbuttons_ig_pic'][0];
		$socialbuttons_ig_link = ! isset( $meta['socialbuttons_ig_link'][0] ) ? '' : $meta['socialbuttons_ig_link'][0];
		$socialbuttons_ta_pic = ! isset( $meta['socialbuttons_ta_pic'][0] ) ? '' : $meta['socialbuttons_ta_pic'][0];
		$socialbuttons_ta_link = ! isset( $meta['socialbuttons_ta_link'][0] ) ? '' : $meta['socialbuttons_ta_link'][0];
		$socialbuttons_ln_pic = ! isset( $meta['socialbuttons_ln_pic'][0] ) ? '' : $meta['socialbuttons_ln_pic'][0];
		$socialbuttons_ln_link = ! isset( $meta['socialbuttons_ln_link'][0] ) ? '' : $meta['socialbuttons_ln_link'][0];

		$socialbuttons_fb_pic_src = $socialbuttons_fb_pic != '' ? wp_get_attachment_url( $socialbuttons_fb_pic ) : '';
		$socialbuttons_yt_pic_src = $socialbuttons_yt_pic != '' ? wp_get_attachment_url( $socialbuttons_yt_pic ) : '';
		$socialbuttons_tw_pic_src = $socialbuttons_tw_pic != '' ? wp_get_attachment_url( $socialbuttons_tw_pic ) : '';
		$socialbuttons_ig_pic_src = $socialbuttons_ig_pic != '' ? wp_get_attachment_url( $socialbuttons_ig_pic ) : '';
		$socialbuttons_ta_pic_src = $socialbuttons_ta_pic != '' ? wp_get_attachment_url( $socialbuttons_ta_pic ) : '';
		$socialbuttons_ln_pic_src = $socialbuttons_ln_pic != '' ? wp_get_attachment_url( $socialbuttons_ln_pic ) : '';

		wp_nonce_field( basename( __FILE__ ), 'socialbuttons_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="socialbuttons_meta_box_td" colspan="3">
					<label for="socialbuttons_fb_link" style="font-weight: bold;"><?php _e( 'Facebook Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="socialbuttons_fb_link" class="regular-text" value="<?= $socialbuttons_fb_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); echo ' https://www.facebook.com/<b>VisaMultimedia-240882866021318</b>'?></p>
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="1">
					<label for="socialbuttons_fb_pic" style="font-weight: bold;"><?php _e( 'Facebook Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="socialbuttons_fb_pic" id="socialbuttons_fb_pic" class="regular-text" value="<?php echo $socialbuttons_fb_pic ?>">
					<input id="upload_socialbuttons_fb_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_socialbuttons_fb_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="socialbuttons_fb_pic_preview" src="<?php echo $socialbuttons_fb_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="3">
					<label for="socialbuttons_yt_link" style="font-weight: bold;"><?php _e( 'YouTube Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="socialbuttons_yt_link" class="regular-text" value="<?= $socialbuttons_yt_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); echo ' https://www.youtube.com/user/<b>visamultimedia</b>'?></p>
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="1">
					<label for="socialbuttons_yt_pic" style="font-weight: bold;"><?php _e( 'Youtube Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="socialbuttons_yt_pic" id="socialbuttons_yt_pic" class="regular-text" value="<?php echo $socialbuttons_yt_pic ?>">
					<input id="upload_socialbuttons_yt_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_socialbuttons_yt_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="socialbuttons_yt_pic_preview" src="<?php echo $socialbuttons_yt_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="3">
					<label for="socialbuttons_tw_link" style="font-weight: bold;"><?php _e( 'Twitter Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="socialbuttons_tw_link" class="regular-text" value="<?= $socialbuttons_tw_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); _e( '@<b>yourname</b>' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="1">
					<label for="socialbuttons_tw_pic" style="font-weight: bold;"><?php _e( 'Twitter Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="socialbuttons_tw_pic" id="socialbuttons_tw_pic" class="regular-text" value="<?php echo $socialbuttons_tw_pic ?>">
					<input id="upload_socialbuttons_tw_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_socialbuttons_tw_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="socialbuttons_tw_pic_preview" src="<?php echo $socialbuttons_tw_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="3">
					<label for="socialbuttons_ig_link" style="font-weight: bold;"><?php _e( 'Instagram Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="socialbuttons_ig_link" class="regular-text" value="<?= $socialbuttons_ig_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); _e( '@<b>yourname</b>' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="1">
					<label for="socialbuttons_ig_pic" style="font-weight: bold;"><?php _e( 'Instagram Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="socialbuttons_ig_pic" id="socialbuttons_ig_pic" class="regular-text" value="<?php echo $socialbuttons_ig_pic ?>">
					<input id="upload_socialbuttons_ig_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_socialbuttons_ig_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="socialbuttons_ig_pic_preview" src="<?php echo $socialbuttons_ig_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="3">
					<label for="socialbuttons_ta_link" style="font-weight: bold;"><?php _e( 'TripAdvisor Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="socialbuttons_ta_link" class="regular-text" value="<?= $socialbuttons_ta_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); _e( 'https://www.tripadvisor.it/<b>Restaurant_Review-g187865-d2144130-Reviews-La_Chaumiere-Courmayeur_Valle_d_Aosta.html</b>' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="1">
					<label for="socialbuttons_ta_pic" style="font-weight: bold;"><?php _e( 'TripAdvisor Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="socialbuttons_ta_pic" id="socialbuttons_ta_pic" class="regular-text" value="<?php echo $socialbuttons_ta_pic ?>">
					<input id="upload_socialbuttons_ta_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_socialbuttons_ta_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="socialbuttons_ta_pic_preview" src="<?php echo $socialbuttons_ta_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="3">
					<label for="socialbuttons_ln_link" style="font-weight: bold;"><?php _e( 'LinkedIn Id', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="socialbuttons_ln_link" class="regular-text" value="<?= $socialbuttons_ln_link; ?>">
					<p class="description"><?php _e( 'Example: ', 'wordmedia' ); _e( 'https://it.linkedin.com/in/<b>mario-rossi-01234567</b>' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="socialbuttons_meta_box_td" colspan="1">
					<label for="socialbuttons_ta_pic" style="font-weight: bold;"><?php _e( 'LinkedIn Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="socialbuttons_ln_pic" id="socialbuttons_ta_pic" class="regular-text" value="<?php echo $socialbuttons_ln_pic ?>">
					<input id="upload_socialbuttons_ln_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_socialbuttons_ln_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />
					<img id="socialbuttons_ln_pic_preview" src="<?php echo $socialbuttons_ln_pic_src ?>" style="max-height:50px;" />
				</td>
			</tr>

		</table>

		<script>
			jQuery(document).ready(function($){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = $( '#image_attachment_id' ).val() != '' && $( '#image_attachment_id' ).val() != undefined && $( '#image_attachment_id' ).val() != null ? $( '#image_attachment_id' ).val() : 0;

				$('#upload_socialbuttons_fb_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social Facebook Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social Facebook Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#socialbuttons_fb_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#socialbuttons_fb_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_socialbuttons_fb_pic').on('click', function ( event) {
					$( '#socialbuttons_fb_pic_preview' ).attr( 'src', '' );
					$( '#socialbuttons_fb_pic' ).val( '' );
				});
				$('#upload_socialbuttons_yt_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social YouTube Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social YouTube Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#socialbuttons_yt_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#socialbuttons_yt_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_socialbuttons_yt_pic').on('click', function ( event) {
					$( '#socialbuttons_yt_pic_preview' ).attr( 'src', '' );
					$( '#socialbuttons_yt_pic' ).val( '' );
				});
				$('#upload_socialbuttons_tw_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social Twitter Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social Twitter Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#socialbuttons_tw_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#socialbuttons_tw_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_socialbuttons_tw_pic').on('click', function ( event) {
					$( '#socialbuttons_tw_pic_preview' ).attr( 'src', '' );
					$( '#socialbuttons_tw_pic' ).val( '' );
				});
				$('#upload_socialbuttons_ig_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social Instagram Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social Instagram Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#socialbuttons_ig_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#socialbuttons_ig_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_socialbuttons_ig_pic').on('click', function ( event) {
					$( '#socialbuttons_ig_pic_preview' ).attr( 'src', '' );
					$( '#socialbuttons_ig_pic' ).val( '' );
				});
				$('#upload_socialbuttons_ta_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social TripAdvisor Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social TripAdvisor Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#socialbuttons_ta_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#socialbuttons_ta_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_socialbuttons_ta_pic').on('click', function ( event) {
					$( '#socialbuttons_ta_pic_preview' ).attr( 'src', '' );
					$( '#socialbuttons_ta_pic' ).val( '' );
				});
				$('#upload_socialbuttons_ln_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: '<?php _e( 'Social LinkedIn Picture', 'wordmedia' ); ?>',
						button: {
							text: '<?php _e( 'Select', 'wordmedia' ); ?>',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == '<?php _e( 'Social LinkedIn Picture', 'wordmedia' ); ?>') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#socialbuttons_ln_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#socialbuttons_ln_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_socialbuttons_ln_pic').on('click', function ( event) {
					$( '#socialbuttons_ln_pic_preview' ).attr( 'src', '' );
					$( '#socialbuttons_ln_pic' ).val( '' );
				});
			});
		</script>

	<?php }

    /**
	 * Save Social buttons description metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_socialbuttons_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['socialbuttons_fields'] ) || !wp_verify_nonce( $_POST['socialbuttons_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['socialbuttons_fb_pic'] = ( isset( $_POST['socialbuttons_fb_pic'] ) ? esc_textarea( $_POST['socialbuttons_fb_pic'] ) : '' );
		$meta['socialbuttons_fb_link'] = ( isset( $_POST['socialbuttons_fb_link'] ) ? esc_textarea( $_POST['socialbuttons_fb_link'] ) : '' );
		$meta['socialbuttons_yt_pic'] = ( isset( $_POST['socialbuttons_yt_pic'] ) ? esc_textarea( $_POST['socialbuttons_yt_pic'] ) : '' );
		$meta['socialbuttons_yt_link'] = ( isset( $_POST['socialbuttons_yt_link'] ) ? esc_textarea( $_POST['socialbuttons_yt_link'] ) : '' );
		$meta['socialbuttons_tw_pic'] = ( isset( $_POST['socialbuttons_tw_pic'] ) ? esc_textarea( $_POST['socialbuttons_tw_pic'] ) : '' );
		$meta['socialbuttons_tw_link'] = ( isset( $_POST['socialbuttons_tw_link'] ) ? esc_textarea( $_POST['socialbuttons_tw_link'] ) : '' );
		$meta['socialbuttons_ig_pic'] = ( isset( $_POST['socialbuttons_ig_pic'] ) ? esc_textarea( $_POST['socialbuttons_ig_pic'] ) : '' );
		$meta['socialbuttons_ig_link'] = ( isset( $_POST['socialbuttons_ig_link'] ) ? esc_textarea( $_POST['socialbuttons_ig_link'] ) : '' );
		$meta['socialbuttons_ta_pic'] = ( isset( $_POST['socialbuttons_ta_pic'] ) ? esc_textarea( $_POST['socialbuttons_ta_pic'] ) : '' );
		$meta['socialbuttons_ta_link'] = ( isset( $_POST['socialbuttons_ta_link'] ) ? esc_textarea( $_POST['socialbuttons_ta_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the Special One custom post type.
	 *
	 * @since    0.0.1
	 * @link 	 http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function register_specialone_post_type() {
		$labels = array(
			'name'               => __( 'Special Ones', 'wordmedia' ),
			'singular_name'      => __( 'Special One', 'wordmedia' ),
			'add_new'            => __( 'Add Special One', 'wordmedia' ),
			'add_new_item'       => __( 'Add Special One', 'wordmedia' ),
			'edit_item'          => __( 'Edit Special One', 'wordmedia' ),
			'new_item'           => __( 'New Special One', 'wordmedia' ),
			'view_item'          => __( 'View Special One', 'wordmedia' ),
			'search_items'       => __( 'Search Special One', 'wordmedia' ),
			'not_found'          => __( 'No Special One found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Special One in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'specialone', 'wordmedia' ) ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-megaphone',
			'show_in_rest'	  => true
		);

		//filter for altering the args
		$args = apply_filters( 'specialone_post_type_args', $args );

		register_post_type( 'specialone', $args );
	}

	/**
	 * Register the metaboxes to be used for the Special One post type
	 *
	 * @since    0.0.1
	 */
	public function add_specialone_meta_boxes() {
		add_meta_box(
			'specialone_fields',
			__( 'Special One', 'wordmedia' ),
			array( $this, 'render_specialone_meta_boxes' ),
			'specialone',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the Special One buttons metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_specialone_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$specialone_css = ! isset( $meta['specialone_css'][0] ) ? '' : htmlspecialchars( $meta['specialone_css'][0] );

		wp_nonce_field( basename( __FILE__ ), 'specialone_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="specialone_meta_box_td" colspan="1">
					<label for="specialone_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<textarea name="specialone_css" class="regular-text" rows="10" cols="100"><?= $specialone_css; ?></textarea>
				</td>
			</tr>

		</table>

	<?php }

    /**
	 * Save Special One description metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_specialone_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['specialone_fields'] ) || !wp_verify_nonce( $_POST['specialone_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}


		$meta['specialone_css'] = ( isset( $_POST['specialone_css'] ) ? htmlspecialchars( $_POST['specialone_css'] ) : '' );


		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the Offer custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 * @since    0.0.1
	 */
	public function register_offer_post_type() {
		$labels = array(
			'name'               => __( 'Offers', 'wordmedia' ),
			'singular_name'      => __( 'Offer', 'wordmedia' ),
			'add_new'            => __( 'Add Offer', 'wordmedia' ),
			'add_new_item'       => __( 'Add Offer', 'wordmedia' ),
			'edit_item'          => __( 'Edit Offer', 'wordmedia' ),
			'new_item'           => __( 'New Offer', 'wordmedia' ),
			'view_item'          => __( 'View Offer', 'wordmedia' ),
			'search_items'       => __( 'Search Offer', 'wordmedia' ),
			'not_found'          => __( 'No Offer found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Offer in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'editor',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'offer', 'wordmedia' ) ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-flag',
			'show_in_rest'	  => true
		);

		//filter for altering the args
		$args = apply_filters( 'offer_post_type_args', $args );

		register_post_type( 'offer', $args );
	}

	/**
	 * Register the description metaboxes to be used for the offer post type
	 *
	 * @since    0.0.1
	 */
	public function add_offer_description_meta_boxes() {
		add_meta_box(
			'offer_description_fields',
			__( 'Offer Description', 'wordmedia' ),
			array( $this, 'render_offer_description_meta_boxes' ),
			'offer',
			'normal',
			'high'
		);
	}

   /**
	* The HTML for the offer description metaboxes
	*
	* @since    0.0.1
	*/
	function render_offer_description_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$offer_subtitle = ! isset( $meta['offer_subtitle'][0] ) ? '' : $meta['offer_subtitle'][0];
		$offer_text = ! isset( $meta['offer_text'][0] ) ? '' : $meta['offer_text'][0];
		$offer_link = ! isset( $meta['offer_link'][0] ) ? '' : $meta['offer_link'][0];

		wp_nonce_field( basename( __FILE__ ), 'offer_description_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="offer_meta_box_td" colspan="3">
					<label for="offer_subtitle" style="font-weight: bold;"><?php _e( 'Discount price', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="offer_subtitle" class="regular-text" value="<?= $offer_subtitle; ?>" required> €
					<p class="description"><?php _e( 'Example: 13.750', 'alb' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="offer_meta_box_td" colspan="3">
					<label for="offer_text" style="font-weight: bold;"><?php _e( 'List price', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="offer_text" class="regular-text" value="<?= $offer_text; ?>" required> €
					<p class="description"><?php _e( 'Example: 17.000', 'wordmedia' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="offer_meta_box_td" colspan="3">
					<label for="offer_link" style="font-weight: bold;"><?php _e( 'Link', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="offer_link" class="regular-text" value="<?= $offer_link; ?>" required>
					<p class="description"><?php _e( 'Example: http://www.visamultimedia.com', 'wordmedia' ); ?></p>
				</td>
			</tr>

		</table>

	<?php }

   /**
	* Save offer description metaboxes
	*
	* @since    0.0.1
	*/
	function save_offer_description_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['offer_description_fields'] ) || !wp_verify_nonce( $_POST['offer_description_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['offer_subtitle'] = ( isset( $_POST['offer_subtitle'] ) ? esc_textarea( $_POST['offer_subtitle'] ) : '' );
		$meta['offer_text'] = ( isset( $_POST['offer_text'] ) ? esc_textarea( $_POST['offer_text'] ) : '' );
		$meta['offer_link'] = ( isset( $_POST['offer_link'] ) ? esc_textarea( $_POST['offer_link'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the time metaboxes to be used for the offer post type
	 *
	 * @since    0.0.1
	 */
	public function add_offer_time_meta_boxes() {
		add_meta_box(
			'offer_time_fields',
			__( 'Offer Dates', 'wordmedia' ),
			array( $this, 'render_offer_time_meta_boxes' ),
			'offer',
			'normal',
			'high'
		);
	}

   /**
	* The HTML for the offer time metaboxes
	*
	* @since    0.0.1
	*/
	function render_offer_time_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$offer_start_display = ! isset( $meta['offer_start_display'][0] ) ? '' : $meta['offer_start_display'][0];
		$offer_end_display = ! isset( $meta['offer_end_display'][0] ) ? '' : $meta['offer_end_display'][0];
		$offer_start_date = ! isset( $meta['offer_start_date'][0] ) ? '' : $meta['offer_start_date'][0];
		$offer_end_date = ! isset( $meta['offer_end_date'][0] ) ? '' : $meta['offer_end_date'][0];

		wp_nonce_field( basename( __FILE__ ), 'offer_time_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="offer_meta_box_td" colspan="1">
					<label for="offer_start_display" style="font-weight: bold;"><?php _e( 'Start Display Date', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="date" name="offer_start_display" class="regular-text" value="<?php echo date( 'Y-m-d', $offer_start_display ); ?>" required>
				</td>
			</tr>

			<tr>
				<td class="offer_meta_box_td" colspan="1">
					<label for="offer_end_display" style="font-weight: bold;"><?php _e( 'End Display Date', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="date" name="offer_end_display" class="regular-text" value="<?php echo date( 'Y-m-d', $offer_end_display ); ?>" required>
				</td>
			</tr>

		</table>

	<?php }

   /**
	* Save offer time metaboxes
	*
	* @since    0.0.1
	*/
	function save_offer_time_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['offer_time_fields'] ) || !wp_verify_nonce( $_POST['offer_time_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['offer_start_display'] = ( isset( $_POST['offer_start_display'] ) ? strtotime( esc_textarea( $_POST['offer_start_display'] ) ) : '' );
		$meta['offer_end_display'] = ( isset( $_POST['offer_end_display'] ) ? strtotime( esc_textarea( $_POST['offer_end_display'] ) ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the picture metabox to be used for the offer post type
	 *
	 * @since    0.0.1
	 */
	public function add_offer_pic_meta_boxes() {
		add_meta_box(
			'offer_pic_fields',
			__( 'Offer Pictures', 'wordmedia' ),
			array( $this, 'render_offer_pic_meta_boxes' ),
			'offer',
			'normal',
			'high'
		);
	}

	/**
	* The HTML for the offer picture metabox
	*
	* @since    0.0.1
	*/
	public function render_offer_pic_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );

		$offer_pic_1 = ! isset( $meta['offer_pic_1'][0] ) ? '' : $meta['offer_pic_1'][0];
		$offer_pic_2 = ! isset( $meta['offer_pic_2'][0] ) ? '' : $meta['offer_pic_2'][0];
		
		$offer_pic_1_src = $offer_pic_1 != '' ? wp_get_attachment_url( $offer_pic_1 ) : '';
		$offer_pic_2_src = $offer_pic_2 != '' ? wp_get_attachment_url( $offer_pic_2 ) : '';

		wp_nonce_field( basename( __FILE__ ), 'offer_pic_fields' );
		
		?>

		<table class="form-table">

			<tr>
				<td class="offer_meta_box_td" colspan="1">
					<label for="offer_pic_1" style="font-weight: bold;"><?php _e( 'Picture 1', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="offer_pic_1" id="offer_pic_1" class="regular-text" value="<?php echo $offer_pic_1 ?>" required>
					<input id="upload_pic_1" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<img id="offer_pic_1_preview" src="<?php echo $offer_pic_1_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="offer_meta_box_td" colspan="1">
					<label for="offer_pic_2" style="font-weight: bold;"><?php _e( 'Picture 2', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="hidden" name="offer_pic_2" id="offer_pic_2" class="regular-text" value="<?php echo $offer_pic_2 ?>" required>
					<input id="upload_pic_2" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<img id="offer_pic_2_preview" src="<?php echo $offer_pic_2_src ?>" style="max-height:200px;" />
				</td>
			</tr>

		</table>

		<script>
			jQuery(document).ready(function($){
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = $( '#image_attachment_id' ).val() != '' ? $( '#image_attachment_id' ).val() : 0;

				$('#upload_pic_1').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Immagine 1',
						button: {
							text: 'Scegli',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == 'Immagine 1') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#offer_pic_1_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#offer_pic_1' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_pic_1').on('click', function ( event) {
					$( '#offer_pic_1_preview' ).attr( 'src', '' );
					$( '#offer_pic_1' ).val( '' );
				});

				$('#upload_pic_2').on('click', function( event ){
					event.preventDefault();				
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Immagine 2',
						button: {
							text: 'Scegli',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == 'Immagine 2') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#offer_pic_2_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#offer_pic_2' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}					
					});
						file_frame.open();
				});
				$('#remove_pic_2').on('click', function ( event) {
					$( '#offer_pic_2_preview' ).attr( 'src', '' );
					$( '#offer_pic_2' ).val( '' );
				});
			});
		</script>

	<?php }

	/**
	* Save offer pic metaboxes
	*
	* @since    0.0.1
	*/
	function save_offer_pic_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['offer_pic_fields'] ) || !wp_verify_nonce( $_POST['offer_pic_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['offer_pic_1'] = ( isset( $_POST['offer_pic_1'] ) ? esc_textarea( $_POST['offer_pic_1'] ) : '' );
		$meta['offer_pic_2'] = ( isset( $_POST['offer_pic_2'] ) ? esc_textarea( $_POST['offer_pic_2'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the Map custom post type.
	 *
	 * @since    0.0.1
	 * @link 	 http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function register_map_post_type() {
		$labels = array(
			'name'               => __( 'Maps', 'wordmedia' ),
			'singular_name'      => __( 'Map', 'wordmedia' ),
			'add_new'            => __( 'Add Map', 'wordmedia' ),
			'add_new_item'       => __( 'Add Map', 'wordmedia' ),
			'edit_item'          => __( 'Edit Map', 'wordmedia' ),
			'new_item'           => __( 'New Map', 'wordmedia' ),
			'view_item'          => __( 'View Map', 'wordmedia' ),
			'search_items'       => __( 'Search Map', 'wordmedia' ),
			'not_found'          => __( 'No Map found', 'wordmedia' ),
			'not_found_in_trash' => __( 'No Map in the trash', 'wordmedia' ),
		);

		$supports = array(
			'title',
			'revisions'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => __( 'map', 'wordmedia' ) ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-welcome-view-site',
			'show_in_rest'	  => true
		);

		//filter for altering the args
		$args = apply_filters( 'map_post_type_args', $args );

		register_post_type( 'map', $args );
	}

	/**
	 * Register the admin metaboxes to be used for the map post type
	 *
	 * @since    0.0.1
	 */
	public function add_map_admin_meta_boxes() {
		global $current_user;
		if($current_user->roles[0] == 'administrator') {
	        add_meta_box(
				'map_admin_fields',
				__( 'Admin', 'wordmedia' ),
				array( $this, 'render_map_admin_meta_boxes' ),
				'map',
				'normal',
				'high'
			);
	    }
	}

	/**
	 * The HTML for the map metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_map_admin_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		$option = get_post_custom( $post->ID );

		$map_width = ! isset( $meta['map_width'][0] ) ? '' : $meta['map_width'][0];
		$map_height = ! isset( $meta['map_height'][0] ) ? '' : $meta['map_height'][0];

		$map_css = ! isset( $meta['map_css'][0] ) ? '' : htmlspecialchars( $meta['map_css'][0] );

		wp_nonce_field( basename( __FILE__ ), 'map_admin_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="map_meta_box_td" colspan="1">
					<label for="map_width" style="font-weight: bold;"><?php _e( 'Map width', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="2">
					<input type="number" name="map_width" class="regular-text" value="<?= $map_width; ?>" required> px
					<p class="description"><?php _e( 'Example: 800', 'wordmedia' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="map_meta_box_td" colspan="1">
					<label for="map_height" style="font-weight: bold;"><?php _e( 'Map height', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="2">
					<input type="number" name="map_height" class="regular-text" value="<?= $map_height; ?>" required> px
					<p class="description"><?php _e( 'Example: 450', 'wordmedia' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="map_meta_box_td" colspan="1">
					<label for="map_css" style="font-weight: bold;"><?php _e( 'CSS', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="2">
					<textarea name="map_css" class="regular-text" rows="10" cols="100"><?= $map_css; ?></textarea>
				</td>
			</tr>

		</table>

	<?php }

    /**
	 * Save map admin metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_map_admin_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['map_admin_fields'] ) || !wp_verify_nonce( $_POST['map_admin_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['map_width'] = ( isset( $_POST['map_width'] ) ? esc_textarea( $_POST['map_width'] ) : '' );
		$meta['map_height'] = ( isset( $_POST['map_height'] ) ? esc_textarea( $_POST['map_height'] ) : '' );

		$meta['map_css'] = ( isset( $_POST['map_css'] ) ? htmlspecialchars( $_POST['map_css'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the metaboxes to be used for the map post type
	 *
	 * @since    0.0.1
	 */
	public function add_map_meta_boxes() {
		add_meta_box(
			'map_fields',
			__( 'Map', 'wordmedia' ),
			array( $this, 'render_map_meta_boxes' ),
			'map',
			'normal',
			'high'
		);
	}

    /**
	 * The HTML for the map metaboxes
	 *
	 * @since    0.0.1
	 */
	function render_map_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		
		$map_title = ! isset( $meta['map_title'][0] ) ? '' : htmlspecialchars( $meta['map_title'][0] );
		$map_pic = ! isset( $meta['map_pic'][0] ) ? '' : $meta['map_pic'][0];

		$map_center_lat = ! isset( $meta['map_center_lat'][0] ) ? '' : $meta['map_center_lat'][0];
		$map_center_lon = ! isset( $meta['map_center_lon'][0] ) ? '' : $meta['map_center_lon'][0];

		$map_zoom = ! isset( $meta['map_zoom'][0] ) ? 8 : $meta['map_zoom'][0];

		$map_pic_src = $map_pic != '' ? wp_get_attachment_url( $map_pic ) : '';

		$map_points_title = ! isset( $meta['map_points_title'][0] ) ? array() :  maybe_unserialize( $meta['map_points_title'][0] );
		$map_points_text = ! isset( $meta['map_points_text'][0] ) ? array() :  maybe_unserialize( $meta['map_points_text'][0] );
		$map_points_lat = ! isset( $meta['map_points_lat'][0] ) ? array() :  maybe_unserialize( $meta['map_points_lat'][0] );
		$map_points_lon = ! isset( $meta['map_points_lon'][0] ) ? array() :  maybe_unserialize( $meta['map_points_lon'][0] );

		wp_nonce_field( basename( __FILE__ ), 'map_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="map_meta_box_td" colspan="1">
					<label for="map_title" style="font-weight: bold;"><?php _e( 'Title', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="2">
					<input type="text" name="map_title" class="regular-text" value="<?= $map_title; ?>" />
				</td>
			</tr>

			<tr >
				<td class="map_meta_box_td" colspan="1">
					<label for="headline_pic" style="font-weight: bold;"><?php _e( 'Picture', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="2">
					<input type="hidden" name="map_pic" id="map_pic" class="regular-text" value="<?php echo $map_pic ?>" required>
					<input id="upload_map_pic" type="button" class="button" value="<?php _e( 'Upload picture', 'wordmedia' ); ?>" />
					<input id="remove_map_pic" type="button" class="button" value="<?php _e( 'Remove picture', 'wordmedia' ); ?>" />					
				</td>					
				<td colspan="2">
					<img id="map_pic_preview" src="<?php echo $map_pic_src ?>" style="max-height:200px;" />
				</td>
			</tr>

			<tr>
				<td class="map_meta_box_td" colspan="1">
					<label style="font-weight: bold;"><?php _e( 'Map Center', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="1">
					<input type="number" step="0.00000001" max="90" min="-90" name="map_center_lat" class="regular-text" value="<?= $map_center_lat; ?>" required>
					<p class="description"><?php _e( 'Latitude', 'wordmedia' ); ?></p>
				</td>
				<td colspan="1">
					<input type="number" step="0.00000001" max="180" min="-180" name="map_center_lon" class="regular-text"  value="<?= $map_center_lon; ?>" required>
					<p class="description"><?php _e( 'Longitude', 'wordmedia' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="map_meta_box_td" colspan="1">
					<label for="map_zoom" style="font-weight: bold;"><?php _e( 'Zoom', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="2">
					<input name="map_zoom" type="range" min="1" max="20" step="1" class="regular-text" value="<?= $map_zoom; ?>" />
				</td>
			</tr>

			<tr>
				<td class="map_meta_box_td" colspan="1">
					<label style="font-weight: bold;"><?php _e( 'Points', 'wordmedia' ); ?>
					</label>
				</td>
				<td colspan="1">
					<a class="button-primary button" style="display: block; text-align: center; width: 80px;" id="add-map-point"><?php _e( 'Add point', 'wordmedia' ); ?></a>
				</td>
			</tr>			

			<?php foreach ($map_points_title as $key => $value): ?>

				<tr data-map-points="<?php echo $key; ?>" style="border: 2px dotted #666666;">
					<td class="map_meta_box_td" rowspan="1">
						<label style="font-weight: bold;"><?= __( '#', 'wordmedia' ) . ( $key + 1 ) ?></label>
					</td>
					<td class="map_meta_box_td" colspan="1">
						<input type="text" name="map_points_title[<?php echo $key; ?>]" id="map_points_title[<?php echo $key; ?>]" value="<?= $map_points_title[$key]; ?>" class="regular-text" required />
						<p class="description"><?php _e( 'Title ', 'wordmedia' ); ?></p>
						<input type="text" name="map_points_text[<?php echo $key; ?>]" id="map_points_text[<?php echo $key; ?>]" value="<?= $map_points_text[$key]; ?>" class="regular-text" />
						<p class="description"><?php _e( 'Text ', 'wordmedia' ); ?></p>
					</td>
					<td class="map_meta_box_td" colspan="1">
						<input type="number" step="0.00000001" max="90" min="-90" name="map_points_lat[<?php echo $key; ?>]" value="<?= isset( $map_points_lat[$key] ) ? $map_points_lat[$key] : ''; ?>" class="regular-text" required />
						<p class="description"><?php _e( 'Latitude', 'wordmedia' ); ?></p>
						<input type="number" step="0.00000001" max="180" min="-180" name="map_points_lon[<?php echo $key; ?>]" value="<?= isset( $map_points_lon[$key] ) ? $map_points_lon[$key] : ''; ?>" class="regular-text" required />
						<p class="description"><?php _e( 'Longitude', 'wordmedia' ); ?></p>
					</td>
					<td class="map_meta_box_td" colspan="1">
						<a class="button-primary button remove-map-point" style="display: inline-block; text-align: center; width: 80px;"><?php _e( 'Remove', 'wordmedia' ); ?></a>
					</td>
				</tr>

			<?php endforeach ?>

		</table>

		<script>
			$(document).ready(function() {

				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id;
				var set_to_post_id = $( '#image_attachment_id' ).val() != '' ? $( '#image_attachment_id' ).val() : 0;

				$('#upload_map_pic').on('click', function( event ){
					event.preventDefault();
					wp.media.model.settings.post.id = set_to_post_id;
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Map',
						button: {
							text: 'Scegli',
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						if (file_frame.options.title == 'Map') {
							var attachment = file_frame.state().get('selection').first().toJSON();
							$( '#map_pic_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
							$( '#map_pic' ).val( attachment.id );
							wp.media.model.settings.post.id = wp_media_post_id;
						}
					});
						file_frame.open();
				});
				$('#remove_map_pic').on('click', function ( event) {
					$( '#map_pic_preview' ).attr( 'src', '' );
					$( '#map_pic' ).val( '' );
				});

				$('a.remove-map-point').on('click', function(event){
					$(this).parent().parent().remove();
				});

				$('#add-map-point').on('click', function(event){
					event.preventDefault();

					var last = $('tr[data-map-points]').last();

					var id = last.length == 0 ? 0 : parseInt(last.attr('data-map-points')) + 1;

					var nextTr = $('<tr></tr>').attr('data-map-points', id).css('border', '2px dotted #666666');

					var numberTd = $('<td></td>').addClass('map_meta_box_td').attr('colspan', '1' );
					var numberLabel = $('<label></label>').attr('for', 'map_points_title[' + id + ']' ).css('font-weight', 'bold').text('<?php _e( '#', 'wordmedia' ); ?>' + (id+1));

					var textTd = $('<td></td>').addClass('map_meta_box_td').attr('colspan', '1' );
					
					var titleInput = $('<input></input>').attr('type', 'text' ).attr('name', 'map_points_title[' + id + ']' ).attr('id', 'map_points_title[' + id + ']' ).addClass('regular-text').prop('required', true);
					var titleLabel = $('<p></p>').text('<?php _e( 'Title', 'wordmedia' ); ?>').addClass('description');
					var textInput = $('<input></input>').attr('type', 'text' ).attr('name', 'map_points_text[' + id + ']' ).attr('id', 'map_points_text[' + id + ']' ).addClass('regular-text');
					var textLabel = $('<p></p>').text('<?php _e( 'Text', 'wordmedia' ); ?>').addClass('description');
					
					var coordTd = $('<td></td>').addClass('map_meta_box_td').attr('colspan', '1' );
					
					var latInput = $('<input></input>').attr('type', 'number' ).attr('step', '0.00000001' ).attr('max', '90' ).attr('min', '-90' ).attr('name', 'map_points_lat[' + id + ']' ).attr('id', 'map_points_lat[' + id + ']' ).addClass('regular-text').prop('required', true);
					var latLabel = $('<p></p>').text('<?php _e( 'Latitude', 'wordmedia' ); ?>').addClass('description');
					var lonInput = $('<input></input>').attr('type', 'number' ).attr('step', '0.00000001' ).attr('max', '180' ).attr('min', '-180' ).attr('name', 'map_points_lon[' + id + ']' ).attr('id', 'map_points_lon[' + id + ']' ).addClass('regular-text').prop('required', true);
					var lonLabel = $('<p></p>').text('<?php _e( 'Longitude', 'wordmedia' ); ?>').addClass('description');
					
					var removeTd = $('<td></td>').addClass('map_meta_box_td').attr('colspan', '1' );
					var removeButton = $('<a></a>').css('display', 'inline-block').css('text-align', 'center').css('width', '80px').addClass('button').addClass('button-primary').addClass('remove-map-point').text('<?php _e( 'Remove', 'wordmedia' ); ?>');

					numberLabel.appendTo(numberTd);
					
					titleInput.appendTo(textTd);
					titleLabel.appendTo(textTd);
					
					textInput.appendTo(textTd);
					textLabel.appendTo(textTd);
					
					latInput.appendTo(coordTd);
					latLabel.appendTo(coordTd);
					
					lonInput.appendTo(coordTd);
					lonLabel.appendTo(coordTd);

					removeButton.appendTo(removeTd);

					numberTd.appendTo(nextTr);
					textTd.appendTo(nextTr);
					coordTd.appendTo(nextTr);
					removeTd.appendTo(nextTr);					

					if (last.length == 0) {
						nextTr.appendTo('#map_fields table.form-table').first();
					}
					else {
						nextTr.appendTo(last.parent());
					}
				});

		});
		</script>

	<?php }

    /**
	 * Save  description metaboxes
	 *
	 * @since    0.0.1
	 */
	function save_map_meta_boxes( $post_id ) {

		global $post;

		if ( !isset( $_POST['map_fields'] ) || !wp_verify_nonce( $_POST['map_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['map_title'] = ( isset( $_POST['map_title'] ) ? htmlspecialchars( $_POST['map_title'] ) : '' );
		$meta['map_pic'] = ( isset( $_POST['map_pic'] ) ? esc_textarea( $_POST['map_pic'] ) : '' );	

		$meta['map_width'] = ( isset( $_POST['map_width'] ) ? esc_textarea( $_POST['map_width'] ) : '' );
		$meta['map_height'] = ( isset( $_POST['map_height'] ) ? esc_textarea( $_POST['map_height'] ) : '' );

		$meta['map_center_lat'] = ( isset( $_POST['map_center_lat'] ) ? esc_textarea( $_POST['map_center_lat'] ) : '' );
		$meta['map_center_lon'] = ( isset( $_POST['map_center_lon'] ) ? esc_textarea( $_POST['map_center_lon'] ) : '' );

		$meta['map_zoom'] = ( isset( $_POST['map_zoom'] ) ? esc_textarea( $_POST['map_zoom'] ) : '' );

		$meta['map_css'] = ( isset( $_POST['map_css'] ) ? htmlspecialchars( $_POST['map_css'] ) : '' );

		$meta['map_points_title'] = ( isset( $_POST['map_points_title'] ) ? $_POST['map_points_title'] : '' );
		$meta['map_points_text'] = ( isset( $_POST['map_points_text'] ) ? $_POST['map_points_text'] : '' );
		$meta['map_points_lat'] = ( isset( $_POST['map_points_lat'] ) ? $_POST['map_points_lat'] : '' );
		$meta['map_points_lon'] = ( isset( $_POST['map_points_lon'] ) ? $_POST['map_points_lon'] : '' );


		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

}
