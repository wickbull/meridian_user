<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use SEOMeta;
use OpenGraph;
use Twitter;
use Packages\Subject;

class SubjectsController extends Controller {

    /**
     *
     * @return View
     */
    public function getList()
    {
        $title = _('Предмети');

        $subjects = Subject::active()
            ->ordered()
            ->paginate(12);

        SEOMeta::setTitle($title);
        OpenGraph::setTitle($title);

        return view('pages.subjects.list')->with([
            'title' => $title,
            'items' => $subjects,
        ]);
    }

    /**
     *
     * @return view
     */
    public function getView(Subject $subject)
    {
        $this->loadRelations($subject);

        SEOMeta::setTitle($subject->title);
        OpenGraph::setTitle($subject->title);

        return view('pages.subjects.item', [
            'subject' => $subject,
        ]);
    }

    /**
     *
     * @param  Subject|Collection $subjects
     * @return void
     */
    protected function loadRelations($subjects)
    {
        $subjects->load([
            'lecturers' => function ($query) {
                $query->translatedIn()->active();
            }
        ]);
        if ($subjects->lecturers) {
            $subjects->lecturers = $subjects->lecturers->sortBy('title');
        }
    }

}
