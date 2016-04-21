=== Content Template Engine ===
Contributors: miyauchi
Tags: the_content, twig, post, page, template, template engine
Requires at least: 4.3
Tested up to: 4.5
Stable tag: 0.9.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables Twig template engine in the WordPress contents.

== Description ==

This plugin enables [Twig template engine](http://twig.sensiolabs.org/) in the WordPress contents.

You can write Twig template in your posts/pages.

`{% if post.my_custom_field %}
    Hello {{ post.my_custom_field }}!
{% endif %}`

See more information:

* [Documentation](https://github.com/miya0001/content-template-engine/wiki)
* [GitHub](https://github.com/miya0001/content-template-engine)

= Features =

* It enables [Twig](http://twig.sensiolabs.org/) template engine in your WordPress contents.
* You can use post meta data in your template.
* [Advanced custom fields](http://www.advancedcustomfields.com/) and [Repeater Field](http://www.advancedcustomfields.com/add-ons/repeater-field/) ready.
* Secure!
  * You can restrict users to write template.
  * Automatic escaping is enabled.
* There are custom filters for WordPress. [See also](https://github.com/miya0001/content-template-engine/wiki/Filters).
* There are custom functions for WordPress. [See also](https://github.com/miya0001/content-template-engine/wiki/Functions).

= Reuqires =

* PHP 5.3 or later
* WordPress 4.3 or later

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Settings meta box in edit posts screen.
2. Edit posts screen.
3. The Content.

== Changelog ==

https://github.com/miya0001/content-template-engine/releases
