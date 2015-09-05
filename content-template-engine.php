<?php
/*
Plugin Name: Content Template Engine
Version: 0.1.0
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

	public function __construct()
	{
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	public function plugins_loaded()
	{
		add_filter( 'the_content', array( $this, 'the_content' ), 9 );
		add_filter( 'widget_text', array( $this, 'the_content' ) );

		if ( function_exists( 'get_fields' ) ) {
			add_filter( 'content_template_engine_variables', function( $variables ){
				$variables['acf'] = get_fields();
				return $variables;
			} );
		}
	}

	public function init()
	{
		$this->twig = new Twig_Environment(
			new Twig_Loader_String(),
			apply_filters( 'content_template_engine_twig_options', array() )
		);

		$this->twig->addExtension( apply_filters(
			'content_template_engine_twig_extensions',
			new Megumi\WP\Twig_Extension() )
		);
	}

	public function the_content( $content )
	{
		$variables = apply_filters(
			'content_template_engine_variables',
			array(
				'post' => $GLOBALS['post'],
			)
		);

		return $this->twig->render( apply_filters( 'content_template_engine_content', $content ), $variables );
	}
}

$content_template_engine = new Content_Template_Engine();
