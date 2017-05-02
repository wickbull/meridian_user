<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentsGroup extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    public $date_format = 'd.m.Y';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'title',
            'slug',
            'specialty',
            'course',
            'is_active',
        ];

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('course', 'ASC');
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
     public function scopeActive($query)
     {
         return $query->whereIsActive(true);
     }

    /**
     *
     * @return string
     */
    public function getViewLessonsUrl()
    {
        return route('timetable.lessonsView', $this->slug);
    }

    /**
     *
     * @return string
     */
    public function getViewExaminationUrl()
    {
        return route('timetable.examinationsView', $this->slug);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function semester()
    {
        return $this->belongsTo('Packages\Semester');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function timetables()
    {
        return $this->hasMany('Packages\Timetable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function examinations()
    {
        return $this->hasMany('Packages\Examen');
    }

}
