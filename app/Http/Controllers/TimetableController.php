<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use SEOMeta;
use OpenGraph;
use Twitter;
use Packages\Examen;
use Packages\Fragment;
use Packages\Semester;
use Packages\Timetable;
use Packages\StudentsGroup;

class TimetableController extends Controller
{

    public function getLessons()
    {
        $timetables = null;

        $semester = Semester::active()
            ->ordered()
            ->with(['groups' => function ($query) {
                $query->ordered()->active()->with('timetables');
            }])->first();

        if ($semester) {
            $timetables = $this->generateTimetable($semester->groups);
        }

        $fragment = Fragment::whereSlug('timetables-lessons')->active()->first();

        SEOMeta::setTitle('Розклад занять');
        OpenGraph::setTitle('Розклад занять');

        return view('pages.timetable.lessons', [
            'semester'   => $semester,
            'fragment'   => $fragment,
            'timetables' => $timetables,
        ]);
    }

    public function getExaminations()
    {
        $examinations = collect();

        $semester = Semester::active()
            ->ordered()
            ->with(['groups' => function ($query) {
                $query->ordered()
                    ->active()
                    ->with(['examinations' => function ($query) {
                        $query->ordered();
                    }]);
            }])->first();

        if ($semester) {
            foreach ($semester->groups as $group) {
                foreach ($group->examinations as $examen) {
                    $examinations->push($examen);
                }
            }
        }

        $fragment = Fragment::whereSlug('timetables-examinations')->active()->first();

        SEOMeta::setTitle('Розклад сесії');
        OpenGraph::setTitle('Розклад сесії');

        return view('pages.timetable.examinations', [
            'semester'     => $semester,
            'fragment'     => $fragment,
            'examinations' => $examinations,
        ]);
    }

    /**
     *
     * @param  StudentsGroup $group
     * @return
     */
    public function getLessonsTimetableByGroup(StudentsGroup $group)
    {
        $group->load('timetables', 'semester.groups');
        $timetables = $group->timetables->toArray();

        $fragment = Fragment::whereSlug('timetables-lessons')->active()->first();

        $timetables = $this->generateTimetableToGroup($group);

        SEOMeta::setTitle('Розклад занять');
        OpenGraph::setTitle('Розклад занять');

        return view('pages.timetable.lessons-view', [
            'group'      => $group,
            'fragment'   => $fragment,
            'timetables' => $timetables,
        ]);
    }

    /**
     *
     * @param  StudentsGroup $group
     * @return
     */
    public function getExaminationsTimetableByGroup(StudentsGroup $group)
    {
        $group->load('examinations', 'semester.groups');
        $examinations = $group->examinations;

        $fragment = Fragment::whereSlug('timetables-examinations')->active()->first();

        SEOMeta::setTitle('Розклад сесії');
        OpenGraph::setTitle('Розклад сесії');

        return view('pages.timetable.examinations-view', [
            'group'        => $group,
            'fragment'     => $fragment,
            'examinations' => $examinations,
        ]);
    }

    private function generateTimetable($groups)
    {

        $timetables = [];
        $fullTimetable = [];

        foreach ($groups as $group) {
            foreach ($group->timetables as $lesson) {
                $timetables
                    [$lesson->day_id]
                    [$lesson->lesson_number]
                    [$lesson->students_group_id]
                    [$lesson->id]
                 = $lesson->toArray();
            }
        }

        foreach ($timetables as $day_id => $lessonsByDay) {
            foreach ($lessonsByDay as $number => $lessonsByNumber) {
                foreach ($lessonsByNumber as $groupId => $lessonsByGroup) {

                    foreach ($groups as $group) {
                        $count = 1;
                        foreach ($lessonsByGroup as $lessonId => $lesson) {
                            if (!isset($timetables[$lesson['day_id']][$lesson['lesson_number']][$group->id])) {

                                if (!isset($fullTimetable[$lesson['day_id']][$lesson['lesson_number']]['max'])) {
                                    $fullTimetable
                                        [$lesson['day_id']]
                                        [$lesson['lesson_number']]
                                        ['max'] = 1;
                                } elseif ($fullTimetable[$lesson['day_id']][$lesson['lesson_number']]['max'] < $count) {
                                    $fullTimetable
                                        [$lesson['day_id']]
                                        [$lesson['lesson_number']]
                                        ['max'] = $count;
                                }

                                $fullTimetable
                                    [$lesson['day_id']]
                                    [$lesson['lesson_number']]
                                    [$group->id.$count]
                                     = $this->generateEmpty($count, $lesson['day_id'], $group->id, $lesson['lesson_number']);
                            } else {
                                if (!isset($fullTimetable[$lesson['day_id']][$lesson['lesson_number']]['max'])) {
                                    $fullTimetable
                                        [$lesson['day_id']]
                                        [$lesson['lesson_number']]
                                        ['max'] = 1;
                                } elseif ($fullTimetable[$lesson['day_id']][$lesson['lesson_number']]['max'] < $count) {
                                    $fullTimetable
                                        [$lesson['day_id']]
                                        [$lesson['lesson_number']]
                                        ['max'] = $count;
                                }

                                $fullTimetable
                                    [$lesson['day_id']]
                                    [$lesson['lesson_number']]
                                    [$lesson['students_group_id'].$count]
                                     = $lesson;
                            }
                            $count++;
                        }
                    }
                }
            }
        }

        return $fullTimetable;
    }

    /**
     *
     * @param  string|null  $id
     * @param  string|null  $dayId
     * @param  string|null  $groupId
     * @param  string|null  $number
     * @return arrary
     */
    private function generateEmpty($id = null, $dayId = null, $groupId = null, $number = null)
    {
        $empty = [
            'id'                => $id,
            'day_id'            => $dayId,
            'students_group_id' => $groupId,
            'lesson_number'     => $number,
            'title'             => ' ',
            'weeks_lessons'     => null,
            'audience'          => null,
            'lecturer'          => null,
            'created_at'        => null,
            'updated_at'        => null,
            'deleted_at'        => null,
        ];

        return $empty;
    }

    /**
     *
     * @param  StudentsGroup $group
     * @return
     */
    private function generateTimetableToGroup(StudentsGroup $group)
    {
        $timetables = $group->timetables->toArray();

        $fullTimetable = [
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => [],
            7 => [],
        ];

        $noLesson = [
            'id'                => null,
            'day_id'            => null,
            'students_group_id' => null,
            'lesson_number'     => null,
            'title'             => ' ',
            'weeks_lessons'     => null,
            'audience'          => null,
            'lecturer'          => null,
            'created_at'        => null,
            'updated_at'        => null,
            'deleted_at'        => null,
        ];

        foreach ($timetables as $key => $lesson) {
            if (isset($fullTimetable[$lesson['day_id']][$lesson['lesson_number']])) {
                foreach ($fullTimetable[$lesson['day_id']][$lesson['lesson_number']] as $lessonInOneNumber) {
                    if ($lessonInOneNumber['lesson_number'] == $lesson['lesson_number']) {
                        $fullTimetable[$lesson['day_id']][$lesson['lesson_number']][$lesson['id']] = $lesson;
                    }
                }
            } else {
                $fullTimetable[$lesson['day_id']][$lesson['lesson_number']][$lesson['id']] = $lesson;
            }
        }

        foreach ($fullTimetable as $key => $value) {
            if (!$value) {
                $fullTimetable[$key]['empty'] = $noLesson;
            }
        }

        return $fullTimetable;
    }

}
