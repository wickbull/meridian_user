<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use SEOMeta;
use OpenGraph;
use Twitter;
use Packages\Page;
use Packages\News;
use Packages\Publication;
use Packages\Fragment;
use Packages\NewsCategory;
use Packages\PublicationsCategory;

class StaticPagesController extends Controller {

    /**
     *
     * @param  Page   $page
     * @return view
     */
    public function getView(Page $page)
    {
        SEOMeta::setTitle($page->title);
        SEOMeta::setDescription($page->getOgDescription());
        OpenGraph::setTitle($page->title);
        OpenGraph::setDescription($page->getOgDescription());

        return view('pages.static', [
            'page' => $page,
        ]);
    }

    public function getStudentPage(Fragment $fragment)
    {
        $slug = $fragment->slug;

        $newsCategory = NewsCategory::whereTranslation('slug', $slug)
            ->with([
                'news' => function ($query) {
                    $query->translatedIn()->active();
                },
            ])->first();

        $publicationsCategory = PublicationsCategory::whereTranslation('slug', $slug)
            ->with([
                'publications' => function ($query) {
                    $query->translatedIn()->active();
                },
            ])->first();

        SEOMeta::setTitle($fragment->title);
        SEOMeta::setDescription($fragment->getOgDescription());
        OpenGraph::setTitle($fragment->title);
        OpenGraph::setDescription($fragment->getOgDescription());

        return view('pages.student')->with([
            'fragment'             => $fragment,
            'newsCategory'         => $newsCategory,
            'publicationsCategory' => $publicationsCategory,
        ]);
    }
}
