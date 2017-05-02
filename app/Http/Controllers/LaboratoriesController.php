<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use SEOMeta;
use OpenGraph;
use Twitter;
use Packages\Laboratory;
use Packages\Publication;
use Packages\News;

class LaboratoriesController extends Controller {

    /**
     *
     * @return View
     */
    public function getList()
    {
        $title = _('Лабораторії');

        $laboratories = Laboratory::active()
            ->ordered()
            ->paginate(12);

        SEOMeta::setTitle($title);
        OpenGraph::setTitle($title);

        return view('pages.laboratories.list')->with([
            'title' => $title,
            'laboratories' => $laboratories,
        ]);
    }

    /**
     *
     * @return view
     */
    public function getView(Laboratory $laboratory)
    {
        $this->loadRelations($laboratory);

        $lastNews = $lastPublications = null;

        if ($laboratory->news->isEmpty()) {
            $lastNews = News::active()->ordered()->limit(5)->get();
        }
        if ($laboratory->publications->isEmpty()) {
            $lastPublications = Publication::active()->ordered()->limit(5)->get();
        }

        SEOMeta::setTitle($laboratory->title);
        SEOMeta::setDescription($laboratory->getOgDescription());
        OpenGraph::setTitle($laboratory->title);
        OpenGraph::setDescription($laboratory->getOgDescription());

        return view('pages.laboratories.item', [
            'laboratory'       => $laboratory,
            'lastNews'         => $lastNews,
            'lastPublications' => $lastPublications,
        ]);
    }

    /**
     *
     * @param  Collection|Laboratory $laboratory
     * @return void
     */
    protected function loadRelations($laboratory)
    {
        $laboratory->load([
            'news' => function ($query) {
                $query->translatedIn()->active();
            },
            'publications' => function ($query) {
                $query->translatedIn()->active();
            },
        ]);
    }
}
