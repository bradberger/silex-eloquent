This package provides a Laravel Eloquent service provider for Silex applications.
It was pulled from [this gist](https://gist.github.com/ziadoz/7326872) created by
[Jamie York](https://github.com/ziadoz). It is his creation, not ours. We just 
maintain this version for use in our public and client projects.

The instructions below show basic usage, and assume that you're already familiar
with [Eloquent and creating models, etc](http://laravel.com/docs/4.2/eloquent).

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