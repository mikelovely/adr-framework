<?php

namespace Adr;

use Adr\Actions\PostsIndexAction;
use Adr\Actions\PostsStoreAction;
use Adr\Domain\Repositories\PostRepository;
use Adr\Domain\Services\PostIndexService;
use Adr\Domain\Services\PostStoreService;
use Adr\Responders\PostIndexResponder;
use Adr\Responders\PostStoreResponder;
use Noodlehaus\Config;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Container;
use Valitron\Validator;

/**
 * Class AppFactory
 *
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 * @package ApplicationService
 */
class AppFactory
{
    public function make()
    {
        $app = new App(
            new Container
        );

        $this->setConfig($app->getContainer());

        $this->externalLibraries($app->getContainer());

        $this->registerActions($app->getContainer());

        $this->registerDomains($app->getContainer());

        $this->registerResponders($app->getContainer());

        $this->registerDatabase($app->getContainer());

        $this->registerRoutes($app);

        return $app;
    }

    /**
     * Configuration processes here.
     *
     * @param ContainerInterface $container
     */
    private function setConfig(ContainerInterface $container)
    {
        $env = getenv('DOCKER_FRESH_ENVIRONMENT');

        $path = APP_PATH;

        $container['Config'] = new Config("{$path}/config.{$env}.json");

        $container['settings']['displayErrorDetails'] = $container->get('Config')->get('debugging.errors') ?? false;
    }

    private function externalLibraries(ContainerInterface $container)
    {
        $container[Validator::class] = function () {
            return new Validator();
        };
    }

    private function registerActions(ContainerInterface $container)
    {
        $container[PostsIndexAction::class] = function ($container) {
            return new PostsIndexAction($container);
        };

        $container[PostsStoreAction::class] = function ($container) {
            return new PostsStoreAction($container);
        };
    }

    private function registerDomains(ContainerInterface $container)
    {
        $container[PostRepository::class] = function ($container) {
            return new PostRepository(
                $container->get('Database')
            );
        };

        $container[PostIndexService::class] = function ($container) {
            return new PostIndexService(
                $container->get(PostRepository::class)
            );
        };

        $container[PostStoreService::class] = function ($container) {
            return new PostStoreService(
                $container->get(PostRepository::class),
                $container->get(Validator::class)
            );
        };
    }

    private function registerResponders(ContainerInterface $container)
    {
        $container[PostIndexResponder::class] = function () {
            return new PostIndexResponder();
        };

        $container[PostStoreResponder::class] = function () {
            return new PostStoreResponder();
        };
    }

    /**
     * Configure database
     *
     * @param ContainerInterface $container
     */
    private function registerDatabase(ContainerInterface $container)
    {
        $dsn = vsprintf('mysql:host=%s;port=%s;dbname=%s;', [
            $container->get('Config')->get('database.mysql.app.master.host'),
            $container->get('Config')->get('database.mysql.app.master.port'),
            $container->get('Config')->get('database.mysql.app.master.database'),
        ]);

        $container['Database'] = function () use ($container, $dsn) {

            $pdo = new \PDO(
                $dsn,
                $container->get('Config')->get('database.mysql.app.master.username'),
                $container->get('Config')->get('database.mysql.app.master.password'),
                [
                    \PDO::ATTR_PERSISTENT => true
                ]
            );

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);

            return $pdo;
        };
    }

    /**
     * @param App $app
     */
    private function registerRoutes(App $app)
    {
        $app->get('/posts', PostsIndexAction::class);

        $app->post('/posts', PostsStoreAction::class);
    }
}

