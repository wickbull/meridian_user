<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelLocalization;
use App\Http\Requests;
use SEOMeta;
use OpenGraph;
use Twitter;
use Packages\Lecturer;
use Packages\Fragment;
use Packages\Chair;
use Packages\News;
use Packages\Publication;
use App\Extended\GalleryInserter;

class FacultyController extends Controller
{
    /**
     *
     * @param  Lecturer $lecturer
     * @return View
     */
    public function getLecturer(Lecturer $lecturer)
    {
        $lecturer->load([
            'news' => function ($query) {
                $query->translatedIn()->active()->ordered();
            },
            'publications' => function ($query) {
                $query->translatedIn()->active()->ordered();
            },
            'chairs' => function ($query) {
                $query->translatedIn()->active();
            },
            'subjects' => function ($query) {
                $query->translatedIn()->active();
            },
        ]);

        $lastNews = $lastPublications = null;

        if ($lecturer->news->isEmpty()) {
            $lastNews = News::active()->ordered()->limit(5)->get();
        }
        if ($lecturer->publications->isEmpty()) {
            $lastPublications = Publication::active()->ordered()->limit(5)->get();
        }

        SEOMeta::setTitle($lecturer->title);
        SEOMeta::setDescription($lecturer->getOgDescription());
        OpenGraph::addImage($lecturer->getImage('1200x630'));
        OpenGraph::setTitle($lecturer->title)
            ->setDescription($lecturer->getOgDescription())
            ->setType('profile')
            ->setProfile([
                'first_name' => $lecturer->getFirstName(),
                'last_name'  => $lecturer->getLastName(),
            ]);

        return view('pages.lecturers.item')->with([
            'lecturer'         => $lecturer,
            'lastNews'         => $lastNews,
            'lastPublications' => $lastPublications,
        ]);
    }

    /**
     *
     * @param  Lecturer $lecturer
     * @return View
     */
    public function getAboutFaculty()
    {
        $faculty = Fragment::active()
            ->whereSlug('about-faculty')->firstOrFail();

        $chairs = Chair::active()
            ->ordered()
            ->get();

        $dean = Lecturer::active()
            ->dean()
            ->ordered()
            ->first();

        SEOMeta::setTitle($faculty->title);
        SEOMeta::setDescription($faculty->getOgDescription());
        OpenGraph::setTitle($faculty->title);
        OpenGraph::setDescription($faculty->getOgDescription());

        return view('pages.about-faculty')->with([
            'faculty' => $faculty,
            'chairs'  => $chairs,
            'dean'    => $dean,
        ]);
    }

    /**
     *
     * @return View
     */
    public function getChairs()
    {
        $title = _('Кафедри');

        $chairs = Chair::active()
            ->ordered()->get();

        SEOMeta::setTitle($title);
        OpenGraph::setTitle($title);

        return view('pages.chairs.list')->with([
            'title' => $title,
            'items' => $chairs,
        ]);
    }

    /**
     *
     * @return View
     */
    public function getChair(Chair $chair)
    {
        $chair->load([
            'news' => function ($query) {
                $query->translatedIn()->active()->ordered();
            },
            'publications' => function ($query) {
                $query->translatedIn()->active()->ordered();
            },
            'mainLecturer' => function ($query) {
                $query->translatedIn()->active();
            },
            'lecturers' => function ($query) use ($chair) {
                $query->translatedIn()
                    ->active()
                    ->exclude($chair->mainLecturer);
            },
        ]);

        $lastNews = $lastPublications = null;

        if ($chair->lecturers) {
            $chair->lecturers = $chair->lecturers->sortBy('title');
        }
        if ($chair->news->isEmpty()) {
            $lastNews = News::active()->ordered()->limit(5)->get();
        }
        if ($chair->publications->isEmpty()) {
            $lastPublications = Publication::active()->ordered()->limit(5)->get();
        }

        SEOMeta::setTitle($chair->title);
        OpenGraph::setTitle($chair->title);
        OpenGraph::setDescription($chair->title);

        return view('pages.chairs.item')->with([
            'item'             => $chair,
            'lastNews'         => $lastNews,
            'lastPublications' => $lastPublications,
        ]);
    }

}
