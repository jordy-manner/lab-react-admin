# Lab React Admin

## Create the API server

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
        ],
        [
            'id'    => 2,
            'title' => 'My second title',
            'body'  => 'My second body',
        ],
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

## Launch and test the API server

```bash
Visit : http://127.0.0.1:8000/api/posts
```

```json
[
  {
    "id": 1,
    "title": "My first title",
    "body": "My first body"
  },
  {
    "id": 2,
    "title": "My second title",
    "body": "My second body"
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

## NPM Initialisation

```bash
npm init
```

```bash
npm install react react-dom react-admin ra-data-json-server prop-types parcel-bundler
```

Modify package.json

```json
{
  "scripts": {
    "dev": "parcel resources/index.html",
    "build": "parcel build resources/index.html",
  }
}
```

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TEST</title>
</head>
<body>
    <div id="root"></div>
    <script src="js/index.js" defer></script>
</body>
</html>
```

```scss
body {
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen',
  'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue',
  sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

code {
  font-family: source-code-pro, Menlo, Monaco, Consolas, 'Courier New',
  monospace;
}
```

## Prevent CORS




```bash
npm run dev
```