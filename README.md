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

## Filter Hooks

### content_template_engine_variables

```
add_filter( 'content_template_engine_variables', function( $var ){
    $var['acf'] = get_fields();
    $var['posts'] = get_posts( array( ... ) );

    return $var;
} );
```

Then you can use this variables in the template.

```
{{ acf.foo }}

<ul>
    {% for p in posts %}
        <li>{{ p.post_title }}</li>
    {% endfor %}
</ul>
```

http://twig.sensiolabs.org/doc/tags/for.html

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
