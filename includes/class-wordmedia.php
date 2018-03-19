<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.elk-lab.com/
 * @since      0.0.1
 *
 * @package    Wordmedia
 * @subpackage Wordmedia/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.1
 * @package    Wordmedia
 * @subpackage Wordmedia/includes
 * @author     Gabriele Coquillard <gabriele@elk-lab.com>
 */
class Wordmedia {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      Wordmedia_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function __construct() {
		if ( defined( 'WORDMEDIA_VERSION' ) ) {
			$this->version = WORDMEDIA_VERSION;
		} else {
			$this->version = '0.0.2';
		}
		$this->plugin_name = 'wordmedia';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_common_hooks();
		$this->add_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wordmedia-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wordmedia-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wordmedia-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wordmedia-public.php';

		$this->loader = new Wordmedia_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wordmedia_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wordmedia_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wordmedia_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_video_admin_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_video_admin_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_video_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_video_meta_boxes', 10, 2 );
		
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_admin_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_admin_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide1_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide1_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide2_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide2_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide3_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide3_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide4_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide4_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide5_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide5_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide6_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide6_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide7_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide7_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide8_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide8_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide9_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide9_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_slider_slide10_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_slider_slide10_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_powergallery_admin_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_powergallery_admin_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_powergallery_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_powergallery_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_menubar_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_menubar_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_evidences_admin_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_evidences_admin_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_evidences_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_evidences_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_headline_admin_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_headline_admin_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_headline_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_headline_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_footer_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_footer_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_calltoaction_admin_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_calltoaction_admin_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_calltoaction_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_calltoaction_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_socialbuttons_admin_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_socialbuttons_admin_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_socialbuttons_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_socialbuttons_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_specialone_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_specialone_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_offer_description_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_offer_description_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_offer_time_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_offer_time_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_offer_pic_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_offer_pic_meta_boxes', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_map_admin_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_map_admin_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_map_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_map_meta_boxes', 10, 2 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wordmedia_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'template_include', $plugin_public, 'register_video_template' );
		$this->loader->add_filter( 'template_include', $plugin_public, 'register_powergallery_template' );
	}

	/**
	 * Register all of the hooks related both to the public-facing and to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_common_hooks() {

		$plugin_admin = new Wordmedia_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_public = new Wordmedia_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'load_wp_media_files' );

		$this->loader->add_action( 'init', $plugin_admin, 'register_video_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_slider_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_powergallery_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_menubar_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_evidences_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_headline_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_footer_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_calltoaction_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_socialbuttons_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_map_post_type', 0 );

		$this->loader->add_action( 'init', $plugin_admin, 'register_specialone_post_type', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'register_offer_post_type', 0 );
	}

	/**
	 * Add shortcodes
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function add_shortcodes() {

		$plugin_admin = new Wordmedia_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_public = new Wordmedia_Public( $this->get_plugin_name(), $this->get_version() );

		$plugin_admin->add_shortcodes();
		$plugin_public->add_shortcodes();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.0.1
	 * @return    Wordmedia_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Load WP Media files
	 *
	 * @since     0.0.1
	 */
	public function load_wp_media_files() {
	    wp_enqueue_media();
	}

}
