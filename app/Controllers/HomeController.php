<?php

namespace App\Controllers;

use App\Models\Article;
use Faker\Factory;
use Illuminate\Database\Capsule\Manager;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class HomeController extends Controller
{
    /**
     * Render the home page
     *
     * @param Request $request
     * @param Response $response
     * @param [type] $args
     * @return void
     */
    public function index(Request $request, Response $response, $args)
    {
//        $faker = Factory::create();
//
//        for ($x = 0; $x <= 3000; $x++) {
//            Article::create([
//                'title' => $faker->sentence(10),
//                'body' => $faker->sentence(100),
//            ]);
//        }

        ['q' => $q] = $request->getQueryParams() + ['q' => ''];

        $articles = $this->c->get('search')(Article::class)
            ->search($q)
            ->get();

        $raw = $articles->raw();

        $articles = $articles->get();

        return $this->c->get('view')->render($response, 'home/index.twig', compact('articles'));
    }
}
