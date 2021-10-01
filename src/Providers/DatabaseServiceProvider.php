<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Pollen\Kernel\Container\BootableServiceProvider;
use Pollen\Support\DateTime;
use Pollen\Support\Env;
use Pollen\Support\Proxy\DbProxy;
use Pollen\Support\Proxy\FakerProxy;

class DatabaseServiceProvider extends BootableServiceProvider
{
    use DbProxy;
    use FakerProxy;

    public function boot(): void
    {
        // Database connection
        $this->db()->addConnection([
            'url'    => Env::get('DATABASE_URL')
        ]);
        $this->db()->setAsGlobal();

        // Database migration
        if (!$this->schema()->hasTable('posts')) {
            $this->schema()->create('posts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->longText('content')->nullable();
                $table->timestamps();
            });
        }

        // Database seeding
        if (!$this->db('posts')->count()) {
            $posts = [];
            for ($i = 1; $i <= 20; $i++) {
                $posts[] = [
                    'title'      => $this->faker()->sentence(),
                    'content'    => $this->faker()->paragraphs(4, true),
                    'created_at' => DateTime::now(),
                ];
            }
            $this->db('posts')->insert($posts);
        }
    }
}
