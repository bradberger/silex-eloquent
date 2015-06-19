<?php

require __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use BitolaCo\Silex\CapsuleServiceProvider;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $table = 'reports';

}

$app = new Silex\Application;
$app['debug'] = true;
$app->register(
    new CapsuleServiceProvider(),
    array(
        'capsule.connection' => array(
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'logging' => true, // Toggle query logging on this connection.
        )
    )
);

$app->get('/', function() use($app) {

    $result = [];
    $rows = Test::all();
    foreach($rows as $row) {
        $result[] = $row->toArray();
    }

    return $app->json($result);

});

$app->run();

