<?php

class Date {
    private $year;
    private $month;
    private $day;

    public function __construct() {
        $ctp = func_num_args();
        $args = func_get_args();
        
        if ($ctp == 2) {
            $date = $args[0];
            $format = $args[1];
            switch ($format) {
                case "en":
                    $this->year = intval(substr($date, 6));
                    $this->month = intval(substr($date, 0, 2));
                    $this->day = intval(substr($date, 3, 2));
                    break;
                case "fr":
                    $this->year = intval(substr($date, 6));
                    $this->month = intval(substr($date, 3, 2));
                    $this->day = intval(substr($date, 0, 2));
                    break;
                case "in":
                    $this->year = intval(substr($date, 0, 4));
                    $this->month = intval(substr($date, 5, 2));
                    $this->day = intval(substr($date, 8, 2));
                    break;
            }
        } else if ($ctp == 3) {
            $this->year = $args[0];
            $this->month = $args[1];
            $this->day = $args[2];
        }
    }
    
    public function toString($format) {
        $str_month = $this->month;
        $str_day = $this->day;
        if ($this->month < 10) {
            $str_month = '0' . $this->month;
        }
        if ($this->day < 10) {
            $str_day = '0' . $this->day;
        }
        switch ($format) {
            case "en":
                return $str_month.'/'.$str_day.'/'.$this->year;
            case "fr":
                return $str_day.'/'.$str_month.'/'.$this->year;
            case "in":
                return $this->year.'-'.$str_month.'-'.$str_day;
        }
    }
}