<?php

class Content_Template_Engine_Test extends WP_UnitTestCase
{
	/**
	 * @test
	 */
	public function the_content()
	{
		$args = array(
			'post_title' => 'Hello',
			'post_author' => 1,
			'post_content' => 'Hello {{ post.your_name }}!',
			'post_status' => 'publish',
			'post_date' => '2014-01-01 00:00:00',
		);

		$this->setup_postdata( $args );

		update_post_meta( get_the_ID(), 'your_name', 'Pitch' );

		$this->expectOutputString( "<p>Hello Pitch!</p>\n" );
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
