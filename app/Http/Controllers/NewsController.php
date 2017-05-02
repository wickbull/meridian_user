<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SEOMeta;
use OpenGraph;
use Twitter;
use Packages\News;
use Packages\Status;
use Packages\Publication;
use Packages\NewsCategory;

class NewsController extends Controller
{
    public function getList()
    {
        return view('pages.news.list');
    }

    public function getView(News $news ){
        return view('pages.news.view', [
                'news' => $news,
            ]);
    }
}
