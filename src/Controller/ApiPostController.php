<?php

declare(strict_types=1);

namespace App\Controller;

use Pollen\Database\DatabaseManager as DB;
use Pollen\Http\JsonResponseInterface;
use Pollen\Routing\BaseController;

class ApiPostController extends BaseController
{
    public function list(): JsonResponseInterface
    {
        $posts = DB::table('posts')->get()->all();

        $response = $this->json($posts);

        // CORS
        /*$response->headers->add([
            'Access-Control-Allow-Origin'   => rtrim($this->httpRequest()->headers->get('referer'), '/'),
            'Access-Control-Expose-Headers' => 'Content-Range'
        ]);*/

        $response->headers->set('Content-Range', count($posts));

        return $response;
    }

    public function show($id): JsonResponseInterface
    {
        $post = DB::table('posts')->where('id', $id)->first();

        return $this->json($post);
    }
}