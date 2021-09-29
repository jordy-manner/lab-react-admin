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
            'body'  => 'My first body',
        ],
        [
            'id'    => 2,
            'title' => 'My second title',
            'body'  => 'My second body',
        ],
    ];

    public function list(): JsonResponseInterface
    {
        $response = $this->json($this->posts);

        // CORS
        /*$response->headers->add([
            'Access-Control-Allow-Origin'   => rtrim($this->httpRequest()->headers->get('referer'), '/'),
            'Access-Control-Expose-Headers' => 'Content-Range'
        ]);*/

        $response->headers->set('Content-Range', count($this->posts));

        return $response;
    }

    public function show($id): JsonResponseInterface
    {
        return $this->json($this->posts[0]);
    }
}