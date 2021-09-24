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

    public function show($id): JsonResponseInterface
    {
        return $this->json($this->posts[0]);
    }
}