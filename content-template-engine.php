<?php
/*
Plugin Name: Content Template Engine
Version: 0.9.4
Description: Enables Twig template engine in the WordPress contents.
Author: Takayuki Miyauchi
Author URI: https://github.com/miya0001/
Plugin URI: https://github.com/miya0001/content-template-engine
Text Domain: content-template-engine
Domain Path: /languages
*/

require_once dirname( __FILE__ ) . '/vendor/autoload.php';

class Content_Template_Engine
{
	private $twig;
	private $allowed_post_types = array( 'post', 'page' );

	public function __construct()
	{
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded()
	{
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'the_content', array( $this, 'the_content' ), 9 );
		add_filter( 'widget_text', array( $this, 'widget_text' ) );

		// for Advanced custom fields and smart custom field
		add_filter( 'content_template_engine_variables', function( $variables ){
			if ( function_exists( 'get_fields' ) ) {
				$variables['post']->acf = get_fields();
			}
			if ( method_exists( 'SCF', 'gets' ) ) {
				$variables['post']->scf = SCF::gets();
			}
			return $variables;
		} );

		add_filter( 'content_template_engine_widget_variables', function( $variables ){
			if ( function_exists( 'get_fields' ) ) {
				$variables['post']->acf = get_fields();
			}
			if ( method_exists( 'SCF', 'gets' ) ) {
				$variables['post']->scf = SCF::gets();
			}
			return $variables;
		} );

		if ( is_admin() ) {
			load_plugin_textdomain(
				"content-template-engine",
				false,
				dirname( plugin_basename( __FILE__ ) ).'/languages'
			);

			add_action( 'wp_editor_settings', array( $this, 'wp_editor_settings' ), 10, 2 );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_post' ) );
		}
	}

	public function init()
	{
		/**
		 * Filters the twig options.
		 *
		 * @param array $twig_options See http://twig.sensiolabs.org/api/master/Twig_Environment.html#method___construct.
		 * @return array
		 */
		$twig_options = apply_filters( 'content_template_engine_twig_options', array() );
		$this->twig = new Twig_Environment( new Twig_Loader_String(), $twig_options );

		$twig_extensions = array(
			new Megumi\WP\Twig_Extension(),
			new Content_Template_Engine_Twig_Extension(),
		);

		/**
		 * Filters the twig extensions.
		 *
		 * @param array $twig_extensions An objects array of twig extensions.
		 * @return array
		 */
		$twig_extensions = apply_filters( 'content_template_engine_twig_extensions', $twig_extensions );

		foreach ( $twig_extensions as $extension ) {
			$this->twig->addExtension( $extension );
		}
	}

	public function the_content( $content )
	{
		if ( ! in_array( get_post_type(), $this->get_allowed_post_types() ) ) {
			return $content;
		}

		if ( "1" !== get_post_meta( get_the_ID(), '_content_template_engine_enable_template', true ) ) {
			return $content;
		}

		/**
		 * Filters the variables for template.
		 *
		 * @param array $variables An array of template variables.
		 * @return array
		 */
		$variables = apply_filters(
			'content_template_engine_variables',
			array(
				'post' => $GLOBALS['post'],
			)
		);

		/**
		 * Filters the content as template.
		 *
		 * @param string $content Content as template.
		 * @return string
		 */
		return $this->twig->render( apply_filters( 'content_template_engine_content', $content ), $variables );
	}

	public function widget_text( $content )
	{
		/**
		 * Filters the variables for template.
		 *
		 * @param array $variables An array of template variables.
		 * @return array
		 */
		$variables = apply_filters(
			'content_template_engine_widget_variables',
			array(
				'post' => $GLOBALS['post'],
			)
		);

		/**
		 * Filters the content as template.
		 *
		 * @param string $content Content as template.
		 * @return string
		 */
		return $this->twig->render( apply_filters( 'content_template_engine_widget_content', $content ), $variables );
	}

	public function wp_editor_settings( $settings, $editor_id )
	{
		$meta = get_post_meta( get_the_ID(), '_content_template_engine_disable_richedit', true );
		if ( "1" === $meta && "content" === $editor_id ) {
			add_filter( 'user_can_richedit', '__return_false' );
		} else {
			add_filter( 'user_can_richedit', '__return_true' );
		}

		return $settings;
	}

	public function add_meta_boxes()
	{
		if ( current_user_can( $this->get_capability() ) ) {
			$screens = $this->get_allowed_post_types();

			foreach ( $screens as $screen ) {
				// add setting metabox
				add_meta_box(
					'content_template_engine_disable_richedit',
					'<span class="dashicons dashicons-admin-tools"></span> ' . __( 'Settings for the Template Engine', 'content-template-engine' ),
					function(){
						wp_nonce_field( 'content-template-engine-disable-richedit', 'content-template-engine-nonce' );
						echo '<p>';
						echo '<label>';
						if ( "1" === get_post_meta( get_the_ID(), '_content_template_engine_enable_template', true ) ) {
							echo '<input type="checkbox" name="content-template-engine-enable-template" value="1" checked> ';
						} else {
							echo '<input type="checkbox" name="content-template-engine-enable-template" value="1"> ';
						}
						echo esc_html__( 'Enable the Twig template engine', 'content-template-engine' );
						echo '</label><br>';
						echo '<label>';
						if ( "1" === get_post_meta( get_the_ID(), '_content_template_engine_disable_richedit', true ) ) {
							echo '<input type="checkbox" name="content-template-engine-disable-richedit" value="1" checked> ';
						} else {
							echo '<input type="checkbox" name="content-template-engine-disable-richedit" value="1"> ';
						}
						echo esc_html__( 'Disable the visual editor', 'content-template-engine' );
						echo '</label><br>';
						echo '<label>';
						if ( "1" === get_post_meta( get_the_ID(), '_content_template_engine_disable_content_editor', true ) ) {
							echo '<input type="checkbox" name="content-template-engine-disable-content-editor" value="1" checked> ';
						} else {
							echo '<input type="checkbox" name="content-template-engine-disable-content-editor" value="1"> ';
						}
						printf( __( 'Display the content editor only to <code>%s</code>', 'content-template-engine' ), $this->get_capability() );
						echo '</label>';
						echo '</p>';
					},
					$screen,
					'advanced',
					'low'
				);
			}
		} else {
			$screens = $this->get_allowed_post_types();
			foreach ( $screens as $screen ) {
				// disable content editor
				if ( get_post_meta( get_the_ID(), '_content_template_engine_disable_content_editor', true ) ) {
					remove_post_type_support( $screen, 'editor' );
				}
			}
		}
	}

	public function save_post( $post_id )
	{
		if ( ! isset( $_POST['content-template-engine-nonce'] ) ) {
			return $post_id;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['content-template-engine-nonce'], 'content-template-engine-disable-richedit' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( current_user_can( $this->get_capability() ) ) {
			if ( empty( $_POST['content-template-engine-enable-template'] ) ) {
				update_post_meta( $post_id, '_content_template_engine_enable_template', "0" );
			} else {
				update_post_meta( $post_id, '_content_template_engine_enable_template', "1" );
			}
			if ( empty( $_POST['content-template-engine-disable-richedit'] ) ) {
				update_post_meta( $post_id, '_content_template_engine_disable_richedit', "0" );
			} else {
				update_post_meta( $post_id, '_content_template_engine_disable_richedit', "1" );
			}
			if ( empty( $_POST['content-template-engine-disable-content-editor'] ) ) {
				update_post_meta( $post_id, '_content_template_engine_disable_content_editor', "0" );
			} else {
				update_post_meta( $post_id, '_content_template_engine_disable_content_editor', "1" );
			}
		}

		return $post_id;
	}

	private function get_allowed_post_types()
	{
		/**
		 * Filters the post types that is enabled template system.
		 *
		 * @param array $post_types Post types like post or page.
		 * @return array
		 */
		return apply_filters( 'content_template_engine_allowed_post_types', $this->allowed_post_types );
	}

	private function get_capability()
	{
		/**
		 * Filters the role or capability who can enables template.
		 *
		 * @param string $role The role or capability.
		 * @return string
		 */
		return apply_filters( 'content_template_engine_capability', 'administrator' );
	}
}

$content_template_engine = new Content_Template_Engine();
