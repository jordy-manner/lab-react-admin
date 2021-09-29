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

1. Create a **src/Controller** directory.
2. Create a **src/Controller/ApiPostController.php** file.
3. Write the **ApiPostController** class and create the post list method :

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

4. Open **src/Providers/RoutingServiceProvider.php** file.

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

1. Modify the **ApiPostController** controller.

In **src/Controller/ApiPostController.php** file :

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

2. Open the **RoutingServiceProvider** provider.

In **src/Providers/RoutingServiceProvider.php** file :

```diff
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
+            $router->get('posts/{id}', [ApiPostController::class, 'show']);
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

1. Create a **webpack.config.js** file.

```js
const path = require('path')

const config = {
  mode: 'development',
  entry: {
    'app': './resources/assets/js/app.jsx'
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

2. Create a Babel configuration **.babelrc** file. 

```json
{
  "presets": [
      "@babel/preset-env",
      "@babel/preset-react"
  ]
}
```

3. Add scripts in **package.json** file.

```diff
{
// ...  
  "scripts": {
+    "serve": "webpack serve",
+    "hot": "npm run serve --hot",
  }
// ...
}
```

## Create the test layouts

### The Front-end layout

1. Modify the **resources/views/index.plates.php** file.

```diff
<?php
/**
 * @var Pollen\ViewExtends\PlatesTemplateInterface $this
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $this->asset_head(); ?>
+    <script src="http://localhost:9000/dist/app.js" defer></script>
</head>
<body>
-<h1>Welcome <?php echo $this->get('name'); ?>, from Plates Engine</h1>
+<div id="app" data-username="<?php echo $this->get('name'); ?>"></div>
<?php echo $this->asset_footer(); ?>
</body>
</html>
```

2. Create the Front-end entry JS file

Create a **resources/assets/js/app.jsx** entry file.

```jsx
import React from 'react'
import {render} from 'react-dom'
import App from './app/components/App'

const $app = document.getElementById('app')
const username = $app.dataset.username || 'World'

render(<App username={username}/>, $app)
```

3. Create the Front-end react component

Create a **resources/assets/js/app/components/App.jsx** component file.

```jsx
import React from 'react'

export default function ({username}) {
  return <h1>Hello {username} !</h1>
}
```

4. Start the webpack dev server

```bash
npm run serve
```

Webpack Dev Server serve assets in memory, to check assets generated visit :
http://localhost:9000/webpack-dev-server

5. Visit the application front-end : http://localhost:8000

### The Back-end layout

1. Create a **resources/views/admin.plates.php** file.

```php
<?php
/**
 * @var Pollen\ViewExtends\PlatesTemplateInterface $this
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $this->asset_head(); ?>
    <script src="http://localhost:9000/dist/app.js" defer></script>
</head>
<body>
<div id="app"></div>
<?php echo $this->asset_footer(); ?>
</body>
</html>
```

2. Create an admin route

Change the RoutingServiceProvider

```diff
<?php
// ...
class RoutingServiceProvider extends BootableServiceProvider
{
    // ...
    public function boot(): void
    {
        // ...
+        $router->get('/admin', function () {
+            $this->asset()->enqueueTitle('Admin');
+            return view('admin');
+        });
        // ...
    }
}
```

3. Create a Back-end entry JS file

Create **resources/assets/js/admin.jsx** entry file.

```jsx
import React from 'react'
import {render} from 'react-dom'
import App from './admin/components/App'
import '../scss/admin.scss'

render(<App/>, document.getElementById('app'))
```

4. Create a Back-end react component

Create a **resources/assets/js/admin/components/App.jsx** component file.

```jsx
import * as React from 'react'
import { Admin, Resource, ListGuesser } from 'react-admin'
import DataProvider from '../DataProvider'

const App = () => <Admin dataProvider={DataProvider} >
  <Resource name="posts" list={ListGuesser} />
</Admin>

export default App
```

5. Create a DataProvider

A DataProvider allow react-admin to interact with your API.

[more-informations](https://marmelab.com/react-admin/Tutorial.html#connecting-to-a-real-api)

Create a **resources/assets/js/admin/DataProvider.js** provider file.

```js
import { fetchUtils } from 'react-admin'
import { stringify } from 'query-string'

const apiUrl = 'http://localhost:8000/api'
const httpClient = fetchUtils.fetchJson

export default {
  getList: (resource, params) => {
    const { page, perPage } = params.pagination;
    const { field, order } = params.sort;
    const query = {
      sort: JSON.stringify([field, order]),
      range: JSON.stringify([(page - 1) * perPage, page * perPage - 1]),
      filter: JSON.stringify(params.filter),
    };
    const url = `${apiUrl}/${resource}?${stringify(query)}`;

    return httpClient(url).then(({ headers, json }) => {
      return {
        data: json,
        total: parseInt(headers.get('content-range').split('/').pop(), 10),
      }
    })
  },

  getOne: (resource, params) =>
      httpClient(`${apiUrl}/${resource}/${params.id}`).then(({ json }) => ({
        data: json,
      })),

  getMany: (resource, params) => {
    const query = {
      filter: JSON.stringify({ id: params.ids }),
    };
    const url = `${apiUrl}/${resource}?${stringify(query)}`;
    return httpClient(url).then(({ json }) => ({ data: json }));
  },

  getManyReference: (resource, params) => {
    const { page, perPage } = params.pagination;
    const { field, order } = params.sort;
    const query = {
      sort: JSON.stringify([field, order]),
      range: JSON.stringify([(page - 1) * perPage, page * perPage - 1]),
      filter: JSON.stringify({
        ...params.filter,
        [params.target]: params.id,
      }),
    };
    const url = `${apiUrl}/${resource}?${stringify(query)}`;

    return httpClient(url).then(({ headers, json }) => ({
      data: json,
      total: parseInt(headers.get('content-range').split('/').pop(), 10),
    }));
  },

  update: (resource, params) =>
      httpClient(`${apiUrl}/${resource}/${params.id}`, {
        method: 'PUT',
        body: JSON.stringify(params.data),
      }).then(({ json }) => ({ data: json })),

  updateMany: (resource, params) => {
    const query = {
      filter: JSON.stringify({ id: params.ids}),
    };
    return httpClient(`${apiUrl}/${resource}?${stringify(query)}`, {
      method: 'PUT',
      body: JSON.stringify(params.data),
    }).then(({ json }) => ({ data: json }));
  },

  create: (resource, params) =>
      httpClient(`${apiUrl}/${resource}`, {
        method: 'POST',
        body: JSON.stringify(params.data),
      }).then(({ json }) => ({
        data: { ...params.data, id: json.id },
      })),

  delete: (resource, params) =>
      httpClient(`${apiUrl}/${resource}/${params.id}`, {
        method: 'DELETE',
      }).then(({ json }) => ({ data: json })),

  deleteMany: (resource, params) => {
    const query = {
      filter: JSON.stringify({ id: params.ids}),
    };
    return httpClient(`${apiUrl}/${resource}?${stringify(query)}`, {
      method: 'DELETE',
    }).then(({ json }) => ({ data: json }));
  }
};
```

6. Create a Back-end CSS stylesheet

Create a resources/assets/scss/admin.scss file.

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

## Prevent CORS errors

https://developer.mozilla.org/docs/Web/HTTP/CORS/Errors