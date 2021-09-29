<?php

declare(strict_types=1);

namespace App;

use App\Providers\AssetServiceProvider as AppAssetServiceProvider;
use App\Providers\DatabaseServiceProvider as AppDatabaseServiceProvider;
use App\Providers\DebugServiceProvider as AppDebugServiceProvider;
use App\Providers\RoutingServiceProvider as AppRoutingServiceProvider;
use App\Providers\ViewServiceProvider as AppViewServiceProvider;
use Pollen\Asset\AssetServiceProvider;
use Pollen\Database\DatabaseServiceProvider;
use Pollen\Debug\DebugServiceProvider;
use Pollen\Faker\FakerServiceProvider;
use Pollen\Kernel\Application;
use Pollen\Routing\RoutingServiceProvider;
use Pollen\View\ViewServiceProvider;
use Pollen\ViewExtends\ViewExtendsServiceProvider;

class App extends Application
{
    public function getServiceProviders(): array
    {
        return [
            // Components
            AssetServiceProvider::class,
            DatabaseServiceProvider::class,
            DebugServiceProvider::class,
            FakerServiceProvider::class,
            RoutingServiceProvider::class,
            ViewServiceProvider::class,
            ViewExtendsServiceProvider::class,
            // Application
            AppAssetServiceProvider::class,
            AppDatabaseServiceProvider::class,
            AppDebugServiceProvider::class,
            AppRoutingServiceProvider::class,
            AppViewServiceProvider::class
        ];
    }
}