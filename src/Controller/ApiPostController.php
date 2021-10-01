<?php

declare(strict_types=1);

namespace App\Controller;

use Pollen\Http\JsonResponseInterface;
use Pollen\Routing\BaseController;
use Pollen\Support\Proxy\DbProxy;

class ApiPostController extends BaseController
{
    use DbProxy;

    public function list(): JsonResponseInterface
    {
        $r = $this->httpRequest();

        $total = $this->db('posts')->count();

        $query = $this->db('posts')
            ->forPage($r->input('paged', 1), $r->input('per_page', 10))
            ->orderBy($r->input('order_by', 'id'), $r->input('order_dir', 'ASC'));

        $posts = $query->get()->all();

        $response = $this->json($posts);

        // CORS
        /*$response->headers->add([
            'Access-Control-Allow-Origin'   => rtrim($this->httpRequest()->headers->get('referer'), '/'),
            'Access-Control-Expose-Headers' => 'Content-Range'
        ]);*/

        $response->headers->set('Content-Range', $total);

        return $response;
    }

    public function show($id): JsonResponseInterface
    {
        $post = $this->db('posts')->where('id', $id)->first();

        return $this->json($post);
    }
}