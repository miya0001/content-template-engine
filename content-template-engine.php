<?php
/*
Plugin Name: Content Template Engine
Version: 0.1.0
Description: Enables Twig template engine in the contents.
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
		$this->twig = new Twig_Environment( new Twig_Loader_String() );
		$this->twig->addExtension( new Megumi\WP\Twig_Extension() );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded()
	{
		add_filter( 'the_content', array( $this, 'the_content' ), 11, 10 );
	}

	public function the_content( $content )
	{
		$variables = apply_filters(
			'content_template_engine_variables',
			array(
				'post' => $GLOBALS['post'],
			)
		);

		return $this->twig->render( $content, $variables );
	}
}

$content_template_engine = new Content_Template_Engine();
