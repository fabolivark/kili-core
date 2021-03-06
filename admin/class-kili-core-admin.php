<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/kiliframework/kili-core
 * @since      1.0.0
 *
 * @package    Kili_Core
 * @subpackage Kili_Core/admin
 */

require_once( 'vendor/class-tgm-plugin-activation.php' );

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kili_Core
 * @subpackage Kili_Core/admin
 * @author     Kili Team <hello@kiliframework.org>
 */
class Kili_Core_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->add_actions();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kili-core-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kili-core-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function add_actions() {
		add_action( 'tgmpa_register', array($this, 'kili_register_required_plugins') );
	}

	public function kili_register_required_plugins() {
		$plugins = array(
			array(
				'name'      => 'Timber Library',
				'slug'      => 'timber-library',
				'required'  => true,
			),
			array(
				'name'     => 'SVG Support',
				'slug'     => 'svg-support',
				'required' => false,
			),
			array(
				'name'     => 'TinyMCE Advanced',
				'slug'     => 'tinymce-advanced',
				'required' => false,
			),
		);
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
			array_push( $plugins, array(
				'name'                  => 'Advanced Custom Fields',
				'slug'                  => 'advanced-custom-fields',
				'source'                => 'https://github.com/AdvancedCustomFields/acf/archive/master.zip',
				'required'              => true,
				'version'               => '5.7.12',
				'force_activation'      => false,
				'force_deactivation'    => false,
				'external_url'          => 'https://github.com/AdvancedCustomFields/acf',
			) );
			array_push( $plugins, array(
				'name'                  => 'Advanced Custom Fields: Options Page',
				'slug'                  => 'acf-options-page',
				'source'                => 'https://connect.advancedcustomfields.com/index.php?a=download&p=options-page&k=OPN8-FA4J-Y2LW-81LS',
				'required'              => false,
				'version'               => '2.0.1',
				'force_activation'      => false,
				'force_deactivation'    => false,
				'external_url'          => 'https://www.advancedcustomfields.com/add-ons/options-page/',
			) );
		}
		$config = array(
			'id'                             => 'kili_tgmpa',
			'default_path'                   => '',
			'menu'                           => 'tgmpa-install-plugins',
			'parent_slug'                    => 'plugins.php',
			'capability'                     => 'edit_theme_options',
			'has_notices'                    => true,
			'dismissable'                    => true,
			'dismiss_msg'                    => '',
			'is_automatic'                   => true,
			'message'                        => '',
			'notice_can_install_required'    => _n_noop(
				// translators: 1: plugin name(s).
				'This plugin requires the following plugin: %1$s.',
				'This plugin requires the following plugins: %1$s.',
				'kili-core'
			),
			'notice_can_install_recommended' => _n_noop(
				// translators: 1: plugin name(s).
				'This plugin recommends the following plugin: %1$s.',
				'This plugin recommends the following plugins: %1$s.',
				'kili-core'
			),
		);
		tgmpa( $plugins, $config );
	}

}
