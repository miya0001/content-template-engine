=== Content Template Engine ===
Contributors: miyauchi
Tags: the_content, twig, post, page
Requires at least: 4.3
Tested up to: 4.3
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables Twig template engine in the WordPress contents.

== Description ==

This plugin enables Twig template engine in the WordPress contents.

You can post article like following.

`{% if post.my_custom_field %}

Hello {{ post.my_custom_field }}!

{% endif %}`

= Filter Hooks =

`content_template_engine_variables`

`add_filter( 'content_template_engine_variables', function( $var ){
    $var['acf'] = get_fields();

    return $var;
} );`

Then you can use this value in the template.

`{{ acf.foo }}`

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Insert a value from custom field.

== Changelog ==

= 0.1.0 =
* Initial release.
