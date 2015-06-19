<?php

namespace BitolaCo\Silex;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Cache\CacheManager;


class CapsuleServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * Register the Capsule service.
     *
     * @param Application $app
     **/
    public function register(Container $app)
    {

        $app['capsule.connection_defaults'] = array(
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => null,
            'username' => 'root',
            'password' => null,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => null,
            'logging' => false,
        );

        $app['capsule.global'] = true;
        $app['capsule.eloquent'] = true;
        $app['capsule.container'] = $app->protect(function () {
            return new IlluminateContainer;
        });


        $app['capsule.dispatcher'] = $app->protect(function () use ($app) {
            return new Dispatcher();
        });

        if (class_exists('Illuminate\Cache\CacheManager')) {
            $app['capsule.cache_manager'] = $app->protect(function () use ($app) {
                return new CacheManager(new \Illuminate\Foundation\Application);
            });
        }

        $app['eloquent'] = $app->protect(function () use ($app) {

            $eloquent = new Capsule();
            $eloquent->setAsGlobal();
            $eloquent->bootEloquent();

            if (!isset($app['capsule.connections'])) {
                $app['capsule.connections'] = array(
                    'default' => (isset($app['capsule.connection']) ? $app['capsule.connection'] : array()),
                );
            }
            foreach ($app['capsule.connections'] as $connection => $options) {
                $options = array_replace($app['capsule.connection_defaults'], $options);
                $logging = $options['logging'];
                unset($options['logging']);
                $eloquent->addConnection($options, $connection);
                if ($logging) {
                    $eloquent->connection($connection)->enableQueryLog();
                } else {
                    $eloquent->connection($connection)->disableQueryLog();
                }
            }

            return $eloquent;

        });

    }

    /**
     * Boot the Capsule service.
     *
     * @param Application $app Silex application instance.
     *
     * @return void
     **/
    public function boot(Application $app)
    {
        if ($app['capsule.eloquent']) {
            $app->before(function () use ($app) {
                $app['eloquent']();
            }, Application::EARLY_EVENT);
        }
    }
}