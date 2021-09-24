# Lab React Admin

## Create the API

### Post list endpoint

1. Create the  src/Controller directory
2. Create the ApiPostController.php file
3. Write the ApiPostController and create the post list method :

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Pollen\Http\JsonResponseInterface;
use Pollen\Routing\BaseController;

class ApiPostController extends BaseController
{
    public array $posts = [
        [
            'id'    => 1,
            'title' => 'My first title',
            'body'  => 'My first body'
        ]
    ];

    public function list(): JsonResponseInterface
    {
        return $this->json($this->posts);
    }
}
```

4. Open src/Providers/RoutingServiceProvider.php

```diff
<?php

declare(strict_types=1);

namespace App\Providers;

use App\Controller\ApiPostController;
+use League\Route\RouteGroup;
use Pollen\Container\BootableServiceProvider;
use Pollen\Routing\RouterInterface;
use Pollen\Support\Proxy\AssetProxy;

class RoutingServiceProvider extends BootableServiceProvider
{
    use AssetProxy;

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        /** @var RouterInterface $router */
        $router = $this->getContainer()->get(RouterInterface::class);

        $router->get(
            '/',
            function () {
                $this->asset()->enqueueTitle('Welcome');

                return view('index', ['name' => 'John Doe']);
            }
        );

+        $router->group('/api', function(RouteGroup $router) {
+            $router->get('posts', [ApiPostController::class, 'list']);
+        });
    }
}
```

5. Visit : http://127.0.0.1:8000/api/posts

```json
[
  {
    "id": 1,
    "title": "My first title",
    "body": "My first body"
  }
]
```

### Post show endpoint

1. Open the ApiPostController :

```php
<?php
// ...

class ApiPostController extends BaseController
{
    // ...
    
    public function show($id): JsonResponseInterface
    {
        return $this->json($this->posts[0]);
    }
}
```

2. Open the RoutingServiceProvider :

```php
<?php

// ...

class RoutingServiceProvider extends BootableServiceProvider
{
    // ...
    
    public function boot(): void
    {
        // ...

        $router->group('/api', function(RouteGroup $router) {
            // ...
            $router->get('posts/{id}', [ApiPostController::class, 'show']);
        });
    }
}
```

3. Visit : http://127.0.0.1:8000/api/posts/1

```json
{
  "id": 1,
  "title": "My first title",
  "body": "My first body"
}
```