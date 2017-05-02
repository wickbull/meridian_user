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
use Packages\PublicationsCategory;

class PublicationsController extends Controller
{
    /**
     *
     * @return View
     */
    public function getList()
    {
        $title = _('Публікації');

        $publications = Publication::active()
            ->ordered()
            ->paginate(12);

        $this->simpleLoadRelations($publications);

        SEOMeta::setTitle($title);
        OpenGraph::setTitle($title);

        return view('pages.articles.list')->with([
            'title' => $title,
            'items' => $publications,
        ]);
    }

    /**
     *
     * @param  PublicationsCategory $category
     * @return View
     */
    public function getCategory(PublicationsCategory $category)
    {
        $publications = $category->publications()->active()
            ->ordered()
            ->paginate(12);

        $this->simpleLoadRelations($publications);

        SEOMeta::setTitle($category->title);
        OpenGraph::setTitle($category->title);

        return view('pages.articles.list')->with([
            'title' => $category->title,
            'items' => $publications,
        ]);
    }

    /**
     *
     * @param  Status $status
     * @return View
     */
    public function getStatus(Status $status)
    {
        $publications = $status->publications()->active()
            ->ordered()
            ->paginate(12);

        $this->simpleLoadRelations($publications);

        SEOMeta::setTitle($status->title);
        OpenGraph::setTitle($status->title);

        return view('pages.articles.list')->with([
            'title' => $status->getNameAttribute(),
            'items' => $publications,
        ]);
    }

    /**
     *
     * @param  Publication $publication
     * @return View
     */
    public function getView(Publication $publication)
    {
        $this->loadRelations($publication);

        $tags = $publication->tags();
        $lastNews = $lastPublications = null;

        if ($publication->news->isEmpty()) {
            $lastNews = News::active()->ordered()->limit(5)->get();
        }
        if ($publication->publications->isEmpty()) {
            $publicationsForExclude = collect([$publication]);
            $lastPublications = Publication::active()
                ->exclude($publicationsForExclude)
                ->ordered()
                ->limit(5)
                ->get();
        }

        SEOMeta::setTitle($publication->title);
        SEOMeta::setDescription($publication->getOgDescription());
        OpenGraph::addImage($publication->getImage('1200x630', 'OG'));
        OpenGraph::setTitle($publication->title)
            ->setDescription($publication->getOgDescription())
            ->setType('article')
            ->setArticle([
                'published_time'  => $publication->publish_at,
                'modified_time'   => $publication->updated_at,
                'tag'             => $tags->lists('name')
            ]);

        return view('pages.articles.item')->with([
            'item'             => $publication,
            'tags'             => $tags,
            'lastNews'         => $lastNews,
            'lastPublications' => $lastPublications,
        ]);
    }

    /**
     *
     * @param  Collection|Publication $publications
     * @return void
     */
    protected function loadRelations($publications)
    {
        $publications->load([
            'news' => function ($query) {
                $query->translatedIn()->active();
            },
            'publications' => function ($query) {
                $query->translatedIn()->active();
            },
            'lecturers' => function ($query) {
                $query->translatedIn()->active();
            },
            'chairs' => function ($query) {
                $query->translatedIn()->active();
            },
            'laboratories' => function ($query) {
                $query->translatedIn()->active();
            },
            'categories' => function ($query) {
                $query->translatedIn()->active()->with('translations');
            },
        ]);
        if ($publications->lecturers) {
            $publications->lecturers = $publications->lecturers->sortBy('title');
        }
    }

    /**
     *
     * @param  Collection|Publication $publications
     * @return void
     */
    protected function simpleLoadRelations($publications)
    {
        $publications->load([
            'categories' => function ($query) {
                $query->translatedIn()->active()->with('translations');
            },
            'statuses',
        ]);
    }
}
