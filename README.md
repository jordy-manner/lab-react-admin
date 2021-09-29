# Lab React Admin

## Install the Pollen Solutions application project skeleton

```bash
composer create-project pollen-solutions/skeleton lab-react-admin
```

## Serve the application

```bash
php -S localhost:8000 -t public
```

## Create the API server

### Post list endpoint

1. Create a src/Controller directory.
2. Create an ApiPostController.php file.
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
Visit : http://localhost:8000/api/posts
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

3. Visit : http://localhost:8000/api/posts/1

```json
{
  "id": 1,
  "title": "My first title",
  "body": "My first body"
}
```

## NPM Initialisation

### Install dependencies

#### Development dependencies

```bash
npm i -D webpack webpack-cli webpack-dev-server @babel/core babel-loader @babel/preset-env @babel/preset-react
```

#### Peer dependencies

```bash
npm i react react-dom react-admin node-sass sass-loader css-loader style-loader
```

### Config webpack

1. Create webpack.config.js

```javascript
const path = require('path')

const config = {
  mode: 'development',
  entry: {
    'app': './resources/js/app.jsx',
    'admin': './resources/js/admin.jsx'
  },
  output: {
    filename: '[name].js',
    publicPath: '/dist/'
  },
  resolve: {
    extensions: ['.js', '.jsx']
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        use: ['babel-loader'],
        exclude: /node_modules/
      },
      {
        test: /\.scss$/,
        use: [
          "style-loader",
          "css-loader",
          "sass-loader"
        ],
        exclude: /node_modules/
      }
    ]
  },
  devServer: {
    static: {
      directory: path.join(__dirname, 'public'),
    },
    compress: true,
    port: 9000
  }
}

module.exports = config
```

2. Create the Babel configuration file .babelrc

```json
{
  "presets": [
      "@babel/preset-env",
      "@babel/preset-react"
  ]
}
```

3. Add script to package json
```diff
{
// ...  
  "scripts": {
+    "start": "webpack-dev-server --progress --hot",
  }
// ...
}
```

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TEST</title>
    <script src="./dist/app.js" defer></script>
</head>
<body>
    <div id="root"></div>
</body>
</html>
```

Webpack Dev Server serve assets in memory, to check assets generation visit :
http://localhost:9000/webpack-dev-server


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