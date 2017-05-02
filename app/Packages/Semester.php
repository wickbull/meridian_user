<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    public $date_format = 'd.m.Y';

    /**
     * @var array
     */
    public $date_format_each = [
            'start_at'  => 'd.m.Y',
            'finish_at' => 'd.m.Y',
        ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
            'start_at',
            'finish_at',
        ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'title',
            'slug',
            'start_at',
            'finish_at',
            'is_active',
        ];

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('start_at', 'DESC');
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
    public function groups()
    {
        return $this->hasMany('Packages\StudentsGroup');
    }

}
