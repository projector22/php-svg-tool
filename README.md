# php-svg-tool

A simple PHP tool to perform basic functions on SVG files.

## At Present

This tool takes the input of an `<svg></svg>` markup, either from a `.svg` file or just typed in. It then allows you to set or change custom attributes on the `<svg></svg>` as desired.

Currently only the `<svg>` tag can be manipulated, as the inner markup is beyond the current need.

## Build

This tool was built on PHP 8.1 - backwards compatibility to PHP 8.0 should be fine, but this tool should be incompatable with PHP 7.x and below as it uses a number of features introduced in PHP 8.0.

## Example

```php
<?php

$svg = new SVG( file_get_contents( 'path/to/file.svg' ) );
$svg->set_id( 'myId' )
    ->set_size( 150, 150 )
    ->add_to_classlist( 'myClass' )
    ->set_stroke( 'red' )
    ->echo();

echo $svg->return();
```

The above code will render the SVG image twice.
