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

        $this->c->get('tnt')->selectIndex('articles.index');

        $results = $this->c->get('tnt')->search($q, 12);

        $articles = Article::whereIn('id', $results['ids'])
            ->orderBy(Manager::connection()->raw('FIELD(id, ' . implode(',', $results['ids']) . ')'))
            ->get();

        return $this->c->get('view')->render($response, 'home/index.twig', compact('articles'));
    }
}
