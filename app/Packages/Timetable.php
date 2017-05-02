<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    public $date_format = 'd.m.Y';

    protected $touches = ['group'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'day_id',
            'title',
            'lesson_number',
            'weeks_lessons',
            'audience',
            'lecturer',
        ];

    /**
     *
     * @param  string  $value
     * @return string
     */
    public function getTitleAttribute($value)
    {
        if ($this->weeks_lessons) {
            return $value . ' (' . $this->weeks_lessons . ') ';
        } else {
            return $value;
        }
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('lesson_number', 'ASC');
    }

    /**
     * @return  Action
     */
    public function getEditUrl()
    {
        return '#';
    }

    /**
     * @return  Action
     */
    public function getViewUrl()
    {
        return '#';
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function group()
    {
        return $this->belongsTo('Packages\StudentsGroup', 'students_group_id');
    }

}
