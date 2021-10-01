<?php

declare(strict_types=1);

namespace App\Providers;

use App\Controller\ApiPostController;
use League\Route\RouteGroup;
use Pollen\Kernel\Container\BootableServiceProvider;
use Pollen\Routing\RouterInterface;
use Pollen\Support\Proxy\AssetProxy;

class RoutingServiceProvider extends BootableServiceProvider
{
    use AssetProxy;

    public function boot(): void
    {
        /** @var RouterInterface $router */
        $router = $this->app->get(RouterInterface::class);

        $router->get('/', function () {
            $this->asset()->enqueueTitle('Welcome');

            return view('index', ['name' => 'John Doe']);
        });

        $router->get('/admin', function () {
            $this->asset()->enqueueTitle('Admin');

            return view('admin');
        });

        $router->group('/api', function(RouteGroup $router) {
            $router->get('posts', [ApiPostController::class, 'list']);
            $router->get('posts/{id}', [ApiPostController::class, 'show']);
        });
    }
}
