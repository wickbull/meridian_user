<?php

namespace App\Http\Controllers;

use Logger;
use Packages\News;
use Packages\Publication;
use App\Handlers\InstantArticles\ItemHandler;

class InstantArticlesController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Instant Articles Controller
    |--------------------------------------------------------------------------
    |
    */

    /**
     * instance of ItemHandler
     * @var App\Handlers\InstantArticles\ItemHandler
     */
    public $itemHandler;

    /**
     * Rss feed for facebook
     *
     * @return Response
     */
    public function getRss()
    {
        Logger::configure([]);

        $items = $this->getRssItems();

        foreach ($items as &$item) {
            $item->body = $this->fetchHanlder($item)->handle();
        }

        return view('instant-articles.rss')
            ->withItems($items)
            ->render();
    }

    /**
     *
     * @return instance of ItemHanlerP
     */
    public function fetchHanlder($item)
    {
        if (! $this->itemHandler) {
            return $this->itemHandler = new ItemHandler($item);
        }

        $this->itemHandler->setModel($item);

        return $this->itemHandler;
    }

    /**
     *
     * @return Collection
     */
    public function getRssItems()
    {
        $news = News::ordered()
            ->available()
            ->with(['news' => function ($query) {
                $query->ordered()->available();
            }, 'publications' => function ($query) {
                $query->ordered()->available();
            }])->take(50)
            ->get();

        $publications = Publication::ordered()
            ->available()
            ->with(['news' => function ($query) {
                $query->ordered()->available();
            }, 'publications' => function ($query) {
                $query->ordered()->available();
            }])->take(50)
            ->get();


        $items = $news
            ->merge($publications)
            ->sortByDesc('publish_at')
            ->take(50);

        return $items;
    }

}
