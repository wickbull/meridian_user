<?php

namespace App\Http\Controllers;

use Packages\NewsCategoryTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Packages\NewsCategory;
use Packages\Publication;
use LaravelLocalization;
use App\Http\Requests;
use Packages\Status;
use Packages\News;
use Packages\Annonce;

class HomeController extends Controller {

    public function getIndex()
    {
        $news = News::active()
            ->limit(10)
            ->get();

        $annonces = Annonce::top()
            ->active()
            ->limit(5)
            ->get();

        return view('pages.home')->with([
                'annonces'   => $annonces,
                'news' => $news,
            ]);

        // return view('pages.home')->with([
        //     // 'topArticles'   => $topArticles,
        //     // 'announcements' => $announcements,
        //     // 'latestNews'    => $latestNews,
        //     // 'publications'  => $publications,
        //     // 'mediaArticles' => $mediaArticles,
        // ]);
    }

}
