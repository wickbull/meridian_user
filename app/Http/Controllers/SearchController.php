<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Response;

use Packages\Tag;
use Packages\News;
use Packages\Chair;
use Packages\Lecturer;
use Packages\Publication;
use Packages\Laboratory;

use LaravelLocalization;

class SearchController extends Controller
{

    public function getSearchByTag(Request $request)
    {
        $query = $request->get('tag');
        $counts = $this->fetchTagCounts($query);

        if ($counts['news']) {
            return redirect()->action(
                'SearchController@getNewsByTag', ['tag' => $query]
            );
        } elseif ($counts['publications']) {
            return redirect()->action(
                'SearchController@getPublicationsByTag', ['tag' => $query]
            );
        } else {
            return view('pages.search.tag', [
                'tag'     => $query,
                'result'  => collect(),
                'counts'  => $counts,
                'request' => $request,
            ]);
        }
    }

    public function getNewsByTag(Request $request)
    {
        $query = $request->get('tag');
        $counts = $this->fetchTagCounts($query);
        $tag = Tag::whereName($query)
            ->with('publications.original', 'news.original')
            ->first();

        return view('pages.search.tag', [
            'tag'     => $query,
            'result'  => $tag,
            'counts'  => $counts,
            'request' => $request,
        ]);
    }

    public function getPublicationsByTag(Request $request)
    {
        $query = $request->get('tag');
        $counts = $this->fetchTagCounts($query);
        $tag = Tag::whereName($query)
            ->with('publications.original', 'news.original')
            ->first();

        return view('pages.search.tag', [
            'tag'     => $query,
            'result'  => $tag,
            'counts'  => $counts,
            'request' => $request,
        ]);
    }

    public function fetchTagCounts($query)
    {
        $counts = [
            'publications' => 0,
            'news' => 0,
            'all' => 0,
        ];

        $tag = Tag::whereName($query)
            ->with('publications', 'news')
            ->first();

        if ($tag) {
            $counts['publications'] = $tag->publications->count();
            $counts['news'] = $tag->news->count();
            $counts['all'] = $counts['news'] + $counts['publications'];
        }

        return $counts;
    }

    public function getSearch(Request $request)
    {
        $query  = $this->fetchQueryStringFromRequest($request);
        $counts = $this->fetchCounts($query);

        if ($counts['news']) {
            return redirect()->action(
                'SearchController@getSearchNews', ['search' => $request->get('search')]
            );
        } elseif ($counts['publications']) {
            return redirect()->action(
                'SearchController@getSearchPublications', ['search' => $request->get('search')]
            );
        } elseif ($counts['chairs']) {
            return redirect()->action(
                'SearchController@getSearchChairs', ['search' => $request->get('search')]
            );
        } elseif ($counts['lecturers']) {
            return redirect()->action(
                'SearchController@getSearchLecturers', ['search' => $request->get('search')]
            );
        } elseif ($counts['laboratories']) {
            return redirect()->action(
                'SearchController@getSearchLaboratories', ['search' => $request->get('search')]
            );
        } else {
            return view('pages.search.all', [
                'search'  => $query,
                'result'  => collect(),
                'counts'  => $counts,
                'request' => $request,
            ]);
        }

    }

    public function getSearchNews(Request $request)
    {
        $query  = $this->fetchQueryStringFromRequest($request);
        $counts = $this->fetchCounts($query);
        $result = $this->fetchNews($query);

        return view('pages.search.all', [
            'search'  => $query,
            'result'  => $result,
            'counts'  => $counts,
            'request' => $request,
        ]);
    }

    public function getSearchPublications(Request $request)
    {
        $query  = $this->fetchQueryStringFromRequest($request);
        $counts = $this->fetchCounts($query);
        $result = $this->fetchPublications($query);

        return view('pages.search.all', [
            'search'  => $query,
            'result'  => $result,
            'counts'  => $counts,
            'request' => $request,
        ]);
    }

    public function getSearchChairs(Request $request)
    {
        $query  = $this->fetchQueryStringFromRequest($request);
        $counts = $this->fetchCounts($query);
        $result = $this->fetchChairs($query);

        return view('pages.search.all', [
            'search'  => $query,
            'result'  => $result,
            'counts'  => $counts,
            'request' => $request,
        ]);
    }

    public function getSearchLecturers(Request $request)
    {
        $query  = $this->fetchQueryStringFromRequest($request);
        $counts = $this->fetchCounts($query);
        $result = $this->fetchLecturers($query);

        return view('pages.search.all', [
            'search'  => $query,
            'result'  => $result,
            'counts'  => $counts,
            'request' => $request,
        ]);
    }

    public function getSearchLaboratories(Request $request)
    {
        $query  = $this->fetchQueryStringFromRequest($request);
        $counts = $this->fetchCounts($query);
        $result = $this->fetchLaboratories($query);

        return view('pages.search.all', [
            'search'  => $query,
            'result'  => $result,
            'counts'  => $counts,
            'request' => $request,
        ]);
    }

    public function getSearchAll(Request $request)
    {
        $result = collect();
        $counts = [];
        $query = $this->fetchQueryStringFromRequest($request);

        $news = $this->fetchNews($query);
        $chairs = $this->fetchChairs($query);
        $lecturers = $this->fetchLecturers($query);
        $publications = $this->fetchPublications($query);
        $laboratories = $this->fetchLaboratories($query);

        $result = $result->merge($news);
        $counts = array_add($counts, 'news', $news->count());

        $result = $result->merge($publications);
        $counts = array_add($counts, 'publications', $publications->count());

        $result = $result->merge($chairs);
        $counts = array_add($counts, 'chairs', $chairs->count());

        $result = $result->merge($lecturers);
        $counts = array_add($counts, 'lecturers', $lecturers->count());

        $result = $result->merge($laboratories);
        $counts = array_add($counts, 'laboratories', $laboratories->count());

        $counts = array_add($counts, 'all', array_sum($counts));

        $result->sortBy('created_at');
        $result = $this->paginate($result);

        return view('pages.search.all', [
            'search'  => $query,
            'result'  => $result,
            'counts'  => $counts,
            'request' => $request,
        ]);
    }

    public function paginate($result, $perPage = 12)
    {

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageSearchResults = $result->slice(($currentPage-1) * $perPage, $perPage)->all();
        $result = new LengthAwarePaginator($currentPageSearchResults, $result->count(), $perPage);

        return $result;
    }

    public function fetchCounts($query)
    {
        $counts = [];

        $news = News::active()
            ->whereTranslationLike('title', $query)
            ->count();
        $counts = array_add($counts, 'news', $news);

        $publications = Publication::active()
            ->whereTranslationLike('title', $query)
            ->count();
        $counts = array_add($counts, 'publications', $publications);

        $chairs = Chair::active()
            ->whereTranslationLike('title', $query)
            ->count();
        $counts = array_add($counts, 'chairs', $chairs);

        $lecturers = Lecturer::active()
            ->whereTranslationLike('title', $query)
            ->count();
        $counts = array_add($counts, 'lecturers', $lecturers);

        $laboratories = Laboratory::active()
            ->whereTranslationLike('title', $query)
            ->count();
        $counts = array_add($counts, 'laboratories', $laboratories);

        $counts = array_add($counts, 'all', array_sum($counts));

        return $counts;
    }

    /**
     * @param Request $request
     * @return string|boolean
     */
    public function fetchQueryStringFromRequest(Request $request)
    {
        if ($request->has('search')) {
            return '%' . $request->get('search') . '%';
        }

        return false;
    }

    /**
     * @param  string $string
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function fetchPublications($string)
    {
        return Publication::active()
            ->whereTranslationLike('title', $string)
            ->ordered()
            ->with([
                'categories' => function ($query) {
                    $query->translatedIn()->active()->with('translations');
                },
                'statuses',
            ])->paginate(12);
    }

    /**
     * @param  string $string
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function fetchNews($string)
    {
        return News::active()
            ->whereTranslationLike('title', $string)
            ->ordered()
            ->with([
                'categories' => function ($query) {
                    $query->translatedIn()->active()->with('translations');
                },
                'statuses',
            ])->paginate(12);
    }

    /**
     * @param  string $string
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function fetchLecturers($string)
    {
        return Lecturer::active()
            ->whereTranslationLike('title', $string)
            ->ordered()
            ->paginate(12);
    }

    /**
     * @param  string $string
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function fetchLaboratories($string)
    {
        return Laboratory::active()
            ->whereTranslationLike('title', $string)
            ->ordered()
            ->paginate(12);
    }

    /**
     * @param  string $string
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function fetchChairs($string)
    {
        return Chair::active()
            ->whereTranslationLike('title', $string)
            ->ordered()
            ->paginate(12);
    }

}
