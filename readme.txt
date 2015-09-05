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

http://twig.sensiolabs.org/

See more information:

https://github.com/miya0001/content-template-engine

= Reuqires =

* PHP 5.3 or later
* WordPress 4.3 or later

= Template examples

Template with custom field:

`{% if post.my_custom_field %}
    Hello {{ post.my_custom_field }}!
{% endif %}`

Template with [advanced custom fields](http://www.advancedcustomfields.com/):

`{% if acf.name %}
    Hello {{ acf.name }}!
{% endif %}`

Template with [acf-repeater](http://www.advancedcustomfields.com/add-ons/repeater-field/):

`<ul>
{% for fruit in acf.fruites %}
    <li>{{ acf.fruit.name }}: {{ acf.fruit.price }}</li>
{% endfor %}
</ul>`

= Filters =

There are some cutom filters for WordPress.

* esc_html
* esc_attr
* esc_textarea
* esc_js
* esc_url
* apply_filters

`
{{ post.post_title | esc_html }}
`

or

`{{ post.post_title | apply_filters( "my_custom_filter" ) }}`

If you want to output HTML, you have to use `raw`.

`
{{ post.post_title | raw }}
`

See also Twig documentation:

http://twig.sensiolabs.org/doc/filters/index.html

= Filter Hooks =

`content_template_engine_variables`:

`add_filter( 'content_template_engine_variables', function( $var ){
    $var['fruits'] = get_fruits_as_array();

    return $var;
} );`

Then you can use this variable in the template.

`{% for fruit in fruits %}
    {{ fruit }}<br>
{% endfor %}`

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 0.1.0 =
* Initial release.
