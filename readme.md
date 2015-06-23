[![Latest Stable Version](https://poser.pugx.org/bitolaco/silex-eloquent/v/stable)](https://packagist.org/packages/bitolaco/silex-eloquent) 
[![Total Downloads](https://poser.pugx.org/bitolaco/silex-eloquent/downloads)](https://packagist.org/packages/bitolaco/silex-eloquent) 
[![Latest Unstable Version](https://poser.pugx.org/bitolaco/silex-eloquent/v/unstable)](https://packagist.org/packages/bitolaco/silex-eloquent) 
[![License](https://poser.pugx.org/bitolaco/silex-eloquent/license)](https://packagist.org/packages/bitolaco/silex-eloquent)

This package provides a Laravel Eloquent service provider for Silex applications.
It was pulled from [this gist](https://gist.github.com/ziadoz/7326872) created by
[Jamie York](https://github.com/ziadoz). It is his creation, not ours. We just 
maintain this version for use in our public and client projects.

The instructions below show basic usage, and assume that you're already familiar
with [Eloquent and creating models, etc](http://laravel.com/docs/4.2/eloquent).

## Silex 2 and the future

There's the basic code for updating this to Silex 2.x and Illuminate 5.1, but since 
Lumen has arrived on the scene, it seems that it would be a bit [redundant](https://github.com/BitolaCo/silex-eloquent/issues/1) to work on
maintaining the project in the future for Silex 2. If you need Eloquent and a 
microframework for new proejcts, Lumen would seem to be the way to go. 

That being said, if someone is interested in helping test the code already there in the
[silex-v2 branch](https://github.com/BitolaCo/silex-eloquent/tree/silex-v2), let us know!

Unless that happens, we plan to maintain this until the end of life for Silex 1.X only.

## Installation

This package is available to install via [Composer](https://getcomposer.org/). Just add
it to your `composer.json` file as a requirement:

```json
{
	"require": {
		"bitolaco/silex-eloquent": "*"
	}
}
```


## Examples

### Single Connection

```php
$app = new Silex\Application;
$app->register(
	new \BitolaCo\Silex\CapsuleServiceProvider(),
	array( 
		 'capsule.connection' => array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'database' => 'dbname',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => '',
			'logging' => true, // Toggle query logging on this connection.
		)
	)
);
```

### Multiple Connections
```php
<?php

$app = new Silex\Application;
$app->register(
	new \BitolaCo\Silex\CapsuleServiceProvider(),
	array(
		// DB Connection: Multiple.
		'capsule.connections' => array(
			'default' => array(
				'driver' => 'mysql',
				'host' => 'localhost',
				'database' => 'dname1',
				'username' => 'root',
				'password' => '',
				'charset' => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix' => '',
				'logging' => false, // Toggle query logging on this connection.
			),
			'other' => array(
				'driver' => 'mysql',
				'host' => 'localhost',
				'database' => 'dbname2',
				'username' => 'root',
				'password' => '',
				'charset' => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix' => '',
				'logging' => true, // Toggle query logging on this connection.
			)
		)
	)
);
```


### APC Caching Example

```php
$app = new Silex\Application;
$app->register(
	new \BitolaCo\Silex\CapsuleServiceProvider(),
	array( 
		 'capsule.connection' => array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'database' => 'dbname',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => '',
			'logging' => true, // Toggle query logging on this connection.
		),
		 'capsule.cache' => array(
			'driver' => 'apc',
			'prefix' => 'laravel',
		),
	)
);
```

### File Caching Example

```php
$app = new Silex\Application;
$app->register(
	new \BitolaCo\Silex\CapsuleServiceProvider(),
	array( 
		 'capsule.connection' => array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'database' => 'dbname',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => '',
			'logging' => true, // Toggle query logging on this connection.
		),
		 'capsule.cache' => array(
			 'driver' => 'file',
			'path' => '/path/to/cache',
			'connection' => null,
			'table' => 'cache',
			'prefix' => 'laravel'
		),
	)
);
```

### Booting and Usage

A connection to the database is only established once Silex is booted, 
which happens when you call $app->run(). If you need to establish the 
connection manually before then, you need to call $app['capsule'];

```php
<?php
require __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();
$app->register(new \BitolaCo\Silex\CapsuleServiceProvider(), array(
    'capsule.connection' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'test',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    )
));

$app['capsule'];

class Book extends Illuminate\Database\Eloquent\Model 
{
    protected $table = "books";
}

var_dump(Book::find(1));
```
