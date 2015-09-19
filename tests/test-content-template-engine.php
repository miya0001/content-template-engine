<?php

class Content_Template_Engine_Test extends WP_UnitTestCase
{
	/**
	 * @test
	 */
	public function the_content_with_custom_field()
	{
		$args = array(
			'post_title' => 'Hello',
			'post_author' => 1,
			'post_content' => 'Hello {{ post.your_name }}!',
			'post_status' => 'publish',
			'post_date' => '2014-01-01 00:00:00',
		);

		$this->setup_postdata( $args );

		update_post_meta( get_the_ID(), '_content_template_engine_enable_template', "1" );
		update_post_meta( get_the_ID(), 'your_name', 'Pitch' );

		$this->expectOutputString( "<p>Hello Pitch!</p>\n" );
		the_content();
	}

	/**
	 * @test
	 */
	public function the_content_disallow_post_type()
	{
		$args = array(
			'post_title' => 'Hello',
			'post_author' => 1,
			'post_content' => 'Hello {{ post.your_name }}!',
			'post_status' => 'publish',
			'post_date' => '2014-01-01 00:00:00',
		);

		$this->setup_postdata( $args );

		update_post_meta( get_the_ID(), '_content_template_engine_enable_template', "1" );
		update_post_meta( get_the_ID(), 'your_name', 'Pitch' );

		add_filter( 'content_template_engine_allowed_post_types', function(){
			return array( 'page' );
		} );

		$this->expectOutputString( "<p>Hello {{ post.your_name }}!</p>\n" );
		the_content();
	}

	/**
	 * @test
	 */
	public function the_content_with_no_custom_filter()
	{
		$args = array(
			'post_title' => 'Hello',
			'post_author' => 1,
			'post_content' => 'Hello {{ post.your_name | apply_filters( "my_custom_filter" ) }}!',
			'post_status' => 'publish',
			'post_date' => '2014-01-01 00:00:00',
		);

		$this->setup_postdata( $args );

		update_post_meta( get_the_ID(), '_content_template_engine_enable_template', "1" );
		update_post_meta( get_the_ID(), 'your_name', 'Pitch' );

		$this->expectOutputString( "<p>Hello Pitch!</p>\n" );
		the_content();
	}

	/**
	 * @test
	 */
	public function the_content_with_custom_filter()
	{
		$args = array(
			'post_title' => 'Hello',
			'post_author' => 1,
			'post_content' => 'Hello {{ post.your_name | apply_filters( "my_custom_filter" ) }}!',
			'post_status' => 'publish',
			'post_date' => '2014-01-01 00:00:00',
		);

		$this->setup_postdata( $args );

		update_post_meta( get_the_ID(), '_content_template_engine_enable_template', "1" );
		update_post_meta( get_the_ID(), 'your_name', 'Pitch' );

		add_filter( 'my_custom_filter', function( $content ){
			return $content . '-san';
		} );

		$this->expectOutputString( "<p>Hello Pitch-san!</p>\n" );
		the_content();
	}

	/**
	 * @test
	 */
	public function filter_hook()
	{
		add_filter( 'content_template_engine_variables', function( $var ){
			$var['posts'] = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish' ) );
			return $var;
		} );

		$args = array(
			'post_title' => 'Hello',
			'post_author' => 1,
			'post_content' => '{% for p in posts %}<li>{{ p.post_title }}</li>{% endfor %}',
			'post_status' => 'publish',
			'post_date' => '2014-01-01 00:00:00',
		);

		$this->setup_postdata( $args );

		update_post_meta( get_the_ID(), '_content_template_engine_enable_template', "1" );

		$this->expectOutputString( "<li>Hello</li>\n" );
		the_content();
	}

	public function setup_postdata( $args )
	{
		global $post;
		global $wp_query;

		$wp_query->is_singular = true;

		$post_id = $this->factory->post->create( $args );
		$post = get_post( $post_id );
		setup_postdata( $post );
	}
}
