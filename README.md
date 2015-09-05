# Content Template Engine

[![Build Status](https://travis-ci.org/miya0001/content-template-engine.svg?branch=master)](https://travis-ci.org/miya0001/content-template-engine)

This plugin enables Twig template engine in the WordPress contents.

You can post article like following.

```
{% if post.my_custom_field %}
    Hello {{ post.my_custom_field }}!
{% endif %}
```

http://twig.sensiolabs.org/

## Requires

* PHP 5.3 or later
* WordPress 4.3 or later

## Template examples

### Custom fields

```
{% if post.my_custom_field %}
    Hello {{ post.my_custom_field }}!
{% endif %}
```

### With [Advanced Custom Fields](http://www.advancedcustomfields.com/)

```
{% if acf.name %}
    Hello {{ acf.name }}!
{% endif %}
```

#### With [The Repeater Field](http://www.advancedcustomfields.com/add-ons/repeater-field/)

```
<ul>
{% for fruit in acf.fruites %}
    <li>{{ acf.fruit.name }}: {{ acf.fruit.price }}</li>
{% endfor %}
</ul>
```

### Conditional tags

```
{% if is_home() %}
This is the Home.
{% endif %}
```

See also:

https://github.com/megumi-wp-composer/wp-twig/blob/master/lib/Megumi/WP/Twig/Extension.php

## Filters

There are some cutom filters for WordPress.

* esc_html
* esc_attr
* esc_textarea
* esc_js
* esc_url

```
{{ post.post_title | esc_html }}
```

### Add your custom filters

There is an `apply_filters` filter as Twig extension.

```
{{ post.post_title | apply_filters( "my_custom_filter" ) }}
```

So, you can add custom filter functions like following.

```
add_filter( 'my_custom_filter', function( $content ){
    return do_something( $content );
} );
```

If you want to output HTML, you have to use `raw`.

```
{{ post.post_title | raw }}
```

See also Twig documentation:

http://twig.sensiolabs.org/doc/filters/index.html

## Filter Hooks

### content_template_engine_variables

```
add_filter( 'content_template_engine_variables', function( $var ){
    $var['fruits'] = get_fruits_as_array();
    return $var;
} );
```

Then you can use this variables in the template.

```
<ul>
    {% for fruit in fruits %}
        <li>{{ fruit.name }}: {{ fruit.price }}</li>
    {% endfor %}
</ul>
```

http://twig.sensiolabs.org/doc/tags/for.html

### content_template_engine_content

`add_filter( 'content_template_engine_content', function( $content ){
    return do_shortcode( $content );
} );`

## Note

Some default functions are disabled.

* `{{ constant() }}`
* `{{ include() }}`
* `{{ source() }}`

## How to contribute

Clone this repository.

```
$ git@github.com:miya0001/content-template-engine.git
```

Change into plugin.

```
$ cd content-template-engine
```

Run `composer install`.

```
$ composer install
```
