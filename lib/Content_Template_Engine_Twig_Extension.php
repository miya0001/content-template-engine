<?php

class Content_Template_Engine_Twig_Extension extends \Twig_Extension
{
	public function getFunctions()
	{
		$conditional_functions = array(
			'is_home' => array( 'allow_args' => false ),
			'is_front_page' => array( 'allow_args' => false ),
			'is_single' => array( 'allow_args' => false ),
			'is_sticky' => array( 'allow_args' => false ),
			'is_page' => array( 'allow_args' => false ),
			'is_category' => array( 'allow_args' => false ),
			'is_tag' => array( 'allow_args' => false ),
			'is_tax' => array( 'allow_args' => false ),
			'is_author' => array( 'allow_args' => false ),
			'is_date' => array( 'allow_args' => false ),
			'is_year' => array( 'allow_args' => false ),
			'is_month' => array( 'allow_args' => false ),
			'is_day' => array( 'allow_args' => false ),
			'is_time' => array( 'allow_args' => false ),
			'is_new_day' => array( 'allow_args' => false ),
			'is_archive' => array( 'allow_args' => false ),
			'is_search' => array( 'allow_args' => false ),
			'is_404' => array( 'allow_args' => false ),
			'is_paged' => array( 'allow_args' => false ),
			'is_attachment' => array( 'allow_args' => false ),
			'is_singular' => array( 'allow_args' => false ),
			'is_feed' => array( 'allow_args' => false ),
			'is_user_logged_in' => array( 'allow_args' => false ),
			'in_category' => array( 'allow_args' => true ),
		);

		$functions = array();
		foreach ( $conditional_functions as $function => $args ) {
			if ( empty( $args['allow_args'] ) ) {
				$functions[] = new \Twig_SimpleFunction( $function, function() use ( $function ) {
					return call_user_func( $function );
				} );
			} else {
				$functions[] = new \Twig_SimpleFunction( $function, function( $args = null ) use ( $function ) {
					return call_user_func( $function, $args );
				} );
			}
		}

		$disabeld_functions = array(
			'constant',
		);

		foreach ( $disabeld_functions as $function ) {
			$functions[] = new \Twig_SimpleFunction( $function, function() use ( $function ) {
				return $function . '() is disabled.';
			} );
		}

		$functions[] = new \Twig_SimpleFunction(
			'include_post',
			array( $this, 'include_post' ), 
			array( 'needs_environment' => true, 'needs_context' => true, 'is_safe' => array( 'all' ) ) 
		);

		return $functions;
	}

	public function include_post( \Twig_Environment $env, $context, $post_id, $variables = array() )
	{
		if ( ! intval( $post_id ) ) {
			return;
		}

		$post = get_post( $post_id );

		return $env->resolveTemplate( $post->post_content )->render( $context );
	}

	public function getName()
	{
		return 'wp-content-template-engine';
	}
}
