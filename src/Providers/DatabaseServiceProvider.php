<?php

declare(strict_types=1);

namespace App\Providers;

use App\App;
use Illuminate\Database\Schema\Blueprint;
use Pollen\Container\BootableServiceProvider;
use Pollen\Database\DatabaseManagerInterface;
use Pollen\Database\DatabaseManager as DB;
use Pollen\Faker\FakerInterface;
use Pollen\Support\DateTime;

class DatabaseServiceProvider extends BootableServiceProvider
{
    public function boot(): void
    {
        /** @var App $app */
        $app = $this->getContainer();

        /** @var DatabaseManagerInterface $db */
        $db = $app->get(DatabaseManagerInterface::class);

        // Database connection
        $db->addConnection([
            'driver'    => 'sqlite',
            'database'  => $app->getBasePath('/var/database.sqlite'),
            'prefix'    => 'r3c6t6dm_',
        ]);
        $db->setAsGlobal();

        // Database migration
        $schema = DB::schema();
        if (!$schema->hasTable('posts')) {
            $schema->create('posts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->longText('content')->nullable();
                $table->timestamps();
            });
        }

        // Database seeding
        /** @var FakerInterface $faker */
        $faker = $app->get(FakerInterface::class);
        if (!DB::table('posts')->count()) {
            $posts = [];
            for ($i = 1; $i <=20; $i++) {
                $posts[] = [
                    'title'      => $faker->sentence(),
                    'content'    => $faker->paragraphs(4, true),
                    'created_at' => DateTime::now(),
                ];
            }
            DB::table('posts')->insert($posts);
        }
    }
}
