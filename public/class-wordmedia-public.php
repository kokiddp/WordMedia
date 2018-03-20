<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.elk-lab.com/
 * @since      0.0.1
 *
 * @package    Wordmedia
 * @subpackage Wordmedia/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wordmedia
 * @subpackage Wordmedia/public
 * @author     Gabriele Coquillard <gabriele@elk-lab.com>
 */
class Wordmedia_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( 'youslider', plugin_dir_url( __FILE__ ) . 'css/youslider.min.css', array(), '20180214', 'all' );
		wp_enqueue_style( 'lightgallery', plugin_dir_url( __FILE__ ) . 'css/lightgallery.min.css', array(), '1.6.6', 'all' );
		wp_enqueue_style( 'lg-transitions', plugin_dir_url( __FILE__ ) . 'css/lg-transitions.min.css', array( 'lightgallery' ), '1.6.6', 'all' );
		wp_enqueue_style( 'lg-fb-comment-box', plugin_dir_url( __FILE__ ) . 'css/lg-fb-comment-box.min.css', array( 'lightgallery' ), '1.6.6', 'all' );
		wp_enqueue_style( 'mmenu', plugin_dir_url( __FILE__ ) . 'css/jquery.mmenu.7.0.3.all.css', array(), '7.0.3', 'all' );
		//wp_enqueue_style( 'mmenu', plugin_dir_url( __FILE__ ) . 'css/jquery.mmenu.6.1.8.all.css', array(), '6.1.8', 'all' );
		wp_enqueue_style( 'jquery-paginate', plugin_dir_url( __FILE__ ) . 'css/jquery.paginate.css', array(), '0.3.0', 'all' );
		wp_enqueue_style( 'qtip', plugin_dir_url( __FILE__ ) . 'css/jquery.qtip.min.css', array(), '3.0.3', 'all' );
		wp_enqueue_style( 'jquery-gray', plugin_dir_url( __FILE__ ) . 'css/gray.min.css', array(), '1.6.0', 'all' );
		//wp_enqueue_style( 'jquery-datepick', plugin_dir_url( __FILE__ ) . 'css/jquery.datepick.css', array(), '5.1.1', 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wordmedia-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {

		
		wp_deregister_script('jquery');
		//wp_deregister_script('jquery-migrate');
        
        //wp_enqueue_script('jquery', plugin_dir_url( __FILE__ ) . 'js/jquery-3.3.1.min.js', array(), '3.3.1', false);
        //wp_enqueue_script('jquery', plugin_dir_url( __FILE__ ) . 'js/jquery-2.2.4.min.js', array(), '2.2.4', false);
        wp_enqueue_script('jquery', plugin_dir_url( __FILE__ ) . 'js/jquery-1.12.4.min.js', array(), '1.12.4', false);
        //wp_enqueue_script('jquery-migrate', plugin_dir_url( __FILE__ ) . 'js/jquery-migrate-3.0.1.min.js', array( 'jquery' ), '3.0.1', false);
        //wp_enqueue_script('jquery-migrate', plugin_dir_url( __FILE__ ) . 'js/jquery-migrate-1.4.1.min.js', array( 'jquery' ), '1.4.1', false);
        
		wp_enqueue_script( 'modernizr', plugin_dir_url( __FILE__ ) . 'js/modernizr-custom.js', array(), '3.6.0', true );

		wp_enqueue_script( 'jcarousel', plugin_dir_url( __FILE__ ) . 'js/jquery.jcarousel.js', array( 'jquery', 'modernizr' ), '0.3.5', true );
		wp_enqueue_script( 'jcarousel-swipe', plugin_dir_url( __FILE__ ) . 'js/jquery.jcarousel-swipe.js', array( 'jquery', 'jcarousel' ), '0.3.4', true );

		//wp_enqueue_script( 'youslider', plugin_dir_url( __FILE__ ) . 'js/jquery.youslider.min.js', array( 'jquery', 'modernizr' ), '20180214', true );
		//wp_enqueue_script( 'youslider-plugins', plugin_dir_url( __FILE__ ) . 'js/jquery.youslider-plugins.min.js', array( 'jquery', 'youslider' ), '20180214', true );

		wp_enqueue_script( 'hammer', plugin_dir_url( __FILE__ ) . 'js/hammer.min.js', array(  ), '2.0.8', true );
		wp_enqueue_script( 'mmenu', plugin_dir_url( __FILE__ ) . 'js/jquery.mmenu.7.0.3.all.js', array( 'jquery', 'hammer' ), '7.0.3', true );

		wp_enqueue_script( 'lightgallery', plugin_dir_url( __FILE__ ) . 'js/lightgallery-all.min.js', array( 'jquery', 'modernizr' ), '1.6.7', true );

		wp_enqueue_script( 'jquery-paginate', plugin_dir_url( __FILE__ ) . 'js/jquery.paginate.js', array( 'jquery' ), '0.3.0', true );

		wp_enqueue_script('qtip', plugin_dir_url( __FILE__ ) . 'js/jquery.qtip.min.js', array('jquery'), '3.0.3', true);

		wp_enqueue_script('jquery-doubletaptogo', plugin_dir_url( __FILE__ ) . 'js/jquery.dcd.doubletaptogo.min.js', array('jquery'), '3.0.2', true);

		wp_enqueue_script('jquery-gray', plugin_dir_url( __FILE__ ) . 'js/jquery.gray.min.js', array('jquery'), '1.6.0', true);

		wp_enqueue_script('google-maps-api', 'https://maps.google.com/maps/api/js?key=AIzaSyBUMkiEI1D1czvQq_ibGHsl4xK3CJRtAFo', array(), '', true);
		wp_enqueue_script('gmaps', plugin_dir_url( __FILE__ ) . 'js/gmaps.min.js', array('google-maps-api'), '0.4.25', true);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wordmedia-public.js', array( 'jquery', 'jquery-migrate', 'jcarousel', 'lightgallery', 'jquery-paginate', 'modernizr', 'qtip', 'jquery-doubletaptogo' ), time()/*$this->version*/, true );

	}

	/**
	 * Add the shortcodes for the public area.
	 *
	 * @since    0.0.1
	 */
	public function add_shortcodes() {

		add_shortcode( 'wordmedia_video', array( $this, 'wordmedia_video_shortcode' ) );
		add_shortcode( 'wordmedia_slider', array( $this, 'wordmedia_slider_shortcode' ) );
		add_shortcode( 'wordmedia_powergallery', array( $this, 'wordmedia_powergallery_shortcode' ) );
		add_shortcode( 'wordmedia_menubar', array( $this, 'wordmedia_menubar_shortcode' ) );
		add_shortcode( 'wordmedia_evidences', array( $this, 'wordmedia_evidences_shortcode' ) );
		add_shortcode( 'wordmedia_headline', array( $this, 'wordmedia_headline_shortcode' ) );
		add_shortcode( 'wordmedia_footer', array( $this, 'wordmedia_footer_shortcode' ) );
		add_shortcode( 'wordmedia_calltoaction', array( $this, 'wordmedia_calltoaction_shortcode' ) );
		add_shortcode( 'wordmedia_socialbuttons', array( $this, 'wordmedia_socialbuttons_shortcode' ) );
		add_shortcode( 'wordmedia_map', array( $this, 'wordmedia_map_shortcode' ) );

		add_shortcode( 'wordmedia_specialone', array( $this, 'wordmedia_specialone_shortcode' ) );
		add_shortcode( 'wordmedia_offer', array( $this, 'wordmedia_offer_shortcode' ) );
		add_shortcode( 'wordmedia_offer_slider', array( $this, 'wordmedia_offer_slider_shortcode' ) );

	}

	/**
	 * Register the Template for the Video post type.
	 *
	 * @since    0.0.1
	 */
	public function register_video_template( $template ) {
	    $post_types = array( 'video' );
	    if ( is_singular( $post_types ) )
	        $template = plugin_dir_path( __FILE__ ) . 'partials/wordmedia-public-single-video-display.php';
	    return $template;
	}

	/**
	 * Define the shortcode for the Video post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_video_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'video',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

        	$video_id = ! isset( $meta['video_id'][0] ) ? '' : $meta['video_id'][0];
			$video_pic = ! isset( $meta['video_pic'][0] ) ? '' : $meta['video_pic'][0];
			$video_title = ! isset( $meta['video_title'][0] ) ? '' : $meta['video_title'][0];
			$video_text = ! isset( $meta['video_text'][0] ) ? '' : $meta['video_text'][0];

			$video_width = ! isset( $meta['video_width'][0] ) ? 99999 : $meta['video_width'][0];

			$video_css = ! isset( $meta['video_css'][0] ) ? '' : $meta['video_css'][0];

			$video_pic_src = $video_pic != '' ? wp_get_attachment_url( $video_pic ) : '';
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<div class="video-placeholder" id="video-<?php echo get_the_ID(); ?>"></div>
				<h3 class="video-title"><?php echo $video_title; ?></h3>
				<p class="video-text"><?php echo $video_text; ?></p>

				<style>
					div#video-<?= get_the_ID(); ?> {
						background-image: url(<?= $video_pic_src; ?>);
						background-size: cover;
						background-repeat: no-repeat;
						max-width: <?= $video_width; ?>px;
						width: 100%;
						padding-top: 56.25%; /* 16:9 Aspect Ratio */
					}
					iframe#video-<?php echo get_the_ID(); ?> {
						padding-top: 0;
					}
					div.video-placeholder + p.video-title { margin-top: 15px!important; }
					iframe + p.video-title { margin-top: 10px!important; }
					<?= $video_css; ?>
				</style>

				<script>
					jQuery(document).ready(function($){
						$('#video-<?php echo get_the_ID(); ?>').on('click', function( event ) {
							event.preventDefault();
							$.getScript("https://www.youtube.com/iframe_api").done( function() {						
								var player;
								var divHeight = $('#video-<?php echo get_the_ID(); ?>').outerHeight();
								var divWidth =$('#video-<?php echo get_the_ID(); ?>').width();
								function readyYoutube(){
								    if ( (typeof YT !== "undefined") && YT && YT.Player )
								    {
								        player = new YT.Player('video-<?php echo get_the_ID(); ?>', {
								        	videoId: '<?php echo $video_id; ?>',
								        	height: divHeight,
								        	width: divWidth,
								        	events: {
							            		'onReady': onPlayerReady
								        		}
							        		});
								    }
								    else
								    {
								        setTimeout(readyYoutube, 100);
								    }
								}
							    function onPlayerReady(event) {
							        event.target.playVideo();
							    }
							    readyYoutube();
							});
					    })
					});
			    </script>

            <?php endwhile;
        wp_reset_postdata(); ?>

    	<?php $video = ob_get_clean();

    	return $video;

    	}

    }

    /**
	 * Define the shortcode for the Slider post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_slider_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'slider',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

        	$slider_width = ! isset( $meta['slider_width'][0] ) ? '' : $meta['slider_width'][0];
        	$slider_height = ! isset( $meta['slider_height'][0] ) ? '' : $meta['slider_height'][0];

        	$slider_pauseonhover = ! isset( $meta['slider_pauseonhover'][0] ) ? 0 : $meta['slider_pauseonhover'][0];
			$slider_speed = ! isset( $meta['slider_speed'][0] ) ? 0 : $meta['slider_speed'][0];

        	$slider_autoplay = ! isset( $meta['slider_autoplay'][0] ) ? 0 : $meta['slider_autoplay'][0];
        	$slider_autoplay_delta = ! isset( $meta['slider_autoplay_delta'][0] ) ? 0 : $meta['slider_autoplay_delta'][0];

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
        	$slider_text_top = ! isset( $meta['slider_text_top'][0] ) ? '' : $meta['slider_text_top'][0];
	    	$slider_text_right = ! isset( $meta['slider_text_right'][0] ) ? '' : $meta['slider_text_right'][0];
	    	$slider_text_bottom = ! isset( $meta['slider_text_bottom'][0] ) ? '' : $meta['slider_text_bottom'][0];
	    	$slider_text_left = ! isset( $meta['slider_text_left'][0] ) ? '' : $meta['slider_text_left'][0];
        	$slider_text_css = ! isset( $meta['slider_text_css'][0] ) ? '' : $meta['slider_text_css'][0];

        	$slider_pic_width = ! isset( $meta['slider_pic_width'][0] ) ? 0 : $meta['slider_pic_width'][0];
        	$slider_pic_height = ! isset( $meta['slider_pic_height'][0] ) ? 0 : $meta['slider_pic_height'][0];
        	$slider_pic_top = ! isset( $meta['slider_pic_top'][0] ) ? '' : $meta['slider_pic_top'][0];
	    	$slider_pic_right = ! isset( $meta['slider_pic_right'][0] ) ? '' : $meta['slider_pic_right'][0];
	    	$slider_pic_bottom = ! isset( $meta['slider_pic_bottom'][0] ) ? '' : $meta['slider_pic_bottom'][0];
	    	$slider_pic_left = ! isset( $meta['slider_pic_left'][0] ) ? '' : $meta['slider_pic_left'][0];
        	$slider_pic_css = ! isset( $meta['slider_pic_css'][0] ) ? '' : $meta['slider_pic_css'][0];

			$slider_css = ! isset( $meta['slider_css'][0] ) ? '' : $meta['slider_css'][0];

        	$slide1_title = ! isset( $meta['slide1_title'][0] ) ? '' : $meta['slide1_title'][0];
        	$slide1_subtitle = ! isset( $meta['slide1_subtitle'][0] ) ? '' : $meta['slide1_subtitle'][0];
			$slide1_pic = ! isset( $meta['slide1_pic'][0] ) ? '' : $meta['slide1_pic'][0];
			$slide1_bg = ! isset( $meta['slide1_bg'][0] ) ? '' : $meta['slide1_bg'][0];
			$slide1_link = ! isset( $meta['slide1_link'][0] ) ? '' : $meta['slide1_link'][0];
			$slide2_title = ! isset( $meta['slide2_title'][0] ) ? '' : $meta['slide2_title'][0];
        	$slide2_subtitle = ! isset( $meta['slide2_subtitle'][0] ) ? '' : $meta['slide2_subtitle'][0];
			$slide2_pic = ! isset( $meta['slide2_pic'][0] ) ? '' : $meta['slide2_pic'][0];
			$slide2_bg = ! isset( $meta['slide2_bg'][0] ) ? '' : $meta['slide2_bg'][0];
			$slide2_link = ! isset( $meta['slide2_link'][0] ) ? '' : $meta['slide2_link'][0];
			$slide3_title = ! isset( $meta['slide3_title'][0] ) ? '' : $meta['slide3_title'][0];
        	$slide3_subtitle = ! isset( $meta['slide3_subtitle'][0] ) ? '' : $meta['slide3_subtitle'][0];
			$slide3_pic = ! isset( $meta['slide3_pic'][0] ) ? '' : $meta['slide3_pic'][0];
			$slide3_bg = ! isset( $meta['slide3_bg'][0] ) ? '' : $meta['slide3_bg'][0];
			$slide3_link = ! isset( $meta['slide3_link'][0] ) ? '' : $meta['slide3_link'][0];
			$slide4_title = ! isset( $meta['slide4_title'][0] ) ? '' : $meta['slide4_title'][0];
        	$slide4_subtitle = ! isset( $meta['slide4_subtitle'][0] ) ? '' : $meta['slide4_subtitle'][0];
			$slide4_pic = ! isset( $meta['slide4_pic'][0] ) ? '' : $meta['slide4_pic'][0];
			$slide4_bg = ! isset( $meta['slide4_bg'][0] ) ? '' : $meta['slide4_bg'][0];
			$slide4_link = ! isset( $meta['slide4_link'][0] ) ? '' : $meta['slide4_link'][0];
			$slide5_title = ! isset( $meta['slide5_title'][0] ) ? '' : $meta['slide5_title'][0];
        	$slide5_subtitle = ! isset( $meta['slide5_subtitle'][0] ) ? '' : $meta['slide5_subtitle'][0];
			$slide5_pic = ! isset( $meta['slide5_pic'][0] ) ? '' : $meta['slide5_pic'][0];
			$slide5_bg = ! isset( $meta['slide5_bg'][0] ) ? '' : $meta['slide5_bg'][0];
			$slide5_link = ! isset( $meta['slide5_link'][0] ) ? '' : $meta['slide5_link'][0];
			$slide6_title = ! isset( $meta['slide6_title'][0] ) ? '' : $meta['slide6_title'][0];
        	$slide6_subtitle = ! isset( $meta['slide6_subtitle'][0] ) ? '' : $meta['slide6_subtitle'][0];
			$slide6_pic = ! isset( $meta['slide6_pic'][0] ) ? '' : $meta['slide6_pic'][0];
			$slide6_bg = ! isset( $meta['slide6_bg'][0] ) ? '' : $meta['slide6_bg'][0];
			$slide6_link = ! isset( $meta['slide6_link'][0] ) ? '' : $meta['slide6_link'][0];
			$slide7_title = ! isset( $meta['slide7_title'][0] ) ? '' : $meta['slide7_title'][0];
        	$slide7_subtitle = ! isset( $meta['slide7_subtitle'][0] ) ? '' : $meta['slide7_subtitle'][0];
			$slide7_pic = ! isset( $meta['slide7_pic'][0] ) ? '' : $meta['slide7_pic'][0];
			$slide7_bg = ! isset( $meta['slide7_bg'][0] ) ? '' : $meta['slide7_bg'][0];
			$slide7_link = ! isset( $meta['slide7_link'][0] ) ? '' : $meta['slide7_link'][0];
			$slide8_title = ! isset( $meta['slide8_title'][0] ) ? '' : $meta['slide8_title'][0];
        	$slide8_subtitle = ! isset( $meta['slide8_subtitle'][0] ) ? '' : $meta['slide8_subtitle'][0];
			$slide8_pic = ! isset( $meta['slide8_pic'][0] ) ? '' : $meta['slide8_pic'][0];
			$slide8_bg = ! isset( $meta['slide8_bg'][0] ) ? '' : $meta['slide8_bg'][0];
			$slide8_link = ! isset( $meta['slide8_link'][0] ) ? '' : $meta['slide8_link'][0];
			$slide9_title = ! isset( $meta['slide9_title'][0] ) ? '' : $meta['slide9_title'][0];
        	$slide9_subtitle = ! isset( $meta['slide9_subtitle'][0] ) ? '' : $meta['slide9_subtitle'][0];
			$slide9_pic = ! isset( $meta['slide9_pic'][0] ) ? '' : $meta['slide9_pic'][0];
			$slide9_bg = ! isset( $meta['slide9_bg'][0] ) ? '' : $meta['slide9_bg'][0];
			$slide9_link = ! isset( $meta['slide9_link'][0] ) ? '' : $meta['slide9_link'][0];
			$slide10_title = ! isset( $meta['slide10_title'][0] ) ? '' : $meta['slide10_title'][0];
        	$slide10_subtitle = ! isset( $meta['slide10_subtitle'][0] ) ? '' : $meta['slide10_subtitle'][0];
			$slide10_pic = ! isset( $meta['slide10_pic'][0] ) ? '' : $meta['slide10_pic'][0];
			$slide10_bg = ! isset( $meta['slide10_bg'][0] ) ? '' : $meta['slide10_bg'][0];
			$slide10_link = ! isset( $meta['slide10_link'][0] ) ? '' : $meta['slide10_link'][0];
		
			$slide1_pic_src = $slide1_pic != '' ? wp_get_attachment_url( $slide1_pic ) : '';
			$slide2_pic_src = $slide2_pic != '' ? wp_get_attachment_url( $slide2_pic ) : '';
			$slide3_pic_src = $slide3_pic != '' ? wp_get_attachment_url( $slide3_pic ) : '';
			$slide4_pic_src = $slide4_pic != '' ? wp_get_attachment_url( $slide4_pic ) : '';
			$slide5_pic_src = $slide5_pic != '' ? wp_get_attachment_url( $slide5_pic ) : '';
			$slide6_pic_src = $slide6_pic != '' ? wp_get_attachment_url( $slide6_pic ) : '';
			$slide7_pic_src = $slide7_pic != '' ? wp_get_attachment_url( $slide7_pic ) : '';
			$slide8_pic_src = $slide8_pic != '' ? wp_get_attachment_url( $slide8_pic ) : '';
			$slide9_pic_src = $slide9_pic != '' ? wp_get_attachment_url( $slide9_pic ) : '';
			$slide10_pic_src = $slide10_pic != '' ? wp_get_attachment_url( $slide10_pic ) : '';
			$slide1_bg_src = $slide1_bg != '' ? wp_get_attachment_url( $slide1_bg ) : '';
			$slide2_bg_src = $slide2_bg != '' ? wp_get_attachment_url( $slide2_bg ) : '';
			$slide3_bg_src = $slide3_bg != '' ? wp_get_attachment_url( $slide3_bg ) : '';
			$slide4_bg_src = $slide4_bg != '' ? wp_get_attachment_url( $slide4_bg ) : '';
			$slide5_bg_src = $slide5_bg != '' ? wp_get_attachment_url( $slide5_bg ) : '';
			$slide6_bg_src = $slide6_bg != '' ? wp_get_attachment_url( $slide6_bg ) : '';
			$slide7_bg_src = $slide7_bg != '' ? wp_get_attachment_url( $slide7_bg ) : '';
			$slide8_bg_src = $slide8_bg != '' ? wp_get_attachment_url( $slide8_bg ) : '';
			$slide9_bg_src = $slide9_bg != '' ? wp_get_attachment_url( $slide9_bg ) : '';
			$slide10_bg_src = $slide10_bg != '' ? wp_get_attachment_url( $slide10_bg ) : '';

			$slider_logo_pic_src = $slider_logo_pic != '' ? wp_get_attachment_url( $slider_logo_pic ) : '';
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<div id="slider-<?= get_the_ID(); ?>" class="jcarousel-slider">
					<ul>
						<?php if ( $slide1_title != '' ): ?>
							<li>
								<div class="slide" id="slide1">
									<div class="slidebg" style="background-image: url(<?php echo $slide1_bg_src; ?>);"></div>
									<a<?php if ($slide1_link != ''): ?> href="<?php echo $slide1_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide1_title ) ); ?></a>
									<a<?php if ($slide1_link != ''): ?> href="<?php echo $slide1_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide1_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide1_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide1_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>
						
						<?php if ( $slide2_title != '' ): ?>
							<li>
								<div class="slide" id="slide2">
									<div class="slidebg" style="background-image: url(<?php echo $slide2_bg_src; ?>);"></div>
									<a<?php if ($slide2_link != ''): ?> href="<?php echo $slide2_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide2_title ) ); ?></a>
									<a<?php if ($slide2_link != ''): ?> href="<?php echo $slide2_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide2_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide2_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide2_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>

						<?php if ( $slide3_title != '' ): ?>
							<li>
								<div class="slide" id="slide3">
									<div class="slidebg" style="background-image: url(<?php echo $slide3_bg_src; ?>);"></div>
									<a<?php if ($slide3_link != ''): ?> href="<?php echo $slide3_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide3_title ) ); ?></a>
									<a<?php if ($slide3_link != ''): ?> href="<?php echo $slide3_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide3_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide3_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide3_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>

						<?php if ( $slide4_title != '' ): ?>
							<li>
								<div class="slide" id="slide4">
									<div class="slidebg" style="background-image: url(<?php echo $slide4_bg_src; ?>);"></div>
									<a<?php if ($slide4_link != ''): ?> href="<?php echo $slide4_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide4_title ) ); ?></a>
									<a<?php if ($slide4_link != ''): ?> href="<?php echo $slide4_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide4_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide4_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide4_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>

						<?php if ( $slide5_title != '' ): ?>
							<li>
								<div class="slide" id="slide5">
									<div class="slidebg" style="background-image: url(<?php echo $slide5_bg_src; ?>);"></div>
									<a<?php if ($slide5_link != ''): ?> href="<?php echo $slide5_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide5_title ) ); ?></a>
									<a<?php if ($slide5_link != ''): ?> href="<?php echo $slide5_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide5_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide5_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide5_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>

						<?php if ( $slide6_title != '' ): ?>
							<li>
								<div class="slide" id="slide6">
									<div class="slidebg" style="background-image: url(<?php echo $slide6_bg_src; ?>);"></div>
									<a<?php if ($slide6_link != ''): ?> href="<?php echo $slide8_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide6_title ) ); ?></a>
									<a<?php if ($slide6_link != ''): ?> href="<?php echo $slide8_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide6_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide6_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide6_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>

						<?php if ( $slide7_title != '' ): ?>
							<li>
								<div class="slide" id="slide7">
									<div class="slidebg" style="background-image: url(<?php echo $slide7_bg_src; ?>);"></div>
									<a<?php if ($slide7_link != ''): ?> href="<?php echo $slide6_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide7_title ) ); ?></a>
									<a<?php if ($slide7_link != ''): ?> href="<?php echo $slide6_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide7_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide7_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide7_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>

						<?php if ( $slide8_title != '' ): ?>
							<li>
								<div class="slide" id="slide8">
									<div class="slidebg" style="background-image: url(<?php echo $slide8_bg_src; ?>);"></div>
									<a<?php if ($slide8_link != ''): ?> href="<?php echo $slide8_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide8_title ) ); ?></a>
									<a<?php if ($slide8_link != ''): ?> href="<?php echo $slide8_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide8_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide8_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide8_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>

						<?php if ( $slide9_title != '' ): ?>
							<li>
								<div class="slide" id="slide9">
									<div class="slidebg" style="background-image: url(<?php echo nl2br( esc_html( $slide9_bg_src ) ); ?>);"></div>
									<a<?php if ($slide9_link != ''): ?> href="<?php echo $slide9_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide9_title ) ); ?></a>
									<a<?php if ($slide9_link != ''): ?> href="<?php echo $slide9_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide9_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide9_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide9_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>

						<?php if ( $slide10_title != '' ): ?>
							<li>
								<div class="slide" id="slide10">
									<div class="slidebg" style="background-image: url(<?php echo $slide10_bg_src; ?>);"></div>
									<a<?php if ($slide10_link != ''): ?> href="<?php echo $slide10_link; ?>" target="_blank"<?php endif ?> class="slidetitle"><?php echo nl2br( esc_html( $slide10_title ) ); ?></a>
									<a<?php if ($slide10_link != ''): ?> href="<?php echo $slide10_link; ?>" target="_blank"<?php endif ?> class="slidesubtitle"><?php echo nl2br( esc_html( $slide10_subtitle ) ); ?></a>
									<p class="slidetext"><?php echo nl2br( esc_html( $slide10_text ) ); ?></p>
									<div class="slidepic" style="background-image: url(<?php echo $slide10_pic_src; ?>);"></div>
								</div>
							</li>
						<?php endif ?>

					</ul>

					<?php if ( $slider_next == 1 ): ?>
						<a id="slider-next"><?= $slider_next_text != '' ? $slider_next_text : _e( 'Next', 'wordmedia' ) ?></a>
					<?php endif ?>

					<?php if ( $slider_logo_pic != '' ): ?>
						<a id="slider_logo" style="background: url(<?= $slider_logo_pic_src ?>)" href="<?= $slider_logo_link != '' ? $slider_logo_link : '#' ?>"></a>
					<?php endif ?>

					<?php if ( $slider_pagination == 1 ): ?>
						<div id="slider-pagination" class="jcarousel-slider-pagination"></div>
					<?php endif ?>

					<style>
						#slider-<?= get_the_ID(); ?>.jcarousel-slider { position: relative; overflow: hidden; }
						#slider-<?= get_the_ID(); ?>.jcarousel-slider > ul { width: 10000em; position: relative; list-style: none; margin: 0; padding: 0; }
						#slider-<?= get_the_ID(); ?>.jcarousel-slider > ul > li { float: left; }

						<?php if ( $slider_next == 1 ): ?>
							#slider-<?= get_the_ID(); ?> #slider-next {
								display: block;
								position: absolute;
								<?php if ($slider_next_top != ''): ?>
									top: <?php echo esc_html( $slider_next_top ); ?>px;
								<?php endif ?>
								<?php if ($slider_next_right != ''): ?>
									right: <?php echo esc_html( $slider_next_right ); ?>px;
								<?php endif ?>
								<?php if ($slider_next_bottom != ''): ?>
									bottom: <?php echo esc_html( $slider_next_bottom ); ?>px;
								<?php endif ?>
								<?php if ($slider_next_left != ''): ?>
									left: <?php echo esc_html( $slider_next_left ); ?>px;
								<?php endif ?>
								cursor: pointer;
							}
						<?php endif ?>
						<?php if ( $slider_pagination == 1 ): ?>
							#slider-<?= get_the_ID(); ?> #slider-pagination { 
								display: block;
								position: absolute;
								<?php if ($slider_pagination_top != ''): ?>
									top: <?php echo esc_html( $slider_pagination_top ); ?>px;
								<?php endif ?>
								<?php if ($slider_pagination_left != ''): ?>
									left: <?php echo esc_html( $slider_pagination_left ); ?>px;
								<?php endif ?>
								<?php if ($slider_pagination_bottom != ''): ?>
									bottom: <?php echo esc_html( $slider_pagination_bottom ); ?>px;
								<?php endif ?>
								<?php if ($slider_pagination_right != ''): ?>
									right: <?php echo esc_html( $slider_pagination_right ); ?>px;
								<?php endif ?>
								<?php if ( $slider_pagination_css != '' ) { echo $slider_pagination_css; } ?> 
							}
							#slider-<?= get_the_ID(); ?> #slider-pagination a { <?php if ( $slider_pagination_element_css != '' ) { echo $slider_pagination_element_css; } ?> }
							#slider-<?= get_the_ID(); ?> #slider-pagination a.active { <?php if ( $slider_pagination_active_element_css != '' ) { echo $slider_pagination_active_element_css; } ?> }
						<?php endif ?>

						<?php if ( $slider_logo_pic != '' ): ?>
							#slider_logo {
								display: block;
								position: absolute;
								width: <?php echo esc_html( $slider_logo_width ); ?>px;
								height: <?php echo esc_html( $slider_logo_height ); ?>px;
								<?php if ($slider_logo_top != ''): ?>
									top: <?php echo esc_html( $slider_logo_top ); ?>px;
								<?php endif ?>
								<?php if ($slider_logo_right != ''): ?>
									right: <?php echo esc_html( $slider_logo_right ); ?>px;
								<?php endif ?>
								<?php if ($slider_logo_bottom != ''): ?>
									bottom: <?php echo esc_html( $slider_logo_bottom ); ?>px;
								<?php endif ?>
								<?php if ($slider_logo_left != ''): ?>
									left: <?php echo esc_html( $slider_logo_left ); ?>px;
								<?php endif ?>
								cursor: pointer;
							}
						<?php endif ?>

						#slider-<?= get_the_ID(); ?> 
						{
							width: <?php echo esc_html( $slider_width ); ?>px; 
							height: <?php echo esc_html( $slider_height ); ?>px; 
							position: relative;
							margin: auto !important;
						}

						#slider-<?= get_the_ID(); ?> .slide
						{
							width: <?php echo esc_html( $slider_width ); ?>px; 
							height: <?php echo esc_html( $slider_height ); ?>px; 
							position: relative;
						}

						#slider-<?= get_the_ID(); ?> .slidebg
						{
							height: 100%;
							width: 100%;
							background-position: center center;
						    background-repeat: no-repeat;
						    background-size: cover;
						}
						#slider-<?= get_the_ID(); ?> .slidetitle
						{ 
							position: absolute;
							<?php if ($slider_title_top != ''): ?>
								top: <?= esc_html( $slider_title_top ); ?>px;
							<?php endif ?>
							<?php if ($slider_title_left != ''): ?>
								left: <?= esc_html( $slider_title_left ); ?>px;
							<?php endif ?>
							<?php if ($slider_title_bottom != ''): ?>
								bottom: <?= esc_html( $slider_title_bottom ); ?>px;
							<?php endif ?>
							<?php if ($slider_title_right != ''): ?>
								right: <?= esc_html( $slider_title_right ); ?>px;
							<?php endif ?>
							<?php if ( $slider_title_css != '' ) { echo $slider_title_css; } ?> 
						}
						#slider-<?= get_the_ID(); ?> .slidesubtitle 
						{ 
							position: absolute;
							<?php if ($slider_subtitle_top != ''): ?>
								top: <?= esc_html( $slider_subtitle_top ); ?>px;
							<?php endif ?>
							<?php if ($slider_subtitle_left != ''): ?>
								left: <?= esc_html( $slider_subtitle_left ); ?>px;
							<?php endif ?>
							<?php if ($slider_subtitle_bottom != ''): ?>
								bottom: <?= esc_html( $slider_subtitle_bottom ); ?>px;
							<?php endif ?>
							<?php if ($slider_subtitle_right != ''): ?>
								right: <?= esc_html( $slider_subtitle_right ); ?>px;
							<?php endif ?>
							<?php if ( $slider_subtitle_css != '' ) { echo $slider_subtitle_css; } ?>
						}

						#slider-<?= get_the_ID(); ?> .slidetext 
						{ 
							position: absolute;
							<?php if ($slider_text_top != ''): ?>
								top: <?= esc_html( $slider_text_top ); ?>px;
							<?php endif ?>
							<?php if ($slider_text_left != ''): ?>
								left: <?= esc_html( $slider_text_left ); ?>px;
							<?php endif ?>
							<?php if ($slider_text_bottom != ''): ?>
								bottom: <?= esc_html( $slider_text_bottom ); ?>px;
							<?php endif ?>
							<?php if ($slider_text_right != ''): ?>
								right: <?= esc_html( $slider_text_right ); ?>px;
							<?php endif ?>
							<?php if ( $slider_text_css != '' ) { echo $slider_text_css; } ?>
						}

						#slider-<?= get_the_ID(); ?> .slidepic 
						{ 
							position: absolute; 
							width: <?= esc_html( $slider_pic_width ); ?>px; 
							height: <?= esc_html( $slider_pic_height ); ?>px; 
							<?php if ($slider_pic_top != ''): ?>
								top: <?= esc_html( $slider_pic_top ); ?>px; 
							<?php endif ?>
							<?php if ($slider_pic_left != ''): ?>
								left: <?= esc_html( $slider_pic_left ); ?>px;
							<?php endif ?>
							<?php if ($slider_pic_bottom != ''): ?>
								bottom: <?= esc_html( $slider_pic_bottom ); ?>px;
							<?php endif ?>
							<?php if ($slider_pic_right != ''): ?>
								right: <?= esc_html( $slider_pic_right ); ?>px;
							<?php endif ?>
							background-size: cover; 
							<?php if ( $slider_pic_css != '' ) { echo $slider_pic_css; } ?>
						}
						@media (max-width: 1499px) and (min-width: 960px) {
							#slider-<?= get_the_ID(); ?>, #slider-<?= get_the_ID(); ?> .slide
							{
								width: 960px;
								/*height: 600px;*/
							}
						}
						@media (max-width: 959px) and (min-width: 768px) {
							#slider-<?= get_the_ID(); ?>, #slider-<?= get_the_ID(); ?> .slide
							{
								width: 768px; 
								/*height: 480px;*/
							}
						}
						@media (max-width: 767px) and (min-width: 480px) {
							#slider-<?= get_the_ID(); ?>, #slider-<?= get_the_ID(); ?> .slide
							{
								width: 480px; 
								/*height: 390px;*/
							}
						}
						@media (max-width: 479px) {
							#slider-<?= get_the_ID(); ?>, #slider-<?= get_the_ID(); ?> .slide
							{
								width: 300px; 
								/*height: 390px;*/
							}
						}
						<?= $slider_css; ?>
					</style>
					<script>
						jQuery(document).ready(function($){
							$('#slider-<?= get_the_ID(); ?>').jcarousel({
						        vertical: !1,
						        animation: {
							        duration: <?= esc_html( $slider_speed ); ?>,
							        easing:   'linear'
							    },
							    transforms:   Modernizr.csstransforms,
	        					transforms3d: Modernizr.csstransforms3d,
							    transitions: !0,
							    wrap: 'circular'
						    })<?php if ( $slider_swipe == 1 ): ?>.jcarouselSwipe()<?php endif ?>;
						    <?php if ( $slider_autoplay == 1 ): ?>
							    $('#slider-<?= get_the_ID(); ?>').jcarouselAutoscroll({
						            interval: <?= esc_html( $slider_autoplay_delta ); ?>,
						            target: '+=1',
						            autostart: true
						        });
					        <?php endif ?>
					        <?php if ( $slider_autoplay == 1 && $slider_pauseonhover == 1 ): ?>
						        $('#slider-<?= get_the_ID(); ?>').hover(
						        	function() {
						        		$('#slider-<?= get_the_ID(); ?>').jcarouselAutoscroll('stop');
						        	},
						        	function() {
						        		$('#slider-<?= get_the_ID(); ?>').jcarouselAutoscroll('start');
						        	}
						        );
				        	<?php endif ?>
					        
					        <?php if ( $slider_next == 1 ): ?>
						        $('#slider-next').click(function() {
						        	$('#slider-<?= get_the_ID(); ?>').jcarousel('scroll', '+=1');
						        });
					        <?php endif ?>
					        <?php if ( $slider_pagination == 1 ): ?>
						        $('.jcarousel-slider-pagination')
						            .on('jcarouselpagination:active', 'a', function() {
						                $(this).addClass('active');
						            })
						            .on('jcarouselpagination:inactive', 'a', function() {
						                $(this).removeClass('active');
						            })
						            .jcarouselPagination();
					        <?php endif ?>

					        if ($(window).width() < 1500) {
								$('#slider-<?= get_the_ID(); ?>').css('width', $(window).width() + 'px');
								$('#slider-<?= get_the_ID(); ?> .slide').css('width', $(window).width() + 'px');
							}
					        $(window).resize(function() {
								if ($(window).width() < 1500) {
									$('#slider-<?= get_the_ID(); ?>').css('width', $(window).width() + 'px');
									$('#slider-<?= get_the_ID(); ?> .slide').css('width', $(window).width() + 'px');
								}
							});

					    });
					</script>

				</div>			

            <?php endwhile;
        wp_reset_postdata(); ?>

    	<?php $slider = ob_get_clean();

    	return $slider;

    	}

    }

    /**
	 * Register the Template for the PowerGallery post type.
	 *
	 * @since    0.0.1
	 */
	public function register_powergallery_template( $template ) {
	    $post_types = array( 'powergallery' );
	    if ( is_singular( $post_types ) )
	        $template = plugin_dir_path( __FILE__ ) . 'partials/wordmedia-public-single-powergallery-display.php';
	    return $template;
	}

    /**
	 * Define the shortcode for the PowerGallery post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_powergallery_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'powergallery',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

        	$powergallery_pics = ! isset( $meta['powergallery_pics'][0] ) ? array() :  maybe_unserialize( $meta['powergallery_pics'][0] );

        	$powergallery_pics_src = array();
			foreach ($powergallery_pics as $key => $value) {
				if ( isset( $powergallery_pics[$key] ) ) {
					$powergallery_pics_src[$key] = wp_get_attachment_url($value);
				}
			}

        	$powergallery_type = ! isset( $meta['powergallery_type'][0] ) ? 0 : $meta['powergallery_type'][0];

        	$powergallery_width = ! isset( $meta['powergallery_width'][0] ) ? 1200 : $meta['powergallery_width'][0];
        	$powergallery_height = ! isset( $meta['powergallery_height'][0] ) ? 300 : $meta['powergallery_height'][0];

        	$powergallery_columns = ! isset( $meta['powergallery_columns'][0] ) ? 0 : $meta['powergallery_columns'][0];

        	$powergallery_zoom = ! isset( $meta['powergallery_zoom'][0] ) ? 1 : $meta['powergallery_zoom'][0];
        	$powergallery_gray = ! isset( $meta['powergallery_gray'][0] ) ? 1 : $meta['powergallery_gray'][0];

        	$powergallery_border = ! isset( $meta['powergallery_border'][0] ) ? 0 : $meta['powergallery_border'][0];
			$powergallery_border_color = ! isset( $meta['powergallery_border_color'][0] ) ? '' : $meta['powergallery_border_color'][0];
			$powergallery_margin = ! isset( $meta['powergallery_margin'][0] ) ? 0 : $meta['powergallery_margin'][0];

			$powergallery_css = ! isset( $meta['powergallery_css'][0] ) ? '' : $meta['powergallery_css'][0];

			if ($powergallery_columns != 0) {
				if ($powergallery_type == 0) {
					$column_percent = round( ( 100 / $powergallery_columns ), 5);
				}
				else if ($powergallery_type == 1) {
					$column_percent = round( ( 100 / 4 ), 5);

					$small_pic_height = $powergallery_height;
					$small_pic_width = round( ( $powergallery_width / 4 ), 5 );
					$big_pic_height = 2 * $powergallery_height;
					$big_pic_width = round( ( $powergallery_width / 2 ), 5 );
				}		
				else if ($powergallery_type == 2) {
					$column_percent = round( ( 100 / 4 ), 5);

					$small_pic_height = $powergallery_height;
					$small_pic_width = round( ( $powergallery_width / 4 ), 5 );
					$big_pic_height = 2 * $powergallery_height;
					$big_pic_width = $small_pic_width;

					$powergallery_width_960 = 960;
					$powergallery_width_768 = 768;
					$powergallery_width_480 = 480;
					$powergallery_width_300 = 300;
					$powergallery_height_960 = ( ( $powergallery_width_960 / $powergallery_width ) * $powergallery_height );
					$powergallery_height_768 = ( ( $powergallery_width_768 / $powergallery_width ) * $powergallery_height );
					$powergallery_height_480 = ( 2 * ( $powergallery_width_480 / $powergallery_width ) * $powergallery_height );
					$powergallery_height_300 = ( 2 * ( $powergallery_width_300 / $powergallery_width ) * $powergallery_height );

					$small_pic_height_960 = $powergallery_height_960;
					$small_pic_width_960 = round( ( $powergallery_width_960 / 4 ), 5 );
					$big_pic_height_960 = 2 * $powergallery_height_960;
					$big_pic_width_960 = $small_pic_width_960;

					$small_pic_height_768 = $powergallery_height_768;
					$small_pic_width_768 = round( ( $powergallery_width_768 / 4 ), 5 );
					$big_pic_height_768 = 2 * $powergallery_height_768;
					$big_pic_width_768 = $small_pic_width_768;

					$small_pic_height_480 = $powergallery_height_480;
					$small_pic_width_480 = round( ( $powergallery_width_480 / 2 ), 5 );
					$big_pic_height_480 = 2 * $powergallery_height_480;
					$big_pic_width_480 = $small_pic_width_480;

					$small_pic_height_300 = $powergallery_height_300;
					$small_pic_width_300 = round( ( $powergallery_width_300 / 2 ), 5 );
					$big_pic_height_300 = 2 * $powergallery_height_300;
					$big_pic_width_300 = $small_pic_width_300;

				}
				else if ($powergallery_type == 3) {
					$small_pic_height = $powergallery_height;
					$small_pic_width = round( ( $powergallery_width / 3 ), 5 );
					$big_pic_height = 1.5 * $powergallery_height;
					$big_pic_width = round( ( $powergallery_width / 2 ), 5 );

					$powergallery_width_960 = 960;
					$powergallery_width_768 = 768;
					$powergallery_width_480 = 480;
					$powergallery_width_300 = 300;
					$powergallery_height_960 = ( ( $powergallery_width_960 / $powergallery_width ) * $powergallery_height );
					$powergallery_height_768 = ( ( $powergallery_width_768 / $powergallery_width ) * $powergallery_height );
					$powergallery_height_480 = ( ( $powergallery_width_480 / $powergallery_width ) * $powergallery_height );
					$powergallery_height_300 = ( ( $powergallery_width_300 / $powergallery_width ) * $powergallery_height );

					$small_pic_height_960 = $powergallery_height_960;
					$small_pic_width_960 = round( ( $powergallery_width_960 / 3 ), 5 );
					$big_pic_height_960 = 2 * $powergallery_height_960;
					$big_pic_width_960 = round( ( $powergallery_width_960 / 2 ), 5 );

					$small_pic_height_768 = $powergallery_height_768;
					$small_pic_width_768 = round( ( $powergallery_width_768 / 3 ), 5 );
					$big_pic_height_768 = 2 * $powergallery_height_768;
					$big_pic_width_768 = round( ( $powergallery_width_768 / 2 ), 5 );

					$small_pic_height_480 = $powergallery_height_480;
					$small_pic_width_480 = round( ( $powergallery_width_480 / 3 ), 5 );
					$big_pic_height_480 = 2 * $powergallery_height_480;
					$big_pic_width_480 = round( ( $powergallery_width_480 / 2 ), 5 );

					$small_pic_height_300 = $powergallery_height_300;
					$small_pic_width_300 = round( ( $powergallery_width_300 / 3 ), 5 );
					$big_pic_height_300 = 2 * $powergallery_height_300;
					$big_pic_width_300 = round( ( $powergallery_width_300 / 2 ), 5 );
				}
			}
			else {
				$column_percent = 33.33333;			
			}
			
			$items = count($powergallery_pics);
			
			while ( $query->have_posts() ) : $query->the_post(); ?>				

				<div id="powergallery-<?= get_the_ID(); ?>" class="powergallery">
					<div class="powergallery-int">
						<?php foreach ($powergallery_pics as $key => $value) { ?>
							<p class="powergallery-entry <?php if ( $powergallery_zoom == 1 ): ?>pageimagezoom<?php endif ?> <?php if ( $powergallery_gray == 1 ): ?>grayscale grayscale-fade<?php endif ?>" data-src="<?php echo $powergallery_pics_src[$key] ?>"><img src="<?php echo $powergallery_pics_src[$key] ?>"></p>
						<?php } ?>
					</div>
				</div>				

				<style>
					#powergallery-<?= get_the_ID(); ?> .powergallery-int {
						width: 100%;
						height: 100%;
						position: relative;
					}
					<?php if ($powergallery_type == 0): ?>
						#powergallery-<?= get_the_ID(); ?> { 
							max-width: <?= $powergallery_width ?>px;
							margin: auto;
							clear: both; 
							float: none;
							line-height: 0;
							font-size: 0;
							display: block;
						}
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry { 
							width: -webkit-calc(<?= $column_percent ?>% - 0.1px);
							width: calc(<?= $column_percent ?>% - 0.1px);
							height: auto;
							max-height: <?= $powergallery_height ?>px;
							line-height: 0;
							font-size: 0;
							float: left;
							text-align: center;
							vertical-align: middle;
							padding: 0;
							<?php if ( $powergallery_border != 0 ): ?>
								border-width: <?= $powergallery_border ?>px;
								border-style: solid;
								<?php if ( $powergallery_border_color != '' ): ?>
									border-color: <?= $powergallery_border_color ?>;
								<?php endif ?>
							<?php endif ?>
							<?php if ( $powergallery_margin != 0 ): ?>
								margin: <?= $powergallery_margin ?>px;
							<?php endif ?>
						}
						<?php if ( $powergallery_margin != 0 ): ?>
							#powergallery-<?= get_the_ID(); ?> .powergallery-entry {
							    width: -webkit-calc(<?= $column_percent ?>% - <?= $powergallery_margin * ( $powergallery_columns - 1 ) / $powergallery_columns * 2 ?>px);
								width: calc(<?= $column_percent ?>% - <?= $powergallery_margin * ( $powergallery_columns - 1 ) / $powergallery_columns * 2 ?>px);
							}
							#powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-child(<?= $powergallery_columns ?>n + 1) {
								margin-left: 0px;
							}
							#powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-child(<?= $powergallery_columns ?>n + <?= $powergallery_columns ?>) {
								margin-right: 0px;
							}
						<?php endif ?>
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry:hover {
							cursor:  pointer;
						}
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry img {
							display: block;
							height: 100%;
							width: 100%;
							-o-object-fit: cover;
							object-fit: cover;
						}
						@media (max-width: 959px) and (min-width: 480px) {
							<?php if ( $powergallery_columns == 2 || $powergallery_column == 4 ): ?>
								<?php if ( $powergallery_margin != 0 ): ?>
									#powergallery-<?= get_the_ID(); ?> .powergallery-entry {
								        width: -webkit-calc(<?= $column_percent * 2 ?>% - <?= $powergallery_margin ?>px);
										width: calc(<?= $column_percent * 2 ?>% - <?= $powergallery_margin ?>px);
								    }
								    #powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-child(<?= $powergallery_columns / 2 ?>n + 1) {
								        margin-left: 0px!important;
								    }
								    #powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-child(<?= $powergallery_columns / 2 ?>n + <?= $powergallery_columns / 2 ?>) {
								        margin-right: 0px!important;
								    }
								<?php endif ?>
								<?php if ($powergallery_margin == 0): ?>
									#powergallery-<?= get_the_ID(); ?> .powergallery-entry {
								        width: <?= $column_percent * 2 ?>%;
								    }
								    #powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-child(<?= $powergallery_columns / 2 ?>n + 1) {
								        margin-left: 0px!important;
								    }
								    #powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-child(<?= $powergallery_columns / 2 ?>n + <?= $powergallery_columns / 2 ?>) {
								        margin-right: 0px!important;
								    }
							    <?php endif ?>
						    <?php endif ?>
						}
						@media (max-width: 479px) {
						    #powergallery-<?= get_the_ID(); ?> .powergallery-entry {
						    	width: -webkit-calc(100% - 0.1px)!important;
						        width: calc(100% - 0.1px)!important;
						        margin: <?= $powergallery_margin / 2 ?>px 0px!important;
						    }
						}					
					<?php endif ?>
					<?php if ($powergallery_type == 1): ?>
						#powergallery-<?= get_the_ID(); ?> {
							max-width: <?= $powergallery_width ?>px;
						 	margin: auto;
						 	line-height: 0;
							font-size: 0;
							clear: both;
							float: none;
							display: block;
						}
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry {
							width: <?= $small_pic_width ?>px;
							height: <?= $small_pic_height ?>px;
							line-height: 0;
							font-size: 0;
							float: left;
							text-align: center;
							vertical-align: middle;
							padding: 0px;
							<?php if ( $powergallery_border != 0 ): ?>
								border-width: <?= $powergallery_border ?>px;
								border-style: solid;
								<?php if ( $powergallery_border_color != '' ): ?>
									border-color: <?= $powergallery_border_color ?>;
								<?php endif ?>
							<?php endif ?>
							<?php if ( $powergallery_margin != 0 ): ?>
								margin: <?= $powergallery_margin ?>px;
								width: calc(<?= $small_pic_width - ( 2 * $powergallery_margin ) ?>px)!important;
							<?php endif ?>
						}
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry:hover {
							cursor:  pointer;
						}
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-of-type(4n), #powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-of-type(5n), #powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-of-type(6n) { 
							border-top: 0; 
						}
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-of-type(6n), #powergallery-<?= get_the_ID(); ?> .powergallery-entry:nth-of-type(6n + 1) { 
							width: <?= $big_pic_width ?>px;
						}
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry img { display: block; height: 100%; width: 100%; -o-object-fit: cover; object-fit: cover; }
						@media (max-width: 1499px) and (min-width: 960px) {

						}
						@media (max-width: 959px) and (min-width: 768px) {
							
						}
						@media (max-width: 767px) and (min-width: 480px) {
							
						}
						@media (max-width: 479px) {
							
						}
					<?php endif ?>
					<?php if ($powergallery_type == 2): ?>
						<?php $powergallery_height = ceil($items / 6) * $big_pic_height; ?>
						<?php $powergallery_height_960 = ceil($items / 6) * $big_pic_height_960; ?>
						<?php $powergallery_height_768 = ceil($items / 6) * $big_pic_height_768; ?>
						<?php $powergallery_height_480 = ceil($items / 6) * 2 * $big_pic_height_480; ?>
						<?php $powergallery_height_300 = ceil($items / 6) * 2 * $big_pic_height_300; ?>
						#powergallery-<?= get_the_ID(); ?> {
							width: <?= $powergallery_width ?>px;
							height: <?= $powergallery_height ?>px;
						 	margin: auto;
						 	line-height: 0;
							font-size: 0;
						}
						.powergallery-entry { 
							width: <?= $small_pic_width ?>px;
						 	height: <?= $small_pic_height ?>px;
						 	line-height: 0;
						 	font-size: 0;
						 	padding: 0;						  
						 	<?php if ( $powergallery_border != 0 ): ?>
								border-width: <?= $powergallery_border ?>px;
								border-style: solid;
								<?php if ( $powergallery_border_color != '' ): ?>
									border-color: <?= $powergallery_border_color ?>;
								<?php endif ?>
							<?php endif ?>
							<?php if ( $powergallery_margin != 0 ): ?>
								margin: <?= $powergallery_margin ?>px;
							<?php endif ?>
							position: absolute;
						  	top: 0px;
						  	left: 0px;
						}
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry:hover {
							cursor:  pointer;
						}
						.powergallery-entry img {
						  	display: block;
						  	height: 100%;
						  	width: 100%;
						  	-o-object-fit: cover;
						  	object-fit: cover;
						}
						.powergallery-entry:nth-child(6n + 1) { 

						}
						.powergallery-entry:nth-child(6n + 2) {
						  	top: <?= $small_pic_height ?>px;
						}
						.powergallery-entry:nth-child(6n + 3) { 
						  	height: <?= $big_pic_height ?>px;
						  	left: <?= $big_pic_width ?>px;
						}
						.powergallery-entry:nth-child(6n + 4) { 
						  	left: <?= 2 * $small_pic_width ?>px;
						}
						.powergallery-entry:nth-child(6n + 5) { 
						  	top: <?= $small_pic_height ?>px;
						  	left: <?= 2 * $small_pic_width ?>px;
						}
						.powergallery-entry:nth-child(6n + 6) { 
						  	height: <?= $big_pic_height ?>px;
						  	left: <?= 3 * $small_pic_width ?>px;
						}
						.powergallery-entry:nth-child(7), .powergallery-entry:nth-child(9), .powergallery-entry:nth-child(10), .powergallery-entry:nth-child(12) { 
							top: <?= 2 * $small_pic_height ?>px;
						}
						.powergallery-entry:nth-child(8), .powergallery-entry:nth-child(11) {
						  	top: <?= 3 * $small_pic_height ?>px;
						}
						.powergallery-entry:nth-child(13), .powergallery-entry:nth-child(15), .powergallery-entry:nth-child(16), .powergallery-entry:nth-child(18) { 
							top: <?= 4 * $small_pic_height ?>px;
						}
						.powergallery-entry:nth-child(14), .powergallery-entry:nth-child(17) {
						  	top: <?= 5 * $small_pic_height ?>px;
						}
						.powergallery-entry:nth-child(19), .powergallery-entry:nth-child(21), .powergallery-entry:nth-child(22), .powergallery-entry:nth-child(24) { 
							top: <?= 6 * $small_pic_height ?>px;
						}
						.powergallery-entry:nth-child(20), .powergallery-entry:nth-child(23) {
						  	top: <?= 7 * $small_pic_height ?>px;
						}

						@media (max-width: 1499px) and (min-width: 960px) {
							#powergallery-<?= get_the_ID(); ?> {
								width: <?= $powergallery_width_960 ?>px;
								height: <?= $powergallery_height_960 ?>px;
							}
							.powergallery-entry { 
								width: <?= $small_pic_width_960 ?>px;
							 	height: <?= $small_pic_height_960 ?>px;
								position: absolute;
							  	top: 0px;
							  	left: 0px;
							}
						    .powergallery-entry:nth-child(6n + 1) { 

							}
							.powergallery-entry:nth-child(6n + 2) {
							  	top: <?= $small_pic_height_960 ?>px;
							}
							.powergallery-entry:nth-child(6n + 3) { 
							  	height: <?= $big_pic_height_960 ?>px;
							  	left: <?= $big_pic_width_960 ?>px;
							}
							.powergallery-entry:nth-child(6n + 4) { 
							  	left: <?= 2 * $small_pic_width_960 ?>px;
							}
							.powergallery-entry:nth-child(6n + 5) { 
							  	top: <?= $small_pic_height_960 ?>px;
							  	left: <?= 2 * $small_pic_width_960 ?>px;
							}
							.powergallery-entry:nth-child(6n + 6) { 
							  	height: <?= $big_pic_height_960 ?>px;
							  	left: <?= 3 * $small_pic_width_960 ?>px;
							}
							.powergallery-entry:nth-child(7), .powergallery-entry:nth-child(9), .powergallery-entry:nth-child(10), .powergallery-entry:nth-child(12) { 
								top: <?= 2 * $small_pic_height_960 ?>px;
							}
							.powergallery-entry:nth-child(8), .powergallery-entry:nth-child(11) {
							  	top: <?= 3 * $small_pic_height_960 ?>px;
							}
							.powergallery-entry:nth-child(13), .powergallery-entry:nth-child(15), .powergallery-entry:nth-child(16), .powergallery-entry:nth-child(18) { 
								top: <?= 4 * $small_pic_height_960 ?>px;
							}
							.powergallery-entry:nth-child(14), .powergallery-entry:nth-child(17) {
							  	top: <?= 5 * $small_pic_height_960 ?>px;
							}
							.powergallery-entry:nth-child(19), .powergallery-entry:nth-child(21), .powergallery-entry:nth-child(22), .powergallery-entry:nth-child(24) { 
								top: <?= 6 * $small_pic_height_960 ?>px;
							}
							.powergallery-entry:nth-child(20), .powergallery-entry:nth-child(23) {
							  	top: <?= 7 * $small_pic_height_960 ?>px;
							}
						}
						@media (max-width: 959px) and (min-width: 768px) {
							#powergallery-<?= get_the_ID(); ?> {
								width: <?= $powergallery_width_768 ?>px;
								height: <?= $powergallery_height_768 ?>px;
							}
							.powergallery-entry { 
								width: <?= $small_pic_width_768 ?>px;
							 	height: <?= $small_pic_height_768 ?>px;
								position: absolute;
							  	top: 0px;
							  	left: 0px;
							}
						    .powergallery-entry:nth-child(6n + 1) { 

							}
							.powergallery-entry:nth-child(6n + 2) {
							  	top: <?= $small_pic_height_768 ?>px;
							}
							.powergallery-entry:nth-child(6n + 3) { 
							  	height: <?= $big_pic_height_768 ?>px;
							  	left: <?= $big_pic_width_768 ?>px;
							}
							.powergallery-entry:nth-child(6n + 4) { 
							  	left: <?= 2 * $small_pic_width_768 ?>px;
							}
							.powergallery-entry:nth-child(6n + 5) { 
							  	top: <?= $small_pic_height_768 ?>px;
							  	left: <?= 2 * $small_pic_width_768 ?>px;
							}
							.powergallery-entry:nth-child(6n + 6) { 
							  	height: <?= $big_pic_height_768 ?>px;
							  	left: <?= 3 * $small_pic_width_768 ?>px;
							}
							.powergallery-entry:nth-child(7), .powergallery-entry:nth-child(9), .powergallery-entry:nth-child(10), .powergallery-entry:nth-child(12) { 
								top: <?= 2 * $small_pic_height_768 ?>px;
							}
							.powergallery-entry:nth-child(8), .powergallery-entry:nth-child(11) {
							  	top: <?= 3 * $small_pic_height_768 ?>px;
							}
							.powergallery-entry:nth-child(13), .powergallery-entry:nth-child(15), .powergallery-entry:nth-child(16), .powergallery-entry:nth-child(18) { 
								top: <?= 4 * $small_pic_height_768 ?>px;
							}
							.powergallery-entry:nth-child(14), .powergallery-entry:nth-child(17) {
							  	top: <?= 5 * $small_pic_height_768 ?>px;
							}
							.powergallery-entry:nth-child(19), .powergallery-entry:nth-child(21), .powergallery-entry:nth-child(22), .powergallery-entry:nth-child(24) { 
								top: <?= 6 * $small_pic_height_768 ?>px;
							}
							.powergallery-entry:nth-child(20), .powergallery-entry:nth-child(23) {
							  	top: <?= 7 * $small_pic_height_768 ?>px;
							}
						}
						@media (max-width: 767px) and (min-width: 480px) {
							#powergallery-<?= get_the_ID(); ?> {
								width: <?= $powergallery_width_480 ?>px;
								height: <?= $powergallery_height_480 ?>px;
							}
							.powergallery-entry { 
								width: <?= $small_pic_width_480 ?>px;
							 	height: <?= $small_pic_height_480 ?>px;
								position: absolute;
							  	top: 0px;
							  	left: 0px;
							}
						    .powergallery-entry:nth-child(6n + 1) { 

							}
							.powergallery-entry:nth-child(6n + 2) {
							  	top: <?= $small_pic_height_480 ?>px;
							}
							.powergallery-entry:nth-child(6n + 3) { 
							  	height: <?= $big_pic_height_480 ?>px;
							  	left: <?= $big_pic_width_480 ?>px;
							}
							.powergallery-entry:nth-child(6n + 4) {
								top: <?= $big_pic_height_480 ?>px;
								left: 0px;
							}
							.powergallery-entry:nth-child(6n + 5) { 
							  	top: <?= $small_pic_height_480 + $big_pic_height_480 ?>px;
							  	left: 0px;
							}
							.powergallery-entry:nth-child(6n + 6) { 
							  	height: <?= $big_pic_height_480 ?>px;
							  	top: <?= $big_pic_height_480 ?>px;
							  	left: <?= $small_pic_width_480 ?>px;
							}
						}
						@media (max-width: 479px) {
							#powergallery-<?= get_the_ID(); ?> {
								width: <?= $powergallery_width_300 ?>px;
								height: <?= $powergallery_height_300 ?>px;
							}
							.powergallery-entry { 
								width: <?= $small_pic_width_300 ?>px;
							 	height: <?= $small_pic_height_300 ?>px;
								position: absolute;
							  	top: 0px;
							  	left: 0px;
							}
						    .powergallery-entry:nth-child(6n + 1) { 

							}
							.powergallery-entry:nth-child(6n + 2) {
							  	top: <?= $small_pic_height_300 ?>px;
							}
							.powergallery-entry:nth-child(6n + 3) { 
							  	height: <?= $big_pic_height_300 ?>px;
							  	left: <?= $big_pic_width_300 ?>px;
							}
							.powergallery-entry:nth-child(6n + 4) {
								top: <?= $big_pic_height_300 ?>px;
								left: 0px;
							}
							.powergallery-entry:nth-child(6n + 5) { 
							  	top: <?= $small_pic_height_300 + $big_pic_height_300 ?>px;
							  	left: 0px;
							}
							.powergallery-entry:nth-child(6n + 6) { 
							  	height: <?= $big_pic_height_300 ?>px;
							  	top: <?= $big_pic_height_300 ?>px;
							  	left: <?= $small_pic_width_300 ?>px;
							}
						}
					<?php endif ?>
					<?php if ($powergallery_type == 3): ?>
						<?php $powergallery_height = ceil($items / 5) * ( $big_pic_height + $small_pic_height ); ?>
						<?php $powergallery_height_960 = ceil($items / 5) * ( $big_pic_height_960 + $small_pic_height_960 ); ?>
						<?php $powergallery_height_768 = ceil($items / 5) * ( $big_pic_height_768 + $small_pic_height_768 ); ?>
						<?php $powergallery_height_480 = ceil($items / 5) * ( $big_pic_height_480 + $small_pic_height_480 ); ?>
						<?php $powergallery_height_300 = ceil($items / 5) * ( $big_pic_height_300 + $small_pic_height_300 ); ?>

						#powergallery-<?= get_the_ID(); ?> {
							width: <?= $powergallery_width ?>px;
							height: <?= $powergallery_height ?>px;
						 	margin: auto;
						 	line-height: 0;
							font-size: 0;
						}
						.powergallery-entry { 
							width: <?= $small_pic_width ?>px;
						 	height: <?= $small_pic_height ?>px;
						 	line-height: 0;
						 	font-size: 0;
						 	padding: 0;						  
						 	<?php if ( $powergallery_border != 0 ): ?>
								border-width: <?= $powergallery_border ?>px;
								border-style: solid;
								<?php if ( $powergallery_border_color != '' ): ?>
									border-color: <?= $powergallery_border_color ?>;
								<?php endif ?>
							<?php endif ?>
							<?php if ( $powergallery_margin != 0 ): ?>
								margin: <?= $powergallery_margin ?>px;
							<?php endif ?>
							position: absolute;
						  	top: 0px;
						  	left: 0px;
						}
						#powergallery-<?= get_the_ID(); ?> .powergallery-entry:hover {
							cursor: pointer;
						}
						.powergallery-entry img {
						  	display: block;
						  	height: 100%;
						  	width: 100%;
						  	-o-object-fit: cover;
						  	object-fit: cover;
						}
						.powergallery-entry:nth-child(5n + 1) { 
							top: 0;
						}
						.powergallery-entry:nth-child(5n + 2) {
							top: 0;
						  	left: <?= $small_pic_width ?>px;
						}
						.powergallery-entry:nth-child(5n + 3) {
							top: 0;
						  	left: <?= 2 * $small_pic_width ?>px;
						}
						.powergallery-entry:nth-child(5n + 4) {
							width: <?= $big_pic_width ?>px;
						 	height: <?= $big_pic_height ?>px;
						 	top: <?= $small_pic_height ?>px;
						  	left: 0;
						}
						.powergallery-entry:nth-child(5n + 5) { 
						  	width: <?= $big_pic_width ?>px;
						 	height: <?= $big_pic_height ?>px;
						 	top: <?= $small_pic_height ?>px;
						  	left: <?= $big_pic_width ?>px;
						}
						.powergallery-entry:nth-child(6), .powergallery-entry:nth-child(7), .powergallery-entry:nth-child(8) { 
							top: <?= $small_pic_height + $big_pic_height ?>px;
						}
						.powergallery-entry:nth-child(9), .powergallery-entry:nth-child(10) { 
							top: <?= ( 2 * $small_pic_height ) + $big_pic_height ?>px;
						}
						.powergallery-entry:nth-child(11), .powergallery-entry:nth-child(12), .powergallery-entry:nth-child(13) { 
							top: <?= 2 * ( $small_pic_height + $big_pic_height ) ?>px;
						}
						.powergallery-entry:nth-child(14), .powergallery-entry:nth-child(15) { 
							top: <?= 2 * ( ( 2 * $small_pic_height ) + $big_pic_height ) ?>px;
						}
						.powergallery-entry:nth-child(16), .powergallery-entry:nth-child(17), .powergallery-entry:nth-child(18) { 
							top: <?= 3 * ( $small_pic_height + $big_pic_height ) ?>px;
						}
						.powergallery-entry:nth-child(19), .powergallery-entry:nth-child(20) { 
							top: <?= 3 * ( ( 2 * $small_pic_height ) + $big_pic_height ) ?>px;
						}
						@media (max-width: 1499px) and (min-width: 960px) {
							.powergallery-entry { 
							  	width: <?= $small_pic_width_960 ?>px;
							 	height: <?= $small_pic_height_960 ?>px;
							}
							.powergallery-entry:nth-child(5n + 1) { 
								top: 0;
							}
							.powergallery-entry:nth-child(5n + 2) {
								top: 0;
							  	left: <?= $small_pic_width_960 ?>px;
							}
							.powergallery-entry:nth-child(5n + 3) {
								top: 0;
							  	left: <?= 2 * $small_pic_width_960 ?>px;
							}
							.powergallery-entry:nth-child(5n + 4) {
								width: <?= $big_pic_width_960 ?>px;
							 	height: <?= $big_pic_height_960 ?>px;
							 	top: <?= $small_pic_height_960 ?>px;
							  	left: 0;
							}
							.powergallery-entry:nth-child(5n + 5) { 
							  	width: <?= $big_pic_width_960 ?>px;
							 	height: <?= $big_pic_height_960 ?>px;
							 	top: <?= $small_pic_height_960 ?>px;
							  	left: <?= $big_pic_width_960 ?>px;
							}
							.powergallery-entry:nth-child(6), .powergallery-entry:nth-child(7), .powergallery-entry:nth-child(8) { 
								top: <?= $small_pic_height_960 + $big_pic_height_960 ?>px;
							}
							.powergallery-entry:nth-child(9), .powergallery-entry:nth-child(10) { 
								top: <?= ( 2 * $small_pic_height_960 ) + $big_pic_height_960 ?>px;
							}
							.powergallery-entry:nth-child(11), .powergallery-entry:nth-child(12), .powergallery-entry:nth-child(13) { 
								top: <?= 2 * ( $small_pic_height_960 + $big_pic_height_960 ) ?>px;
							}
							.powergallery-entry:nth-child(14), .powergallery-entry:nth-child(15) { 
								top: <?= 2 * ( ( 2 * $small_pic_height_960 ) + $big_pic_height_960 ) ?>px;
							}
							.powergallery-entry:nth-child(16), .powergallery-entry:nth-child(17), .powergallery-entry:nth-child(18) { 
								top: <?= 3 * ( $small_pic_height_960 + $big_pic_height_960 ) ?>px;
							}
							.powergallery-entry:nth-child(19), .powergallery-entry:nth-child(20) { 
								top: <?= 3 * ( ( 2 * $small_pic_height_960 ) + $big_pic_height_960 ) ?>px;
							}
						}
						@media (max-width: 959px) and (min-width: 768px) {
							.powergallery-entry { 
							  	width: <?= $small_pic_width_768 ?>px;
							 	height: <?= $small_pic_height_768 ?>px;
							}
							.powergallery-entry:nth-child(5n + 1) { 
								top: 0;
							}
							.powergallery-entry:nth-child(5n + 2) {
								top: 0;
							  	left: <?= $small_pic_width_768 ?>px;
							}
							.powergallery-entry:nth-child(5n + 3) {
								top: 0;
							  	left: <?= 2 * $small_pic_width_768 ?>px;
							}
							.powergallery-entry:nth-child(5n + 4) {
								width: <?= $big_pic_width_768 ?>px;
							 	height: <?= $big_pic_height_768 ?>px;
							 	top: <?= $small_pic_height_768 ?>px;
							  	left: 0;
							}
							.powergallery-entry:nth-child(5n + 5) { 
							  	width: <?= $big_pic_width_768 ?>px;
							 	height: <?= $big_pic_height_768 ?>px;
							 	top: <?= $small_pic_height_768 ?>px;
							  	left: <?= $big_pic_width_768 ?>px;
							}
							.powergallery-entry:nth-child(6), .powergallery-entry:nth-child(7), .powergallery-entry:nth-child(8) { 
								top: <?= $small_pic_height_768 + $big_pic_height_768 ?>px;
							}
							.powergallery-entry:nth-child(9), .powergallery-entry:nth-child(10) { 
								top: <?= ( 2 * $small_pic_height_768 ) + $big_pic_height_768 ?>px;
							}
							.powergallery-entry:nth-child(11), .powergallery-entry:nth-child(12), .powergallery-entry:nth-child(13) { 
								top: <?= 2 * ( $small_pic_height_768 + $big_pic_height_768 ) ?>px;
							}
							.powergallery-entry:nth-child(14), .powergallery-entry:nth-child(15) { 
								top: <?= 2 * ( ( 2 * $small_pic_height_768 ) + $big_pic_height_768 ) ?>px;
							}
							.powergallery-entry:nth-child(16), .powergallery-entry:nth-child(17), .powergallery-entry:nth-child(18) { 
								top: <?= 3 * ( $small_pic_height_768 + $big_pic_height_768 ) ?>px;
							}
							.powergallery-entry:nth-child(19), .powergallery-entry:nth-child(20) { 
								top: <?= 3 * ( ( 2 * $small_pic_height_768 ) + $big_pic_height_768 ) ?>px;
							}
						}
						@media (max-width: 767px) and (min-width: 480px) {
							.powergallery-entry { 
							  	width: <?= $small_pic_width_480 ?>px;
							 	height: <?= $small_pic_height_480 ?>px;
							}
							.powergallery-entry:nth-child(5n + 1) { 
								top: 0;
							}
							.powergallery-entry:nth-child(5n + 2) {
								top: 0;
							  	left: <?= $small_pic_width_480 ?>px;
							}
							.powergallery-entry:nth-child(5n + 3) {
								top: 0;
							  	left: <?= 2 * $small_pic_width_480 ?>px;
							}
							.powergallery-entry:nth-child(5n + 4) {
								width: <?= $big_pic_width_480 ?>px;
							 	height: <?= $big_pic_height_480 ?>px;
							 	top: <?= $small_pic_height_480 ?>px;
							  	left: 0;
							}
							.powergallery-entry:nth-child(5n + 5) { 
							  	width: <?= $big_pic_width_480 ?>px;
							 	height: <?= $big_pic_height_480 ?>px;
							 	top: <?= $small_pic_height_480 ?>px;
							  	left: <?= $big_pic_width_480 ?>px;
							}
							.powergallery-entry:nth-child(6), .powergallery-entry:nth-child(7), .powergallery-entry:nth-child(8) { 
								top: <?= $small_pic_height_480 + $big_pic_height_480 ?>px;
							}
							.powergallery-entry:nth-child(9), .powergallery-entry:nth-child(10) { 
								top: <?= ( 2 * $small_pic_height_480 ) + $big_pic_height_480 ?>px;
							}
							.powergallery-entry:nth-child(11), .powergallery-entry:nth-child(12), .powergallery-entry:nth-child(13) { 
								top: <?= 2 * ( $small_pic_height_480 + $big_pic_height_480 ) ?>px;
							}
							.powergallery-entry:nth-child(14), .powergallery-entry:nth-child(15) { 
								top: <?= 2 * ( ( 2 * $small_pic_height_480 ) + $big_pic_height_480 ) ?>px;
							}
							.powergallery-entry:nth-child(16), .powergallery-entry:nth-child(17), .powergallery-entry:nth-child(18) { 
								top: <?= 3 * ( $small_pic_height_480 + $big_pic_height_480 ) ?>px;
							}
							.powergallery-entry:nth-child(19), .powergallery-entry:nth-child(20) { 
								top: <?= 3 * ( ( 2 * $small_pic_height_480 ) + $big_pic_height_480 ) ?>px;
							}
						}
						@media (max-width: 479px) {
							.powergallery-entry { 
							  	width: <?= $small_pic_width_300 ?>px;
							 	height: <?= $small_pic_height_300 ?>px;
							}
							.powergallery-entry:nth-child(5n + 1) { 
								top: 0;
							}
							.powergallery-entry:nth-child(5n + 2) {
								top: 0;
							  	left: <?= $small_pic_width_300 ?>px;
							}
							.powergallery-entry:nth-child(5n + 3) {
								top: 0;
							  	left: <?= 2 * $small_pic_width_300 ?>px;
							}
							.powergallery-entry:nth-child(5n + 4) {
								width: <?= $big_pic_width_300 ?>px;
							 	height: <?= $big_pic_height_300 ?>px;
							 	top: <?= $small_pic_height_300 ?>px;
							  	left: 0;
							}
							.powergallery-entry:nth-child(5n + 5) { 
							  	width: <?= $big_pic_width_300 ?>px;
							 	height: <?= $big_pic_height_300 ?>px;
							 	top: <?= $small_pic_height_300 ?>px;
							  	left: <?= $big_pic_width_300 ?>px;
							}
							.powergallery-entry:nth-child(6), .powergallery-entry:nth-child(7), .powergallery-entry:nth-child(8) { 
								top: <?= $small_pic_height_300 + $big_pic_height_300 ?>px;
							}
							.powergallery-entry:nth-child(9), .powergallery-entry:nth-child(10) { 
								top: <?= ( 2 * $small_pic_height_300 ) + $big_pic_height_300 ?>px;
							}
							.powergallery-entry:nth-child(11), .powergallery-entry:nth-child(12), .powergallery-entry:nth-child(13) { 
								top: <?= 2 * ( $small_pic_height_300 + $big_pic_height_300 ) ?>px;
							}
							.powergallery-entry:nth-child(14), .powergallery-entry:nth-child(15) { 
								top: <?= 2 * ( ( 2 * $small_pic_height_300 ) + $big_pic_height_300 ) ?>px;
							}
							.powergallery-entry:nth-child(16), .powergallery-entry:nth-child(17), .powergallery-entry:nth-child(18) { 
								top: <?= 3 * ( $small_pic_height_300 + $big_pic_height_300 ) ?>px;
							}
							.powergallery-entry:nth-child(19), .powergallery-entry:nth-child(20) { 
								top: <?= 3 * ( ( 2 * $small_pic_height_300 ) + $big_pic_height_300 ) ?>px;
							}
						}

					<?php endif ?>
					
					<?php if ( $powergallery_css != '' ) echo $powergallery_css; ?>
				</style>

				<script>
					jQuery(document).ready(function($){
				        var lg = $('#powergallery-<?= get_the_ID(); ?> > .powergallery-int').lightGallery({
							selector: '.powergallery-entry',
							thumbnail: true
						});
						lg.on('onBeforeOpen.lg',function(event){
						    $('#main-header').css('z-index', '-1');
						});
						lg.on('onBeforeClose.lg',function(event){
						    $('#main-header').css('z-index', '99999');

						});
						if ( ! Modernizr.objectfit ) {
						  	$('#powergallery-<?= get_the_ID(); ?> > .powergallery-int > powergallery-entry').each(function () {
						    	var $container = $(this),
						        	imgUrl = $container.find('img').prop('src');
						    	if (imgUrl) {
						      		$container
						      		.find('img')
						        	.css('background-image', 'url(' + imgUrl + ')')
						        	.css('background-size', 'cover')
						        	.css('background-position', 'center center')
						        	.prop('src', '');
						    	}  
							});
						}
					});
				</script>

            <?php endwhile;
        	wp_reset_postdata(); 

        	$powergallery = ob_get_clean();

    		return $powergallery;

    	}

    }

    /**
	 * Define the shortcode for the MenuBar post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_menubar_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'menubar',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

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

			$menubar_css = ! isset( $meta['menubar_css'][0] ) ? '' : $meta['menubar_css'][0];

			$menubar_hamburger_pic_src = $menubar_hamburger_pic != '' ? wp_get_attachment_url( $menubar_hamburger_pic ) : '';

			$menubar_social_fb_pic_src = $menubar_social_fb_pic != '' ? wp_get_attachment_url( $menubar_social_fb_pic ) : '';
			$menubar_social_yt_pic_src = $menubar_social_yt_pic != '' ? wp_get_attachment_url( $menubar_social_yt_pic ) : '';
			$menubar_social_tw_pic_src = $menubar_social_tw_pic != '' ? wp_get_attachment_url( $menubar_social_tw_pic ) : '';
			$menubar_social_ig_pic_src = $menubar_social_ig_pic != '' ? wp_get_attachment_url( $menubar_social_ig_pic ) : '';
			
			$menu_locations = get_nav_menu_locations();
			$menu_items = wp_get_nav_menu_items(wp_get_nav_menu_object( $menu_locations[$menubar_menu_id] ) );
 
		    $menu_list = '<ul id="menu-' . $menubar_menu_id . '">';
		    $count = 0;
        	$submenu = false;
		 
		    foreach ( (array) $menu_items as $key => $menu_item ) {
		        $title = $menu_item->title;
		        $link = $menu_item->url;
		        if ( !$menu_item->menu_item_parent ) {
                	$parent_id = $menu_item->ID;
		        	$menu_list .= '<li><a href="'.$link.'">'.$title.'</a>';
		        }
		        if ( $parent_id == $menu_item->menu_item_parent ) { 
	                if ( !$submenu ) {
	                    $submenu = true;
	                    $menu_list .= '<ul>';
	                }	 
	                $menu_list .= '<li class="item">';
	                $menu_list .= '<a href="'.$link.'">'.$title.'</a>';
	                $menu_list .= '</li>';	 
	                if ( isset( $menu_items[ $count + 1 ] ) && $menu_items[ $count + 1 ]->menu_item_parent != $parent_id && $submenu ){
	                    $menu_list .= '</ul>';
	                    $submenu = false;
	                }	 
	            }	 
	            if ( isset( $menu_items[ $count + 1 ] ) && $menu_items[ $count + 1 ]->menu_item_parent != $parent_id ) { 
	                $menu_list .= '</li>';      
	                $submenu = false;
	            }	 
	            $count++;
			}
		    $menu_list .= '</ul>';
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<div id="menubar-<?= get_the_ID(); ?>" class="menubar">
					<a href="#menubar-<?= get_the_ID(); ?>-menu" class="menubar-hamburger-container">
						<span class="menubar-hamburger"></span>
						<?php if ( isset( $meta['menubar_hamburger_caption'] ) && $meta['menubar_hamburger_caption'] != '' ): ?>
							<span class="menubar-hamburger-caption"><?= $menubar_hamburger_caption ?></span>
						<?php endif ?>
					</a>
					<nav id="menubar-<?= get_the_ID(); ?>-menu">
						<?= $menu_list ?>
					</nav>
					<ul id="menubar-social">
						<?php if ( $menubar_social_fb_link != '' ): ?>
							<li><a href="https://www.facebook.com/<?= $menubar_social_fb_link ?>" class="menubar-social-element" id="menubar-social-fb"></a></li>
						<?php endif ?>
						<?php if ( $menubar_social_yt_link != '' ): ?>
							<li><a href="https://www.youtube.com/user/<?= $menubar_social_yt_link ?>" class="menubar-social-element" id="menubar-social-yt"></a></li>
						<?php endif ?>
						<?php if ( $menubar_social_tw_link != '' ): ?>
							<li><a href="https://www.twitter.com/<?= $menubar_social_tw_link ?>" class="menubar-social-element" id="menubar-social-tw"></a></li>
						<?php endif ?>
						<?php if ( $menubar_social_ig_link != '' ): ?>
							<li><a href="https://www.instagram.com/<?= $menubar_social_ig_link ?>" class="menubar-social-element" id="menubar-social-ig"></a></li>
						<?php endif ?>
						
					</ul>
				</div>

				<style>
					#menubar-<?= get_the_ID(); ?> {
						position: relative;
						height: <?= $menubar_height ?>px;
						width: <?= $menubar_width ?>px;
						<?php if ( $menubar_background != '' ): ?>
							background-color: <?= $menubar_background ?>;
						<?php endif ?>
					}

					#menubar-<?= get_the_ID(); ?> .menubar-hamburger-container {
						position: absolute;
						top: <?= $menubar_hamburger_top ?>px;
						right: <?= $menubar_hamburger_right ?>px;
						bottom: <?= $menubar_hamburger_bottom ?>px;
						left: <?= $menubar_hamburger_left ?>px;
					}
					#menubar-<?= get_the_ID(); ?> .menubar-hamburger-container:hover {
						cursor: pointer;
					}
					#menubar-<?= get_the_ID(); ?> span.menubar-hamburger {
						height: <?= $menubar_hamburger_height ?>px;
						width: <?= $menubar_hamburger_width ?>px;
						display:  inline-block;
						background: url(<?php echo $menubar_hamburger_pic_src; ?>);
					}
					#menubar-<?= get_the_ID(); ?> span.menubar-hamburger-caption {
						display:  inline-block;
						height: <?= $menubar_hamburger_height ?>px;
						line-height: <?= $menubar_hamburger_height ?>px;
					}

					#menubar-<?= get_the_ID(); ?> .menubar-menu {
						
					}

					#menubar-<?= get_the_ID(); ?> .menubar-social { 
						display: block;
						position: absolute;
						top: <?= $menubar_social_top ?>px;
						right: <?= $menubar_social_right ?>px;
						bottom: <?= $menubar_social_bottom ?>px;
						left: <?= $menubar_social_left ?>px;
					}
					#menubar-<?= get_the_ID(); ?> .menubar-social li { 
						display: inline-block;
					}
					#menubar-<?= get_the_ID(); ?> .menubar-social li a.menubar-social-element { 
						display: block;
						height: <?= $menubar_social_height ?>px;
						width: <?= $menubar_social_width ?>px;
						margin-right: <?= $menubar_social_margin ?>px;
					}

					<?php if ( $menubar_social_fb_pic != '' ): ?>
						#menubar-social-fb { background: url(<?= $menubar_social_fb_pic_src; ?>); }
					<?php endif ?>
					<?php if ( $menubar_social_yt_pic != '' ): ?>
						#menubar-social-yt { background: url(<?= $menubar_social_yt_pic_src; ?>); }
					<?php endif ?>
					<?php if ( $menubar_social_tw_pic != '' ): ?>
						#menubar-social-tw { background: url(<?= $menubar_social_tw_pic_src; ?>); }
					<?php endif ?>
					<?php if ( $menubar_social_ig_pic != '' ): ?>
						#menubar-social-ig { background: url(<?= $menubar_social_fb_pic_src; ?>); }
					<?php endif ?>
					
					<?= $menubar_css; ?>
				</style>

				<script>
					jQuery(document).ready(function($){

				        //menu
						$("#menubar-<?= get_the_ID(); ?>-menu").mmenu({
							<?php if ( $menubar_menu_type == 1): ?>
								autoHeight: true,
								dropdown: true,
							<?php endif ?>
							<?php if ( $menubar_menu_type == 0): ?>
								navbars:[{
									position:"top",
									content:[
										"prev",
										"title",
										"close"
									]
								}],
					        	drag: true,
							<?php endif ?>
							extensions: [
								<?php if ( $menubar_menu_theme == 1): ?>
									"theme-dark",
								<?php endif ?>
								<?php if ( $menubar_menu_theme == 2): ?>
									"theme-white",
								<?php endif ?>
								<?php if ( $menubar_menu_theme == 3): ?>
									"theme-black",
								<?php endif ?>
								<?php if ( $menubar_menu_style == 0): ?>
									"fx-menu-slide",
									"fx-panels-slide-100",
									"fx-listitems-slide",
								<?php endif ?>
								<?php if ( $menubar_menu_style == 1): ?>
									"fx-menu-fade",
						            "fx-panels-none",
						            "fx-listitems-fade",
								<?php endif ?>
								<?php if ( $menubar_menu_style == 2): ?>
									"fx-menu-zoom",
            						"fx-panels-zoom",
								<?php endif ?>
								"multiline",
							],
							lazySubmenus: true,
							keyboardNavigation: true,
							screenReader: true,
							backButton: true,							
						});
					});
				</script>

            <?php endwhile;
        	wp_reset_postdata(); 

        	$menubar = ob_get_clean();

    		return $menubar;

    	}

    }

    /**
	 * Define the shortcode for the Evidences post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_evidences_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'evidences',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

        	$evidences_title = ! isset( $meta['evidences_title'][0] ) ? 0 : $meta['evidences_title'][0];

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

			$evidences_text = ! isset( $meta['evidences_text'][0] ) ? array() :  maybe_unserialize( $meta['evidences_text'][0] );
			$evidences_extra = ! isset( $meta['evidences_extra'][0] ) ? array() :  maybe_unserialize( $meta['evidences_extra'][0] );
			$evidences_link = ! isset( $meta['evidences_link'][0] ) ? array() :  maybe_unserialize( $meta['evidences_link'][0] );

			$evidences_single_width = intval( ( ($evidences_width ) - ( 2 * $evidences_columns * $evidences_border) - ( 2 * $evidences_columns * $evidences_margin ) ) / $evidences_columns );
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<div id="evidences-<?= get_the_ID(); ?>" class="evidences features<?php if ( $evidences_type == 1 ) { echo ' clearfix'; }?>">
					<?php if ( $evidences_title != '' ): ?>
						<h2><?= $evidences_title ?> </h2>
					<?php endif ?>					
					<ul id="evidences-<?= get_the_ID(); ?>-list" class="evidences-list <?= $evidences_pagination == 1 ? 'list' : ''; ?>">
						<?php 
						foreach ($evidences_text as $key => $value) { 
							if ( isset( $evidences_text[$key] ) && $evidences_text[$key] != '' && $evidences_text[$key] != NULL ) { ?>
								<li>
									<?php if ($evidences_type == 0): ?>
										<a style="cursor: pointer;" class="" id="evidence_<?= $key ?>""><?= $evidences_text[$key] ?></a>
									<?php endif ?>
									<?php if ( $evidences_type == 1 ): ?>
										<a href="<?= $evidences_link[$key] ?>" class="tile_container">
										<span class="slidetile">
											<span class="tile_left" id="tile_left_<?= $key ?>"><?= $evidences_text[$key] ?></span>
											<?php if ( $evidences_type != 0 ): ?>
												<?php if ( isset( $evidences_extra[$key] ) && $evidences_extra[$key] != '' ): ?>
													<span class="tile_right"><?= $evidences_extra[$key] ?></span>
												<?php endif ?>
											<?php endif ?>											
										</span>
									</a>
									<?php endif ?>									
								</li>
							<?php }
						}
						?>	
					</ul>
					<?php if ( $evidences_pagination == 1 ): ?>
						<nav class="pagination"></ul>	
					<?php endif ?>								
				</div>

				<style>
					
					#evidences-<?= get_the_ID(); ?> {
						<?php if ( $evidences_type == 1 ): ?>
							margin-left: calc(50% - <?= $evidences_width / 2 ?>px);
						<?php endif ?>	
					}
					#evidences-<?= get_the_ID(); ?> h2 {
						
					}
					#evidences-<?= get_the_ID(); ?> ul {
						padding: 0;
						list-style: none;					    
					    width: <?= $evidences_width ?>px;
					    display: block;
					    margin: auto;
					}
					#evidences-<?= get_the_ID(); ?> li {
						<?php if ( $evidences_type == 1 ): ?>
							display: block;
						    float: left;
						    width: <?= $evidences_single_width ?>px;
						    height: <?= $evidences_height ?>px;
						    overflow: hidden;
						    border: <?= $evidences_border ?>px solid <?= $evidences_border_color ?>;
						    margin: <?= $evidences_margin ?>px;
						    padding: 0;
						<?php endif ?>						
						<?php if ( $evidences_type == 0 ): ?>
							display: inline-block;
						    width: calc(<?php echo (100 / $evidences_columns); ?>% - <?= $evidences_margin ?>px );
						    height: 31px;
						    margin-right: <?= $evidences_margin ?>px;
					    <?php endif ?>
					}
					<?php if ( $evidences_type == 0 ): ?>
					#evidences-<?= get_the_ID(); ?> li:nth-child(3n+ 0) {
						margin-right: 0px;
					}
					<?php endif ?>

					<?php if ( $evidences_type == 1 ): ?>
						#evidences-<?= get_the_ID(); ?> a.tile_container {						
							-webkit-perspective: 800px;
						    -ms-perspective: 800px;
						    perspective: 800px;
						    -webkit-transform-style: flat;
						    transform-style: flat;
						    display:block;
						    height:<?= $evidences_height ?>px;
						    width:340px;						
						}
					<?php endif ?>

					<?php if ( $evidences_type == 1 ): ?>
						#evidences-<?= get_the_ID(); ?> span.slidetile {						
						    display:block;
						    height:<?= $evidences_height ?>px;
						    position:relative;
						    width:<?= $evidences_single_width ?>px;						
						}
					<?php endif ?>
					<?php if ( $evidences_type == 1 ): ?>
						#evidences-<?= get_the_ID(); ?> span.tile_left {
						
						 	background: <?= $evidences_text_background ?>;
						    display:block;
					    	height:100%;
						    left:0px;
						    position:absolute;
						    top:0px;
						    -webkit-transition-delay:0s;
						            transition-delay:0s;
						    -webkit-transition-duration:0.5s;
						            transition-duration:0.5s;
						    -webkit-transition-property:left;
						    transition-property:left;
						    -webkit-transition-timing-function:ease;
						            transition-timing-function:ease;
						    width:100%;					    
						}
					<?php endif ?>
					<?php if ( $evidences_type == 1 ): ?>
						#evidences-<?= get_the_ID(); ?> span.tile_right {						
						 	background: <?= $evidences_extra_background ?>;
						    display:block;
						    height:100%;
						    left:<?= $evidences_single_width ?>px;
						    position:absolute;
						    text-align:center;
						    top:0px;
						    -webkit-transition-delay:0s;
						            transition-delay:0s;
						    -webkit-transition-duration:0.5s;
						            transition-duration:0.5s;
						    -webkit-transition-property:left;
						    transition-property:left;
						    -webkit-transition-timing-function:ease;
						            transition-timing-function:ease;
						    width:100%;					    
						}
					<?php endif ?>
					<?php if ( $evidences_type == 1 ): ?>
						#evidences-<?= get_the_ID(); ?> .slidetile:hover .tile_left {
						   left: -100%;						    
						}
					<?php endif ?>

					<?php if ( $evidences_type == 1 ): ?>
						#evidences-<?= get_the_ID(); ?> .slidetile:hover .tile_right {						    
						    	left: 0;						    				    
						}
					<?php endif ?>	

					<?php if ( $evidences_type == 0 ): ?>
						.evidence {
							border: <?= $evidences_border ?>px solid <?= $evidences_border_color ?>;
							background:  <?= $evidences_extra_background ?>;
						}
					<?php endif ?>

					<?php if ( $evidences_pagination == 1 ): ?>
						<?= $evidences_pagination_css ?>

						#evidences-<?= get_the_ID(); ?> > nav.paginate-pagination > div > span > a.page-prev, #evidences-<?= get_the_ID(); ?> > nav.paginate-pagination > div > span > a.page-next {
							display: none;
						}
					<?php endif ?>

					<?= $evidences_css; ?>
				</style>

				<script>
					jQuery(document).ready(function($){
				        
				        <?php if ( $evidences_pagination == 1 ): ?>
				        	var resp = false;

				        	if ($( window ).width() <= 767) {
						        $('#evidences-<?= get_the_ID(); ?>-list').paginate({
						        	perPage: 				3,
						        	paginatePosition: 		['bottom'],
						        	scope: 					$('li'),
	  								containerTag:           'nav',
	  								paginationTag:          'div',
	  								itemTag:                'span',
	  								linkTag:                'a',
	  								useHashLocation:        false
						        });
						        resp = !resp;
						    }
						    $(window).on('resize', function(){
							    if ($(this).width() <= 767) {
							    	if (!resp) {
							    		$('#evidences-<?= get_the_ID(); ?>-list').paginate({
								        	perPage: 				3,
								        	paginatePosition: 		['bottom'],
								        	scope: 					$('li'),
			  								containerTag:           'nav',
			  								paginationTag:          'div',
			  								itemTag:                'span',
			  								linkTag:                'a',
								        });
						        		resp = !resp;
							    	}
							    }
							    if ($(this).width() >= 768) {
							    	if (resp) {
							    		$('#evidences-<?= get_the_ID(); ?>-list').data('paginate').kill();
						        		resp = !resp;
							    	}
							    }
							});
				        <?php endif ?>

				        <?php if ($evidences_type == 0): ?>
				        	<?php foreach ($evidences_text as $key => $value) { 
								if ( isset( $evidences_text[$key] ) && $evidences_text[$key] != '' && $evidences_text[$key] != NULL ) { ?>
				        		 	$('#evidence_<?= $key ?>').qtip({
									    content: {
									        text: '<?= $evidences_extra[$key] ?>',
									    },
									    style: {
									    	classes: 'evidence',
									    	tip: false,
									    },
									    position: {
									    	my: 'bottom left',
    										at: 'top right',
										},								        
    									show: {
									        effect: function(offset)
									        {
									            $(this).slideDown(300);
									        },
									    },
									    hide: {
									        effect: function(offset) {
									            $(this).slideUp(300);
									        },
									    },
									});
				        		<?php }
				        	} ?>
				        	
				        <?php endif ?>
					});
				</script>

            <?php endwhile;
        	wp_reset_postdata(); 

        	$evidences = ob_get_clean();

    		return $evidences;

    	}

    }

    /**
	 * Define the shortcode for the Headline post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_headline_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'headline',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

        	$headline_title = ! isset( $meta['headline_title'][0] ) ? '' : $meta['headline_title'][0];
			$headline_pic = ! isset( $meta['headline_pic'][0] ) ? '' : $meta['headline_pic'][0];

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
					
			$headline_pic_src = $headline_pic != '' ? wp_get_attachment_url( $headline_pic ) : '';
			$headline_logo_pic_src = $headline_logo_pic != '' ? wp_get_attachment_url( $headline_logo_pic ) : '';
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<div id="headline-<?= get_the_ID(); ?>" class="headline">
					<div id="headlineimg-<?= get_the_ID(); ?>" class="headlineimg" style="background-image: url(<?php echo $headline_pic_src; ?>);">
						<?php if ( $headline_title != '' ): ?>
							<h1><?php echo $headline_title; ?></h1>
						<?php endif ?>
						<?php if ( $headline_logo_pic != '' ): ?>
							<a id="headline_logo-<?= get_the_ID(); ?>" class="headline-logo" style="background: url(<?= $headline_logo_pic_src ?>)" href="<?= $headline_logo_link != '' ? $headline_logo_link : '#' ?>"></a>
						<?php endif ?>
					</div>					

					<style>
						#headline-<?= get_the_ID(); ?> {
							width: <?= $headline_width ?>px;
							height: <?= $headline_height ?>px;
							margin: auto;
							position: relative;
						}
						#headlineimg-<?= get_the_ID(); ?> {
							width: <?= $headline_width ?>px;
							height: <?= $headline_height ?>px;
							background-position: center center;
						    background-repeat: no-repeat;
						    background-size: cover;
						}
						<?php if ( $headline_logo_pic != '' ): ?>
							#headline_logo-<?= get_the_ID(); ?> {
								display: block;
								position: absolute;
								width: <?php echo esc_html( $headline_logo_width ); ?>px;
								height: <?php echo esc_html( $headline_logo_height ); ?>px;
								<?php if ($headline_logo_top != ''): ?>
									top: <?php echo esc_html( $headline_logo_top ); ?>px;
								<?php endif ?>
								<?php if ($headline_logo_top != ''): ?>
								top: <?php echo esc_html( $headline_logo_top ); ?>px;
								<?php endif ?>
								<?php if ($headline_logo_top != ''): ?>
								right: <?php echo esc_html( $headline_logo_right ); ?>px;
								<?php endif ?>
								<?php if ($headline_logo_top != ''): ?>
								bottom: <?php echo esc_html( $headline_logo_bottom ); ?>px;
								<?php endif ?>
								<?php if ($headline_logo_top != ''): ?>
								left: <?php echo esc_html( $headline_logo_left ); ?>px;
								<?php endif ?>
								cursor: pointer;
							}
						<?php endif ?>

						@media (max-width: 1499px) and (min-width: 960px) {
							#headline-<?= get_the_ID(); ?>, #headlineimg-<?= get_the_ID(); ?>
							{
								width: 100%; 
								height: 600px; 
							}
						}
						@media (max-width: 959px) and (min-width: 768px) {
							#headline-<?= get_the_ID(); ?>, #headlineimg-<?= get_the_ID(); ?>
							{
								width: 100%; 
								height: 480px; 
							}
						}
						@media (max-width: 767px) and (min-width: 480px) {
							#headline-<?= get_the_ID(); ?>, #headlineimg-<?= get_the_ID(); ?>
							{
								width: 100%; 
								height: 390px; 
							}
						}
						@media (max-width: 479px) {
							#headline-<?= get_the_ID(); ?>, #headlineimg-<?= get_the_ID(); ?>
							{
								width: 100%; 
								height: 390px; 
							}
						}

						<?= $headline_css; ?>
					</style>
					<script>
						jQuery(document).ready(function($){


						});
					</script>
				</div>

            <?php endwhile;
        wp_reset_postdata(); ?>

    	<?php $headline = ob_get_clean();

    	return $headline;

    	}

    }

    /**
	 * Define the shortcode for the Footer post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_footer_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'footer',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

			$footer_css = ! isset( $meta['footer_css'][0] ) ? '' : $meta['footer_css'][0];
					
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<div id="footer-<?= $query->get_queried_object_id() ?>">
					
				</div>
				<style>
					<?= $footer_css ?>
				</style>
				<script>

				</script>

            <?php endwhile;
        wp_reset_postdata(); ?>

    	<?php $footer = ob_get_clean();

    	return $footer;

    	}

    }

    /**
	 * Define the shortcode for the Call to Action post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_calltoaction_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'calltoaction',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

        	$calltoaction_text = ! isset( $meta['calltoaction_text'][0] ) ? '' : $meta['calltoaction_text'][0];
        	$calltoaction_link = ! isset( $meta['calltoaction_link'][0] ) ? '' : $meta['calltoaction_link'][0];

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
					
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<div id="calltoaction-<?= $query->get_queried_object_id() ?>">
					<a style="display: block;" href="<?= $calltoaction_link ?>"><?= $calltoaction_text ?></a>
				</div>
				<style>
					#calltoaction-<?= $query->get_queried_object_id() ?> {
						width: <?= $calltoaction_width ?>px;
						height: <?= $calltoaction_height ?>px;
					}

					#calltoaction-<?= $query->get_queried_object_id() ?> a {
						box-shadow: none!important;
						width: <?= $calltoaction_width ?>px;
						height: <?= $calltoaction_height ?>px;
						line-height: <?= $calltoaction_height ?>px;
						font-size: <?= $calltoaction_text_size ?>px;
						font-weight: <?= $calltoaction_text_weight ?>;
						text-decoration: <?= $calltoaction_text_decoration ?>;
						color: <?= $calltoaction_text_color ?>;
						background-color: <?= $calltoaction_bg_color ?>;
						border-radius: <?= $calltoaction_border_radius ?>px;
						border-width: <?= $calltoaction_border_width ?>px;
						border-style: solid;
						border-color: <?= $calltoaction_border_color ?>;
						text-align: center;
						<?php if ( $calltoaction_hover_effect == 1): ?>
							transition: color <?= $calltoaction_hover_duration ?>s, background-color <?= $calltoaction_hover_duration ?>s, border-color <?= $calltoaction_hover_duration ?>s;
						<?php endif ?>
						<?php if ( $calltoaction_hover_effect == 2): ?>
							background: linear-gradient(to right, <?= $calltoaction_bg_color ?> 0%,<?= $calltoaction_bg_color ?> 50%,<?= $calltoaction_hover_bg_color ?> 50%, <?= $calltoaction_hover_bg_color ?> 100%);
							background-size: 200% 100%;
							background-position: left center;
							transition: color <?= $calltoaction_hover_duration ?>s, border-color <?= $calltoaction_hover_duration ?>s, background-position <?= $calltoaction_hover_duration ?>s;
						<?php endif ?>
					}
					<?php if ( $calltoaction_hover_effect != 0): ?>
						#calltoaction-<?= $query->get_queried_object_id() ?> a:hover {
							font-weight: <?= $calltoaction_hover_text_weight ?>;
							<?php if ( $calltoaction_hover_effect == 1): ?>
								color: <?= $calltoaction_hover_text_color ?>;
								background-color: <?= $calltoaction_hover_bg_color ?>;
								border-color: <?= $calltoaction_border_color ?>;
							<?php endif ?>
							<?php if ( $calltoaction_hover_effect == 2): ?>
								color: <?= $calltoaction_hover_text_color ?>;
								border-color: <?= $calltoaction_border_color ?>;
								background-position: right center;
							<?php endif ?>							
						}
					<?php endif ?>

					<?= $calltoaction_css ?>
				</style>
				<script>

				</script>

            <?php endwhile;
        wp_reset_postdata(); ?>

    	<?php $calltoaction = ob_get_clean();

    	return $calltoaction;

    	}

    }

    /**
	 * Define the shortcode for the Social Buttons post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_socialbuttons_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'socialbuttons',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

        	$socialbuttons_width = ! isset( $meta['socialbuttons_width'][0] ) ? 30 : $meta['socialbuttons_width'][0];
			$socialbuttons_height = ! isset( $meta['socialbuttons_height'][0] ) ? 30 : $meta['socialbuttons_height'][0];
			$socialbuttons_margin = ! isset( $meta['socialbuttons_margin'][0] ) ? 20 : $meta['socialbuttons_margin'][0];

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

			$socialbuttons_css = ! isset( $meta['socialbuttons_css'][0] ) ? '' : $meta['socialbuttons_css'][0];

			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<ul id="socialbuttons-<?= $id ?>">
					<?php if ( $socialbuttons_fb_link != '' ): ?>
						<li><a href="https://www.facebook.com/<?= $socialbuttons_fb_link ?>" class="socialbuttons-element" id="socialbuttons-fb" target="_blank"></a></li>
					<?php endif ?>
					<?php if ( $socialbuttons_yt_link != '' ): ?>
						<li><a href="https://www.youtube.com/channel/<?= $socialbuttons_yt_link ?>" class="socialbuttons-element" id="socialbuttons-yt" target="_blank"></a></li>
					<?php endif ?>
					<?php if ( $socialbuttons_tw_link != '' ): ?>
						<li><a href="https://www.twitter.com/<?= $socialbuttons_tw_link ?>" class="socialbuttons-element" id="socialbuttons-tw" target="_blank"></a></li>
					<?php endif ?>
					<?php if ( $socialbuttons_ig_link != '' ): ?>
						<li><a href="https://www.instagram.com/<?= $socialbuttons_ig_link ?>" class="socialbuttons-element" id="socialbuttons-ig" target="_blank"></a></li>
					<?php endif ?>
					<?php if ( $socialbuttons_ta_link != '' ): ?>
						<li><a href="https://www.tripadvisor.com/<?= $socialbuttons_ta_link ?>" class="socialbuttons-element" id="socialbuttons-ta" target="_blank"></a></li>
					<?php endif ?>
					<?php if ( $socialbuttons_ln_link != '' ): ?>
						<li><a href="https://it.linkedin.com/in/<?= $socialbuttons_ln_link ?>" class="socialbuttons-element" id="socialbuttons-ln" target="_blank"></a></li>
					<?php endif ?>
				</ul>

				<style>
					ul#socialbuttons-<?= $id ?> { 
						display: block;
						position: absolute;
					}
					#socialbuttons-<?= $id ?> li { 
						display: inline-block;
					}
					#socialbuttons-<?= $id ?> li a.socialbuttons-element { 
						display: block;
						height: <?= $socialbuttons_height ?>px;
						width: <?= $socialbuttons_width ?>px;
						margin-right: <?= $socialbuttons_margin ?>px;
						background-position: top center;
						background-repeat: no-repeat;
					}
					#socialbuttons-<?= $id ?> li a.socialbuttons-element:hover { 
						background-position: bottom center;
						cursor: pointer;
					}

					<?php if ( $socialbuttons_fb_pic != '' ): ?>
						#socialbuttons-<?= $id ?> #socialbuttons-fb { 
							background-image: url(<?= $socialbuttons_fb_pic_src; ?>); 
						}
					<?php endif ?>
					<?php if ( $socialbuttons_yt_pic != '' ): ?>
						#socialbuttons-<?= $id ?> #socialbuttons-yt { 
							background-image: url(<?= $socialbuttons_yt_pic_src; ?>); 
						}
					<?php endif ?>
					<?php if ( $socialbuttons_tw_pic != '' ): ?>
						#socialbuttons-<?= $id ?> #socialbuttons-tw { 
							background-image: url(<?= $socialbuttons_tw_pic_src; ?>); 
						}
					<?php endif ?>
					<?php if ( $socialbuttons_ig_pic != '' ): ?>
						#socialbuttons-<?= $id ?> #socialbuttons-ig { 
							background-image: url(<?= $socialbuttons_ig_pic_src; ?>); 
						}
					<?php endif ?>
					<?php if ( $socialbuttons_ta_pic != '' ): ?>
						#socialbuttons-<?= $id ?> #socialbuttons-ta { 
							background-image: url(<?= $socialbuttons_ta_pic_src; ?>); 
						}
					<?php endif ?>
					<?php if ( $socialbuttons_ln_pic != '' ): ?>
						#socialbuttons-<?= $id ?> #socialbuttons-ln { 
							background-image: url(<?= $socialbuttons_ln_pic_src; ?>); 
						}
					<?php endif ?>
					<?= $socialbuttons_css; ?>
				</style>

				<script>
					jQuery(document).ready(function($){
						
					});
			    </script>

            <?php endwhile;
        wp_reset_postdata(); ?>

    	<?php $socialbuttons = ob_get_clean();

    	return $socialbuttons;

    	}

    }

    /**
	 * Define the shortcode for the Social Buttons post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_specialone_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'specialone',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

			$specialone_css = ! isset( $meta['specialone_css'][0] ) ? '' : $meta['specialone_css'][0];

			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<div id="specialone-<?= $id ?>">
					<div class="left">
					    <div class="left-int">
					      <img src="http://elk-lab.com/chaumiere/wp-content/uploads/2018/03/square_home1.jpg">
					    </div>
					  </div>
					  <div class="right">
					    <div class="right-int">
					      <div class="left-top small">
					        <img class="grayscale" src="http://elk-lab.com/chaumiere/wp-content/uploads/2018/03/square_home2.jpg">
					        <div class="detail">
					          <span class="detail-title">Gourmet</span>
					          <span class="detail-text">Degustare con calma i piaceri della <b>grande gastronomia valdostana.</b></span>
					          <a href="/chaumiere/il-gourmet/" class="detail-link">Scopri di più</a>
					        </div>
					      </div>
					      <div class="right-top small">
					        <img class="grayscale" src="http://elk-lab.com/chaumiere/wp-content/uploads/2018/03/square_home3.jpg">
					        <div class="detail">
					          <span class="detail-title">Bistrot</span>
					          <span class="detail-text">Una <b>pausa veloce</b> e di gran gusto prima di rientrare con energia sulle piste.</span>
					          <a href="chaumiere/il-bistrot/" class="detail-link">Scopri di più</a>
					        </div>
					      </div>
					       <div class="right-bottom small">
					        <img class="grayscale" src="http://elk-lab.com/chaumiere/wp-content/uploads/2018/03/square_home5.jpg">
					        <div class="detail">
					          <span class="detail-title">B2B</span>
					          <span class="detail-text">La tua location perfetta a Courmayeur per gli <b>eventi aziendali</b>.</span>
					          <a href="chaumiere/b2b/" class="detail-link">Scopri di più</a>
					        </div>
					      </div>
					      <div class="left-bottom small">
					        <img class="grayscale" src="http://elk-lab.com/chaumiere/wp-content/uploads/2018/03/square_home4.jpg">
					        <div class="detail">
					          <span class="detail-title">Serate in baita</span>
					          <span class="detail-text">Il <b>piacere del cibo</b> più goloso abbinato al tramonto al cospetto del monte bianco.</span>
					          <a href="/chaumiere/serate-in-baita/" class="detail-link">Scopri di più</a>
					        </div>
					      </div>     
					    </div>
					  </div>
				</div>

				<style>
					#specialone-<?= $id ?> {
						width: 1290px;
						padding: 45px;
						position: relative;
						background-color: #CE0231;
						font-family: 'Barlow Semi Condensed', sans-serif;
						margin: auto;
					}
					#specialone-<?= $id ?> .left {
						width: 590px;
						height: 590px;
						display: inline-block;
						position: relative;
						background-color: #FFFFFF;
					}
					#specialone-<?= $id ?> .left-int {
						width: 590px;
						height: 590px;
						padding: 45px;
						-moz-background-size: 500px;
						     background-size: 500px;
						background-position: center center;
						background-repeat: no-repeat;
					}
					#specialone-<?= $id ?> .left-int img {
						display: block;
						height: 500px;
						width: 500px;
						-o-object-fit: cover;
						object-fit: cover;
					}
					#specialone-<?= $id ?> .right {
						width: 590px;
						height: 590px;
						display: inline-block;
						position: relative;
						float: right;
					}
					#specialone-<?= $id ?> .right-int {
						width: 100%;
						height: 100%;
						position: relative;
					}
					#specialone-<?= $id ?> .right-int img {
						display: inline-block;
						height: 100%;
						width: 100%;
						-o-object-fit: cover;
						object-fit: cover;
						position: absolute;
						top: 0;
						left: 0;
					}
					#specialone-<?= $id ?> .small {
						width: 290px;
						height: 290px;
						display: inline-block;
					}
					#specialone-<?= $id ?> .left-top {
						position: absolute;
						top: 0;
						left: 0;
					}
					#specialone-<?= $id ?> .right-top {
						position: absolute;
						top: 0;
						right: 0;
					}
					#specialone-<?= $id ?> .right-bottom {
						position: absolute;
						bottom: 0;
						right: 0;
					}
					#specialone-<?= $id ?> .left-bottom {
						position: absolute;
						bottom: 0;
						left: 0;
					}
					#specialone-<?= $id ?> .detail {
						display: none;
						position: absolute;
						top: 0;
						left: 0;
						width: 290px;
						height: 290px;
						background: rgba(255, 255, 255, 0.8);
					}
					#specialone-<?= $id ?> .detail-title {
						display: block;
						position: absolute;
						top: 32px;
						left: 0;
						width: 160px;
						height: 50px;
						background: #CE0231;
						line-height: 50px;
						color: #FFFFFF;
						padding-left: 20px;
						font-size: 24px;
					}
					#specialone-<?= $id ?> .detail-link {
						display: block;
						position: absolute;
						bottom: 40px;
						left: -webkit-calc(50% - 120px);
						left: calc(50% - 120px);
						width: 240px;
						height: 50px;
						line-height: 50px;
						font-size: 20px;
						text-transform: uppercase;
						text-align: center;
						text-decoration: none;
						border: 1px solid #CE0231;
						color: #CE0231;
						-webkit-transition: background-color 0.5s, color 0.5s;
						transition: background-color 0.5s, color 0.5s;
					}
					#specialone-<?= $id ?> .detail-link:hover {
						text-decoration: none;
						background-color: #CE0231;
						color: #FFFFFF;
					}
					#specialone-<?= $id ?> .detail-text {
						display: block;
						position: absolute;
						top: 100px;
						left: -webkit-calc(50% - 120px);
						left: calc(50% - 120px);
						width: 240px;
						height: 65px;
						line-height: 22px;
						font-size: 18px;
						text-align: left;
						text-decoration: none;
						color: #555555;
						overflow: hidden;
					}
					@media (max-width: 1499px) and (min-width: 960px) {
						#specialone-<?= $id ?> {
						    width: 960px;
						    padding: 35px;
						}
						#specialone-<?= $id ?> .left {
						    width: 440px;
						    height: 440px;
						}
						#specialone-<?= $id ?> .left-int {
						    width: 440px;
						    height: 440px;
						    padding: 36.5px;
						    -moz-background-size: 367px;
						         background-size: 367px;
						}
						#specialone-<?= $id ?> .left-int img {
						    height: 367px;
						    width: 367px;
						}
						#specialone-<?= $id ?> .right {
						    width: 440px;
						    height: 440px;
						}
						#specialone-<?= $id ?> .small {
						    width: 215px;
						    height: 215px;
						}
						#specialone-<?= $id ?> .detail {
						    width: 215px;
						    height: 215px;
						}
						#specialone-<?= $id ?> .detail-title {
						    top: 10px;
						    width: 160px;
						    height: 40px;
						    line-height: 40px;
						    font-size: 24px;
						}
						#specialone-<?= $id ?> .detail-text {
						    top: 70px;
						    left: -webkit-calc(50% - 90px);
						    left: calc(50% - 90px);
						    width: 180px;
						    height: 65px;
						    line-height: 22px;
						    font-size: 18px;
						}
						#specialone-<?= $id ?> .detail-link {
						    bottom: 10px;
						    left: -webkit-calc(50% - 90px);
						    left: calc(50% - 90px);
						    width: 180px;
						    height: 40px;
						    line-height: 40px;
						    font-size: 18px;
						}
					}
					@media (max-width: 959px) and (min-width: 768px) {
						#specialone-<?= $id ?> {
							width: 768px;
					    	padding: 30px;
						}
						#specialone-<?= $id ?> .left {
							width: 350px;
						    height: 350px;
						}
						#specialone-<?= $id ?> .left-int {
							width: 350px;
						    height: 350px;
						    padding: 25px;
						    -moz-background-size: 300px;
						         background-size: 300px;
						}
						#specialone-<?= $id ?> .left-int img {
							width: 300px;
						    height: 300px;
						}
						#specialone-<?= $id ?> .right {
							width: 350px;
						    height: 350px;
						}
						#specialone-<?= $id ?> .small {
							width: 172px;
						    height: 172px;
						}
						#specialone-<?= $id ?> .detail {
							width: 172px;
						    height: 172px;
						}
						#specialone-<?= $id ?> .detail-title {
						    top: 10px;
						    width: 140px;
						    height: 40px;
						    line-height: 40px;
						    padding-left: 10px;
						    font-size: 22px;
						}
						#specialone-<?= $id ?> .detail-text {
							top: 55px;
						    left: -webkit-calc(50% - 75px);
						    left: calc(50% - 75px);
						    width: 150px;
						    height: 60px;
						    line-height: 20px;
						    font-size: 16px;
						}
						#specialone-<?= $id ?> .detail-link {
							bottom: 10px;
						    left: -webkit-calc(50% - 70px);
						    left: calc(50% - 70px);
						    width: 140px;
						    height: 40px;
						    line-height: 40px;
						    font-size: 18px;
						}
					}
					@media (max-width: 767px) and (min-width: 480px) {
						#specialone-<?= $id ?> {
							width: 480px;
					    	padding: 40px;
						}
						#specialone-<?= $id ?> .left {
							width: 400px;
						    height: 400px;
						}
						#specialone-<?= $id ?> .left-int {
							width: 400px;
						    height: 400px;
						    padding: 30px;
						    -moz-background-size: 340px;
						         background-size: 340px;
						}
						#specialone-<?= $id ?> .left-int img {
							width: 340px;
						    height: 340px;
						}
						#specialone-<?= $id ?> .right {
							width: 400px;
						    height: 400px;
						    display: block;
						    float: none;
						    margin-top: 5px;
						}
						#specialone-<?= $id ?> .small {
							width: 195px;
						    height: 195px;
						}
						#specialone-<?= $id ?> .detail {
							width: 195px;
						    height: 195px;
						}
						#specialone-<?= $id ?> .detail-title {
						    top: 10px;
						    height: 40px;
						    line-height: 40px;
						}
						#specialone-<?= $id ?> .detail-text {
							top: 60px;
						    left: -webkit-calc(50% - 85px);
						    left: calc(50% - 85px);
						    width: 170px;
						    height: 60px;
						    line-height: 20px;
						    font-size: 16px;
						}
						#specialone-<?= $id ?> .detail-link {
							bottom: 10px;
						    left: -webkit-calc(50% - 80px);
						    left: calc(50% - 80px);
						    width: 160px;
						    height: 40px;
						    line-height: 40px;
						    font-size: 18px;
						}
					}
					@media (max-width: 479px) {
						#specialone-<?= $id ?> {
							width: 300px;
					    	padding: 25px;
						}
						#specialone-<?= $id ?> .left {
							width: 250px;
						    height: 250px;
						}
						#specialone-<?= $id ?> .left-int {
							width: 250px;
						    height: 250px;
						    padding: 20px;
						    -moz-background-size: 210px;
						         background-size: 210px;
						}
						#specialone-<?= $id ?> .left-int img {
							width: 210px;
						    height: 210px;
						}
						#specialone-<?= $id ?> .right {
							width: 250px;
						    height: 250px;
						    display: block;
						    float: none;
						    margin-top: 5px;
						}
						#specialone-<?= $id ?> .small {
							width: 123px;
						    height: 123px;
						}
						#specialone-<?= $id ?> .detail {
							width: 123px;
						    height: 123px;
						}
						#specialone-<?= $id ?> .detail-title {
						    top: 10px;
						    width: 113px;
						    height: 30px;
						    line-height: 30px;
						    font-size: 22px;
						}
						#specialone-<?= $id ?> .detail-text {
							display: none;
						}
						#specialone-<?= $id ?> .detail-link {
							position: absolute;
						    bottom: 10px;
						    left: -webkit-calc(50% - 55px);
						    left: calc(50% - 55px);
						    width: 110px;
						    height: 30px;
						    line-height: 30px;
						    font-size: 18px;
						}
					}
					<?= $specialone_css; ?>
				</style>

				<script>
					jQuery(document).ready(function($){
						var oldSrc = $('.left-int img').attr('src');
						var newSrc;

						$('.right-int .small').hover(function() {
							newSrc = $(this).children('img').attr('src');
							$('.left-int').css('background-image', 'url(' + newSrc + ')')
							$('.left-int img').fadeOut(400, function() { 
								$(this).attr("src", newSrc);
								$(this).fadeIn(400);
							});
							$(this).children('.detail').fadeIn(400);

						}, function() {
							$('.left-int').css('background-image', 'url(' + oldSrc + ')')
							$('.left-int img').fadeOut(400, function() {      
								$(this).attr("src", oldSrc);
								$(this).fadeIn(400);
							});
							$(this).children('.detail').fadeOut(400);
						});
					});
			    </script>

            <?php endwhile;
        wp_reset_postdata(); ?>

    	<?php $specialone = ob_get_clean();

    	return $specialone;

    	}

    }

    /**
	 * Define the shortcode for the Offer post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_offer_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'offer',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {

        	$offer_id = $query->get_queried_object_id();

        	$meta = get_post_custom( $offer_id );

        	$offer_subtitle = ! isset( $meta['offer_subtitle'][0] ) ? '' : $meta['offer_subtitle'][0];
        	$offer_text = ! isset( $meta['offer_text'][0] ) ? '' : $meta['offer_text'][0];
        	$offer_link = ! isset( $meta['offer_link'][0] ) ? '' : $meta['offer_link'][0];

			$offer_pic_1 = ! isset( $meta['offer_pic_1'][0] ) ? '' : $meta['offer_pic_1'][0];
			$offer_pic_2 = ! isset( $meta['offer_pic_2'][0] ) ? '' : $meta['offer_pic_2'][0];			
			$offer_pic_1_src = $offer_pic_1 != '' ? wp_get_attachment_url( $offer_pic_1 ) : '';
			$offer_pic_2_src = $offer_pic_2 != '' ? wp_get_attachment_url( $offer_pic_2 ) : '';
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

	            <div class="boxoffertasingola jcarousel-offerte">
					<ul>
						<li>
							<div class="offerta" id="offerta<?php echo $offer_id; ?>">
								<p class="offertatitolo"><a href="<?php echo $offer_link; ?>"><?php the_title(); ?></a></p>
								<p class="offertasottotitolo"><a style="color: #C10000!important;" href="<?php echo $offer_link; ?>">Tua a <?php echo number_format( $offer_subtitle, 0, ',', '.' ); ?> €</a></p>
								<p class="offertaextra">Listino: <del><?php echo number_format( $offer_text, 0, ',', '.' ); ?> €</del></p>
								<p class="offertatesto">
									<?php the_content(); ?>
								</p>
								<a href="<?php echo $offer_link; ?>" class="offertabutton offertabutton1"><span>SCOPRI DI PIÙ</span></a>
								<div class="offertagalleriacontainer clearfix">
									<p class="offertagalleria pageimagezoom" data-src="<?php echo $offer_pic_1_src; ?>"><img src="<?php echo $offer_pic_1_src; ?>"></p>
									<p class="offertagalleria pageimagezoom" data-src="<?php echo $offer_pic_2_src; ?>"><img src="<?php echo $offer_pic_2_src; ?>"></p>
								</div>
							</div>
						</li>
					</ul>
					<div id="offerte-pagination" class="jcarousel-offerte-pagination"></div>
					<style>
						.boxofferte, .boxoffertasingola { width: 1200px; margin: 60px auto 0 auto; overflow: hidden; }
							.offerta { width: 1200px; position: relative; }
								.offerta p { }
								.offertatitolo { font-family: 'Anton', sans-serif;font-weight: 400; font-size: 40px; line-height: 1; margin-left: 100px; color: #91A2B5; padding: 20px 0 0 0!important; margin-bottom: 0!important; }
								.offertasottotitolo { font-family: 'Anton', sans-serif;font-weight: 400; font-size: 60px; line-height: 1; margin-left: 100px; color: #C10000; padding: 0!important; margin-bottom: 0!important; margin-top: 15px; }
								.offertaextra { font-family: 'Oswald', sans-serif;font-weight: 400; font-size: 18px; line-height: 1.4; margin-left: 100px; color: #001726; padding: 15px 0 0 0!important; margin-bottom: 0!important; }
								.offertatesto, .offertatesto p { font-family: 'Lato', sans-serif;font-weight: 400; font-size: 15px; line-height: 1.4; margin-left: 100px; color: #47545E; padding: 15px 0 0 0!important; margin-bottom: 0!important; }
								.offertabutton { font-weight: 400; display: block; font-family: 'Anton', sans-serif; font-size: 20px; line-height: 65px; color: #EEEEEE; margin-left: 30px; width: 240px; height: 65px; background: -webkit-gradient(linear, left top, left bottom, from(#ef201d),to(#a30c13)); background: -webkit-linear-gradient(top, #ef201d 0%,#a30c13 100%); background: -moz-linear-gradient(top, #ef201d 0%,#a30c13 100%); background: linear-gradient(to bottom, #ef201d 0%,#a30c13 100%); -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; -webkit-box-shadow: 0px 5px 2px -2px #000000; -moz-box-shadow: 0px 5px 2px -2px #000000; box-shadow: 0px 5px 2px -2px #000000; position: relative; }
								.offertabutton:after { content: ""; display: block; height: 100%; width: 100%; background: url(../img/buttonarrow.png) right top no-repeat; position: absolute; top: 0; left: 0; z-index: 1; }
								.offertabutton:hover { text-decoration: none; background: -webkit-gradient(linear, left top, left bottom, from(#b4cfeb),to(#8da2b7)); background: -webkit-linear-gradient(top, #b4cfeb 0%,#8da2b7 100%); background: -moz-linear-gradient(top, #b4cfeb 0%,#8da2b7 100%); background: linear-gradient(to bottom, #b4cfeb 0%,#8da2b7 100%); }
									.offertabutton1 { position: absolute; right: 100px; top: 45px; }
									.offertabutton2 { position: absolute; right: 100px; top: 120px; }
										.offertabutton span { margin-left: 14px; }
								.offertagalleriacontainer { margin-top: 50px; margin-bottom: 50px; width: 1200px; height:350px; }
									.offertagalleriacontainer p { margin-bottom: 0!important; height: 350px; line-height: 0; font-size: 0; float: left; border: 1px solid #EEEEEE; }
										.offertagalleriacontainer p:first-of-type { width: -webkit-calc(66.67% - 2px)!important; width: calc(66.67% - 2px)!important; }
										.offertagalleriacontainer p:last-of-type { width: -webkit-calc(33.33% - 2px)!important; width: calc(33.33% - 2px)!important; }
											.offertagalleriacontainer p img { display: block; height: 100%; width: 100%; -o-object-fit: cover; object-fit: cover; }
							#offerte-pagination { display: none; }
						@media (max-width: 1499px) and (min-width: 960px) {
							.boxofferte, .boxoffertasingola { width: 960px; margin: 0 auto; }
								.offerta { width: 960px; }
									.offertatitolo { margin-left: 20px;  }
									.offertasottotitolo { margin-left: 20px; }
									.offertaextra { margin-left: 20px; }
									.offertatesto { margin-left: 20px; }
									.offertagalleriacontainer { width: 100%; height: 280px; }
										.offertagalleria { max-height: 280px; }
						}
						@media (max-width: 959px) and (min-width: 768px) {
							.boxofferte, .boxoffertasingola { width: 768px; margin: 0 auto; }
								.offerta { width: 768px; }
									.offertatitolo { margin-left: 130px; margin-right: 130px; width: calc(100% - 260px)!important; text-align: center; padding-top: 0!important; }
									.offertasottotitolo { margin-left: 130px; margin-right: 130px; margin-top: 15px; width: calc(100% - 260px)!important; text-align: center; }
									.offertaextra { margin-left: 130px; margin-right: 130px; width: calc(100% - 260px)!important; text-align: center; }
									.offertatesto { margin-left: 130px; margin-right: 130px; width: calc(100% - 260px)!important; text-align: center; }
									.offertagalleriacontainer { width: 768px; height: 300px; }
										.offertagalleria { max-height: 223px; }
									.offertabutton1 { bottom: -20px; top: unset; left: 100px; }
									.offertabutton2 { bottom: -20px; top: unset; left: 380px; }
						}
						@media (max-width: 767px) and (min-width: 480px) {
							.boxofferte, .boxoffertasingola { width: 480px; position: relative; margin: 0 auto; }
								.offerta { width: 480px; }
									.offertatitolo { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; padding-top: 0!important; }
									.offertasottotitolo { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
									.offertaextra { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
									.offertatesto { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
									.offertagalleriacontainer { width: 480px; height: 265px; }
										.offertagalleriacontainer p:first-of-type { width: 100%!important; height: 212px!important; }
										.offertagalleriacontainer p:nth-of-type(2) { display: none; }
									.offertabutton { display: none; }
									#offerte-pagination { display: block; position: absolute; bottom: 50px; left: 200px; }
										#offerte-pagination a { border: none!important; border-radius: 5px!important; width: 20px!important; height: 20px!important; display: inline-block; vertical-align: middle; zoom: 1; margin: 2px; overflow: hidden; text-indent: -100%; background-color: #B4CFEB; }
											#offerte-pagination a.active { background-color: #00375A!important; }
						}
						@media (max-width: 479px) {
							.boxofferte, .boxoffertasingola { width: 300px; position: relative; margin: 0 auto; }
								.offerta { width: 300px; }
									.offertatitolo { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; padding-top: 0!important; }
									.offertasottotitolo { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
									.offertaextra { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
									.offertatesto { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
									.offertagalleriacontainer { width: 300px; height: 190px; }
										.offertagalleriacontainer p:first-of-type { width: 100%!important; height: 131px!important; }
										.offertagalleriacontainer p:nth-of-type(2) { display: none; }
									.offertabutton { display: none; }
									#offerte-pagination { display: block; position: absolute; bottom: 50px; left: 110px; }
										#offerte-pagination a { border: none!important; border-radius: 5px!important; width: 20px!important; height: 20px!important; display: inline-block; vertical-align: middle; zoom: 1; margin: 2px; overflow: hidden; text-indent: -100%; background-color: #B4CFEB; }
											#offerte-pagination a.active { background-color: #00375A!important; }
						}
					</style>

					<script>
						jQuery(document).ready(function($){
							$('.jcarousel-offerte').jcarousel({
								vertical:!1,
						        animation: {
							        duration: 1200,
							        easing:   'linear'
							    },
							    transitions: !0,
							    wrap: 'circular'
						    })
						    .jcarouselSwipe();
						    $('.offertabutton2').click(function() {
						    	$('.jcarousel-offerte').jcarousel('scroll', '+=1');
						    });
						    $('.jcarousel-offerte-pagination')
					            .on('jcarouselpagination:active', 'a', function() {
					                $(this).addClass('active');
					            })
					            .on('jcarouselpagination:inactive', 'a', function() {
					                $(this).removeClass('active');
					            })
					            .jcarouselPagination({

					        	});
					        if ($('#offerte-pagination:visible').length > 0) {			
								$(window).resize(function() {
									$('#offerte-pagination').css('left', 'calc(50% - ' + ( $('#offerte-pagination').width() / 2 ) + 'px)');
									if ($(window).width() < 768) {
										$('#offerte-pagination').css('left', 'calc(50% - ' + ( $('#offerte-pagination').width() / 2 ) + 'px)');
									}
								});
							}
						});
				    </script>
				</div>

            <?php endwhile;
        wp_reset_postdata(); ?>

    	<?php $offer = ob_get_clean();

    	return $offer;

    	}
    }

    /**
	 * Define the shortcode for the Offer Slider.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_offer_slider_shortcode( ) {

        ob_start();

        $options = get_option( 'alb_settings' );
		$args = array(
		  'post_type'  => 'offer',
		  'post_status' => 'publish',
		  'numberposts' => -1,
		  'meta_key' => 'offer_start_display',
		  'orderby'   => 'meta_value_num',
		  'order'      => 'ASC',
		  'meta_query' => array(
		    'relation' => 'AND',
		    array(
		      'key'     => 'offer_start_display',
		      'value'   => time(),
		      'compare' => '<=',
		    ),
		    array(
		      'key'     => 'offer_end_display',
		      'value'   => time(),
		      'compare' => '>=',
		    ),
		  ),
		);
		$offers = get_posts($args);
		if ( count( $offers ) > 0 ) {
			?>

			<div class="boxofferte jcarousel-offerte">
				<ul>

				<?php
		  		foreach ($offers as $index => $offer) {
		  			$offer_id = $offer->ID;
				    $meta = get_post_custom( $offer_id );

				    $offer_title = ! isset( $offer->post_title ) ? '' : $offer->post_title;				    
				    $offer_subtitle = ! isset( $meta['offer_subtitle'][0] ) ? '' : $meta['offer_subtitle'][0];
		        	$offer_text = ! isset( $meta['offer_text'][0] ) ? '' : $meta['offer_text'][0];
		        	$offer_content = str_replace( '<p>', '<p class="offertatesto">', wpautop( get_post_field( 'post_content', $offer->ID ), true ) );	
		        	$offer_link = ! isset( $meta['offer_link'][0] ) ? '' : $meta['offer_link'][0];

					$offer_pic_1 = ! isset( $meta['offer_pic_1'][0] ) ? '' : $meta['offer_pic_1'][0];
					$offer_pic_2 = ! isset( $meta['offer_pic_2'][0] ) ? '' : $meta['offer_pic_2'][0];
					$offer_pic_1_src = $offer_pic_1 != '' ? wp_get_attachment_url( $offer_pic_1 ) : '';
					$offer_pic_2_src = $offer_pic_2 != '' ? wp_get_attachment_url( $offer_pic_2 ) : '';

					?>

					<li>
						<div class="offerta" id="offerta<?php echo $offer_id; ?>">
							<p class="offertatitolo"><a href="<?php echo $offer_link; ?>"><?php echo $offer_title; ?></a></p>
							<p class="offertasottotitolo"><a style="color: #C10000!important;" href="<?php echo $offer_link; ?>">Tua a <?php echo number_format( $offer_subtitle, 0, ',', '.' ); ?> €</a></p>
							<p class="offertaextra">Listino: <del><?php echo number_format( $offer_text, 0, ',', '.' ); ?> €</del></p>
							<?php echo $offer_content; ?>
							<a href="<?php echo $offer_link; ?>" class="offertabutton offertabutton1"><span>SCOPRI DI PIÙ</span></a>
							<a class="offertabutton offertabutton2"><span>OFFERTA SUCCESSIVA</span></a>
							<div class="offertagalleriacontainer clearfix">
								<p class="offertagalleria pageimagezoom" data-src="<?php echo $offer_pic_1_src; ?>"><a href="<?php echo $offer_link; ?>"><img src="<?php echo $offer_pic_1_src; ?>"></a></p>
								<p class="offertagalleria pageimagezoom" data-src="<?php echo $offer_pic_2_src; ?>"><a href="<?php echo $offer_link; ?>"><img src="<?php echo $offer_pic_2_src; ?>"></a></p>
							</div>
						</div>
					</li>

				<?php }	?>

				</ul>
				<div id="offerte-pagination" class="jcarousel-offerte-pagination"></div>

			<?php }
		else { ?>

			<div class="boxoffertasingola jcarousel-offerte">
				<ul>
					<li>
						<div class="offerta" id="offerta0">
							<p class="offertatitolo"><a href="https://www.autoalpina-fcagroup.it/concessionario">SCOPRI SUBITO</a></p>
							<p class="offertasottotitolo"><a style="color: #C10000!important;" href="https://www.autoalpina-fcagroup.it/concessionario">Le offerte del mese</a></p>
							<p class="offertaextra">Ogni 30 giorni occasioni imperdibili!</p>
							<p class="offertatesto">
								Auto Alpina seleziona per voi ogni mese le migliori offerte<br />
								nel mondo dell'auto, sul nuovo e a Km 0,<br />
								disponibili nelle nostre concessionarie di Aosta e Ivrea<br />
							</p>
							<a href="https://www.autoalpina-fcagroup.it/concessionario" target="_blank" class="offertabutton offertabutton1"><span>SCOPRI DI PIÙ</span></a>
							<div class="offertagalleriacontainer clearfix">
								<p class="offertagalleria pageimagezoom" data-src="http://www.autoalpina.it/wp-content/uploads/2018/02/02-salone-aosta.jpg"><img src="http://www.autoalpina.it/wp-content/uploads/2018/02/02-salone-aosta.jpg"></p>
								<p class="offertagalleria pageimagezoom" data-src="http://www.autoalpina.it/wp-content/uploads/2018/02/05-salone-ivrea.jpg"><img src="http://www.autoalpina.it/wp-content/uploads/2018/02/05-salone-ivrea.jpg"></p>
							</div>
						</div>
					</li>
				</ul>
				<div id="offerte-pagination" class="jcarousel-offerte-pagination"></div>
			</div>

		<?php } ?>

			<style>
				.boxofferte, .boxoffertasingola { width: 1200px; margin: 60px auto 0 auto; overflow: hidden; }
					.offerta { width: 1200px; position: relative; }
						.offerta p { }
						.offertatitolo { font-family: 'Anton', sans-serif;font-weight: 400; font-size: 40px; line-height: 1; margin-left: 100px; color: #91A2B5; padding: 20px 0 0 0!important; margin-bottom: 0!important; }
						.offertasottotitolo { font-family: 'Anton', sans-serif;font-weight: 400; font-size: 60px; line-height: 1; margin-left: 100px; color: #C10000; padding: 0!important; margin-bottom: 0!important; margin-top: 15px; }
						.offertaextra { font-family: 'Oswald', sans-serif;font-weight: 400; font-size: 18px; line-height: 1.4; margin-left: 100px; color: #001726; padding: 15px 0 0 0!important; margin-bottom: 0!important; }
						.offertatesto, .offertatesto p { font-family: 'Lato', sans-serif;font-weight: 400; font-size: 15px; line-height: 1.4; margin-left: 100px; color: #47545E; padding: 15px 0 0 0!important; margin-bottom: 0!important; }
						.offertabutton { font-weight: 400; display: block; font-family: 'Anton', sans-serif; font-size: 20px; line-height: 65px; color: #EEEEEE; margin-left: 30px; width: 240px; height: 65px; background: -webkit-gradient(linear, left top, left bottom, from(#ef201d),to(#a30c13)); background: -webkit-linear-gradient(top, #ef201d 0%,#a30c13 100%); background: -moz-linear-gradie	nt(top, #ef201d 0%,#a30c13 100%); background: linear-gradient(to bottom, #ef201d 0%,#a30c13 100%); -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; -webkit-box-shadow: 0px 5px 2px -2px #000000; -moz-box-shadow: 0px 5px 2px -2px #000000; box-shadow: 0px 5px 2px -2px #000000; position: relative; cursor: pointer; }
						.offertabutton:after { content: ""; display: block; height: 100%; width: 100%; background: url(../img/buttonarrow.png) right top no-repeat; position: absolute; top: 0; left: 0; z-index: 1; }
						.offertabutton:hover { text-decoration: none; background: -webkit-gradient(linear, left top, left bottom, from(#b4cfeb),to(#8da2b7)); background: -webkit-linear-gradient(top, #b4cfeb 0%,#8da2b7 100%); background: -moz-linear-gradient(top, #b4cfeb 0%,#8da2b7 100%); background: linear-gradient(to bottom, #b4cfeb 0%,#8da2b7 100%); }
							.offertabutton1 { position: absolute; right: 100px; top: 45px; }
							.offertabutton2 { position: absolute; right: 100px; top: 120px; }
								.offertabutton span { margin-left: 14px; }
						.offertagalleriacontainer { margin-top: 50px; margin-bottom: 50px; width: 1200px; height:350px; }
							.offertagalleriacontainer p { margin-bottom: 0!important; height: 350px; line-height: 0; font-size: 0; float: left; border: 1px solid #EEEEEE; }
								.offertagalleriacontainer p:first-of-type { width: -webkit-calc(66.67% - 2px)!important; width: calc(66.67% - 2px)!important; }
								.offertagalleriacontainer p:last-of-type { width: -webkit-calc(33.33% - 2px)!important; width: calc(33.33% - 2px)!important; }
									.offertagalleriacontainer p img { display: block; height: 100%; width: 100%; -o-object-fit: cover; object-fit: cover; }
					#offerte-pagination { display: none; }
				@media (max-width: 1499px) and (min-width: 960px) {
					.boxofferte, .boxoffertasingola { width: 960px; margin: 0 auto; }
						.offerta { width: 960px; }
							.offertatitolo { margin-left: 20px;  }
							.offertasottotitolo { margin-left: 20px; }
							.offertaextra { margin-left: 20px; }
							.offertatesto { margin-left: 20px; }
							.offertagalleriacontainer { width: 100%; height: 280px; }
								.offertagalleria { max-height: 280px; }
				}
				@media (max-width: 959px) and (min-width: 768px) {
					.boxofferte, .boxoffertasingola { width: 768px; margin: 0 auto; }
						.offerta { width: 768px; }
							.offertatitolo { margin-left: 130px; margin-right: 130px; width: calc(100% - 260px)!important; text-align: center; padding-top: 0!important; }
							.offertasottotitolo { margin-left: 130px; margin-right: 130px; margin-top: 15px; width: calc(100% - 260px)!important; text-align: center; }
							.offertaextra { margin-left: 130px; margin-right: 130px; width: calc(100% - 260px)!important; text-align: center; }
							.offertatesto { margin-left: 130px; margin-right: 130px; width: calc(100% - 260px)!important; text-align: center; }
							.offertagalleriacontainer { width: 768px; height: 300px; }
								.offertagalleria { max-height: 223px; }
							.offertabutton1 { bottom: -20px; top: unset; left: 100px; }
							.offertabutton2 { bottom: -20px; top: unset; left: 380px; }
				}
				@media (max-width: 767px) and (min-width: 480px) {
					.boxofferte, .boxoffertasingola { width: 480px; position: relative; margin: 0 auto; }
						.offerta { width: 480px; }
							.offertatitolo { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; padding-top: 0!important; }
							.offertasottotitolo { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
							.offertaextra { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
							.offertatesto { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
							.offertagalleriacontainer { width: 480px; height: 265px; }
								.offertagalleriacontainer p:first-of-type { width: 100%!important; height: 212px!important; }
								.offertagalleriacontainer p:nth-of-type(2) { display: none; }
							.offertabutton { display: none; }
							#offerte-pagination { display: block; position: absolute; bottom: 50px; left: 200px; }
								#offerte-pagination a { border: none!important; border-radius: 5px!important; width: 20px!important; height: 20px!important; display: inline-block; vertical-align: middle; zoom: 1; margin: 2px; overflow: hidden; text-indent: -100%; background-color: #B4CFEB; }
									#offerte-pagination a.active { background-color: #00375A!important; }
				}
				@media (max-width: 479px) {
					.boxofferte, .boxoffertasingola { width: 300px; position: relative; margin: 0 auto; }
						.offerta { width: 300px; }
							.offertatitolo { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; padding-top: 0!important; }
							.offertasottotitolo { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
							.offertaextra { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
							.offertatesto { margin-left: 20px; margin-right: 20px; width: calc(100% - 40px)!important; text-align: center; }
							.offertagalleriacontainer { width: 300px; height: 190px; }
								.offertagalleriacontainer p:first-of-type { width: 100%!important; height: 131px!important; }
								.offertagalleriacontainer p:nth-of-type(2) { display: none; }
							.offertabutton { display: none; }
							#offerte-pagination { display: block; position: absolute; bottom: 50px; left: 110px; }
								#offerte-pagination a { border: none!important; border-radius: 5px!important; width: 20px!important; height: 20px!important; display: inline-block; vertical-align: middle; zoom: 1; margin: 2px; overflow: hidden; text-indent: -100%; background-color: #B4CFEB; }
									#offerte-pagination a.active { background-color: #00375A!important; }
				}
			</style>

			<script>
				jQuery(document).ready(function($){
					$('.jcarousel-offerte').jcarousel({
						vertical:!1,
				        animation: {
					        duration: 1200,
					        easing:   'linear'
					    },
					    transitions: !0,
					    wrap: 'circular'
				    })
				    .jcarouselSwipe();
				    $('.offertabutton2').click(function() {
				    	$('.jcarousel-offerte').jcarousel('scroll', '+=1');
				    });
				    $('.jcarousel-offerte-pagination')
			            .on('jcarouselpagination:active', 'a', function() {
			                $(this).addClass('active');
			            })
			            .on('jcarouselpagination:inactive', 'a', function() {
			                $(this).removeClass('active');
			            })
			            .jcarouselPagination({

			        	});
			        if ($('#offerte-pagination:visible').length > 0) {			
						$(window).resize(function() {
							$('#offerte-pagination').css('left', 'calc(50% - ' + ( $('#offerte-pagination').width() / 2 ) + 'px)');
							if ($(window).width() < 768) {
								$('#offerte-pagination').css('left', 'calc(50% - ' + ( $('#offerte-pagination').width() / 2 ) + 'px)');
							}
						});
					}
				});
		    </script>

            <?php wp_reset_postdata(); ?>

    	<?php $offer = ob_get_clean();

    	return $offer;

    }

    /**
	 * Define the shortcode for the Video post type.
	 *
	 * @since    0.0.1
	 */
    public function wordmedia_map_shortcode( $atts ) {

        ob_start();
        extract( shortcode_atts( array ( 'id' => '' ), $atts ) );
        $args = array(
            'post_type'      => 'map',
            'page_id'        => $id
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {       	

        	$meta = get_post_custom( $query->get_queried_object_id() );

        	$map_title = ! isset( $meta['map_title'][0] ) ? '' : htmlspecialchars( $meta['map_title'][0] );
			$map_pic = ! isset( $meta['map_pic'][0] ) ? '' : $meta['map_pic'][0];

			$map_width = ! isset( $meta['map_width'][0] ) ? '' : $meta['map_width'][0];
			$map_height = ! isset( $meta['map_height'][0] ) ? '' : $meta['map_height'][0];

			$map_center_lat = ! isset( $meta['map_center_lat'][0] ) ? '' : $meta['map_center_lat'][0];
			$map_center_lon = ! isset( $meta['map_center_lon'][0] ) ? '' : $meta['map_center_lon'][0];

			$map_zoom = ! isset( $meta['map_zoom'][0] ) ? 8 : $meta['map_zoom'][0];

			$map_css = ! isset( $meta['map_css'][0] ) ? '' : htmlspecialchars( $meta['map_css'][0] );

			$map_pic_src = $map_pic != '' ? wp_get_attachment_url( $map_pic ) : '';

			$map_points_title = ! isset( $meta['map_points_title'][0] ) ? array() :  maybe_unserialize( $meta['map_points_title'][0] );
			$map_points_text = ! isset( $meta['map_points_text'][0] ) ? array() :  maybe_unserialize( $meta['map_points_text'][0] );
			$map_points_lat = ! isset( $meta['map_points_lat'][0] ) ? array() :  maybe_unserialize( $meta['map_points_lat'][0] );
			$map_points_lon = ! isset( $meta['map_points_lon'][0] ) ? array() :  maybe_unserialize( $meta['map_points_lon'][0] );
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<h3 class="map-title"><?php echo $map_title; ?></h3>

				<div class="map" id="map-<?php echo get_the_ID(); ?>">

				</div>
				
				<p class="map-text"><?php echo $map_text; ?></p>

				<style>
					div#map-<?= get_the_ID(); ?> {
						width: <?= $map_width ?>px;
						height: <?= $map_height ?>px;
						background: url(<?= $map_pic_src ?>);
						background-size: cover;
					}

					<?= $video_css; ?>
				</style>

				<script>
					jQuery(document).ready(function($){
						var map = new GMaps({
					    	el: '#map-<?= get_the_ID(); ?>',
					      	lat: <?= $map_center_lat ?>,
					      	lng: <?= $map_center_lon ?>,
					      	zoom: <?= $map_zoom ?>,
					    });
					    <?php foreach ($map_points_title as $key => $value): ?>
					    	var p = map.createMarker({
								lat: <?= $map_points_lat[$key] ?>,
								lng: <?= $map_points_lon[$key] ?>,
								title: '<?= $map_points_title[$key] ?>',
								infoWindow: { content: '<strong><?= $map_points_title[$key] ?></strong><br /><?= $map_points_text[$key] ?>' }
							});
							map.addMarker(p);
					    <?php endforeach ?>
					    map.setCenter(<?= $map_center_lat ?>, <?= $map_center_lon ?>);				    
					});
			    </script>

            <?php endwhile;
        wp_reset_postdata(); ?>

    	<?php $map = ob_get_clean();

    	return $map;

    	}

    }

}
