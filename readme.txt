=== Content Template Engine ===
Contributors: miyauchi
Tags: the_content, twig, post, page, template, template engine
Requires at least: 4.3
Tested up to: 4.3
Stable tag: 0.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables Twig template engine in the WordPress contents.

== Description ==

This plugin enables [Twig template engine](http://twig.sensiolabs.org/) in the WordPress contents.

You can post article like following.

`{% if post.my_custom_field %}
    Hello {{ post.my_custom_field }}!
{% endif %}`

See more information:

https://github.com/miya0001/content-template-engine

= Reuqires =

* PHP 5.3 or later
* WordPress 4.3 or later

= Template examples =

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

There are some custom filters for WordPress.

* esc_html
* esc_attr
* esc_textarea
* esc_js
* esc_url

`
{{ post.post_title | esc_html }}
`

If you want to output HTML, you have to use `raw`.

`
{{ post.post_title | raw }}
`

See also Twig documentation:

http://twig.sensiolabs.org/doc/filters/index.html

= Note =

Some default functions are disabled.

* `{{ constant() }}`

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

https://github.com/miya0001/content-template-engine/releases
