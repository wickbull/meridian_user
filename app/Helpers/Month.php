<?php

namespace App\Helpers;

use LaravelLocalization;

class Month
{
    /**
     * @var integer
     */
    protected $number;

    /**
     * @param integer $number
     */
    public function __construct($number)
    {
        $this->number = intval($number);
    }

    /**
     * @param integer $number
     */
    public static function create($number)
    {
        return new self($number);
    }

    /**
     *
     */
    public static function now()
    {
        return new self(date('m'));
    }

    /**
     * @return string
     */
    public function getInGenitiveCase($locale = null)
    {
        if (! $locale) {
            $locale = LaravelLocalization::getCurrentLocale();
        }

        $method_name = 'getInGenitiveCase' . strtoupper($locale);

        if (method_exists($this, $method_name)) {
            return $this->$method_name();
        }
    }

    /**
     * @return string
     */
    public function getInGenitiveCaseUK()
    {
        switch ($this->number) {
            case 1:
                return 'Січня';
            case 2:
                return 'Лютого';
            case 3:
                return 'Березня';
            case 4:
                return 'Квітня';
            case 5:
                return 'Травня';
            case 6:
                return 'Червня';
            case 7:
                return 'Липня';
            case 8:
                return 'Серпня';
            case 9:
                return 'Вересня';
            case 10:
                return 'Жовтня';
            case 11:
                return 'Листопада';
            case 12:
                return 'Грудня';
        }
    }

    /**
     * @return string
     */
    public function getInGenitiveCaseRU()
    {
        switch ($this->number) {
            case 1:
                return 'Января';
            case 2:
                return 'Февраля';
            case 3:
                return 'Марта';
            case 4:
                return 'Апреля';
            case 5:
                return 'Мая';
            case 6:
                return 'Июня';
            case 7:
                return 'Июля';
            case 8:
                return 'Августа';
            case 9:
                return 'Сентября';
            case 10:
                return 'Октября';
            case 11:
                return 'Ноября';
            case 12:
                return 'Декабря';
        }
    }

    /**
     * @return string
     */
    public function getInGenitiveCaseEN()
    {
        switch ($this->number) {
            case 1:
                return 'January';
            case 2:
                return 'February';
            case 3:
                return 'March';
            case 4:
                return 'April';
            case 5:
                return 'May';
            case 6:
                return 'June';
            case 7:
                return 'July';
            case 8:
                return 'August';
            case 9:
                return 'September';
            case 10:
                return 'October';
            case 11:
                return 'November';
            case 12:
                return 'December';
        }
    }
}
