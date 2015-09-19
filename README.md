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
{% for fruit in acf.fruits %}
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

If you want to output HTML, you have to use `raw`.

```
{{ post.post_title | raw }}
```

See also Twig documentation:

http://twig.sensiolabs.org/doc/filters/index.html

## Note

### Some default functions are disabled by security reason.

* `{{ constant() }}`

### Disable visual editor

Visual editor sometimes breaks template, so we recommend you to disable visual editor.

There is a meta box for disable Visual editor in editing screen. It is allowed only users who can publish posts.

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
