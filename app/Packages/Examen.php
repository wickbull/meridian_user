<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Examen extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'examinations';

    /**
     * @var array
     */
    public $date_format = 'd.m.Y';

    /**
     * @var array
     */
    public $date_format_each = [
            'date_of_examen'      => 'Y-m-d H:i:s',
            'date_of_elimination' => 'Y-m-d H:i:s',
            'date_of_commission'  => 'Y-m-d H:i:s',
        ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
            'date_of_examen',
            'date_of_elimination',
            'date_of_commission',
        ];

    protected $touches = ['group'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'title',
            'type',
            'lecturer_of_examen',
            'lecturers_of_commission',
            'examination_room_of_examen',
            'examination_room_of_elimination',
            'examination_room_of_commission',
            'date_of_examen',
            'date_of_elimination',
            'date_of_commission',
        ];

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('date_of_examen', 'ASC');
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

    public function getExamenDate()
    {
        return $this->date_of_examen->format('d.m.Y');
    }

    public function getExamenTime()
    {
        return $this->date_of_examen->format('H:i');
    }

    public function getElimination()
    {
        return
            $this->date_of_elimination->format('d.m.Y') .' '.
            $this->date_of_elimination->format('H:i') .' '.
            $this->examination_room_of_elimination;
    }

    public function getCommission()
    {
        return
            $this->date_of_commission->format('d.m.Y') .' '.
            $this->date_of_commission->format('H:i') .' '.
            $this->examination_room_of_commission;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function group()
    {
        return $this->belongsTo('Packages\StudentsGroup', 'students_group_id');
    }

}
