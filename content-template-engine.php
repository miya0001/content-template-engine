<?php
/*
Plugin Name: Content Template Engine
Version: 0.5.0
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
		add_action( 'init', array( $this, 'init' ) );
	}

	public function plugins_loaded()
	{
		load_plugin_textdomain(
			"content-template-engine",
			false,
			dirname( plugin_basename( __FILE__ ) ).'/languages'
		);

		add_filter( 'the_content', array( $this, 'the_content' ), 9 );
		add_filter( 'widget_text', array( $this, 'widget_text' ) );
		add_filter( 'user_can_richedit', array( $this, 'user_can_richedit' ) );
		add_filter( 'content_template_engine_content', array( $this, 'content_template_engine_content' ) );

		if ( function_exists( 'get_fields' ) ) {
			add_filter( 'content_template_engine_variables', function( $variables ){
				$variables['acf'] = get_fields();
				return $variables;
			} );
		}

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	public function init()
	{
		$this->twig = new Twig_Environment(
			new Twig_Loader_String(),
			apply_filters( 'content_template_engine_twig_options', array() )
		);

		$twig_extensions = array(
			new Megumi\WP\Twig_Extension(),
			new Content_Template_Engine_Twig_Extension(),
		);
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

		$variables = apply_filters(
			'content_template_engine_variables',
			array(
				'post' => $GLOBALS['post'],
			)
		);

		return $this->twig->render( apply_filters( 'content_template_engine_content', $content ), $variables );
	}

	public function widget_text( $content )
	{
		$variables = apply_filters(
			'content_template_engine_variables',
			array(
				'post' => $GLOBALS['post'],
			)
		);

		return $this->twig->render( apply_filters( 'content_template_engine_content', $content ), $variables );
	}

	public function content_template_engine_content( $content )
	{
		return do_shortcode( $content );
	}

	public function user_can_richedit( $bool )
	{
		if ( "1" === get_post_meta( get_the_ID(), '_content_template_engine_disable_richedit', true ) ) {
			return false;
		}

		return $bool;
	}

	public function add_meta_boxes()
	{
		if ( current_user_can( 'publish_'.get_post_type().'s' ) ) {
			$screens = $this->get_allowed_post_types();

			foreach ( $screens as $screen ) {
				add_meta_box(
					'content_template_engine_disable_richedit',
					__( 'Settings for the Template Engine', 'content-template-engine' ),
					function(){
						wp_nonce_field( 'content-template-engine-disable-richedit', 'content-template-engine-nonce' );
						echo '<p><label>';
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
						echo '</label></p>';
						echo sprintf(
							'<p><strong>%s</strong><br>%s</p>',
							esc_html__( 'Note:', 'content-template-engine' ),
							esc_html__( 'If you want to use Twig template in this article, we recommend you to disable visual editor.', 'content-template-engine' )
						);
					},
					$screen,
					'side',
					'low'
				);
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

		if ( current_user_can( 'publish_'.get_post_type().'s' ) ) {
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
		}

		return $post_id;
	}

	private function get_allowed_post_types()
	{
		return apply_filters( 'content_template_engine_allowed_post_types', $this->allowed_post_types );
	}
}

$content_template_engine = new Content_Template_Engine();
