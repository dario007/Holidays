<?php

namespace BalkanHolidays;

class Holidays {

    public $year;
    public $land;
    public $language;

    public function  __construct($year, $land, $language = 'en') {
        $this->year = $year;
        $this->land = $land;
        $this->language = $language;
    }

    public function getHolidays() {
        $holidays = [];
        foreach ($this->land as $land) {
            $get_file = file_get_contents(__DIR__."/holidays/".$land."_holidays.json");
            $added_religios_dates = $this->addCalculatedDates(json_decode($get_file, true));
            $holidays[$land] = [$added_religios_dates];
        }
        return $holidays;
    }

    private function getTranslation($date_name) {
        $get_file = file_get_contents(__DIR__."/translations/".$this->language.".json");
        $translations = json_decode($get_file, true);
        foreach ($translations as $key => $trans) {
            if( $key == $date_name ) {
                return $trans;
            }
        }
    }

    private function addCalculatedDates($data_set) {
        $holidays = [];
        foreach ($data_set as $dates) {
            $region_name = $dates['name'];
            if ($dates['dates']) {
                foreach ($dates['dates'] as $date_name => $date) {
                    if ($date['date'] == "") {
                        $date['date'] = call_user_func(array($this, $this->snakeToCamel($date_name)));
                    }
                    $holidays[$region_name][] = [
                        'date' => $date['date'],
                        'name' => $this->getTranslation($date_name)
                    ];
                }
            }
        }
        return $holidays;
    }

    private function snakeToCamel($string) {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        $str[0] = strtolower($str[0]);
        return $str;
    }

    public function goodFriday() {
        $easter = date("Y-m-d", easter_date($this->year));
        return date('d.m.', strtotime($easter . ' -2 day'));
    }

    public function easterSunday() {
        return date("d.m.",easter_date());
    }

    public function easterMonday() {
        $easter = date("Y-m-d", easter_date($this->year));
        return date('d.m.', strtotime($easter . ' +1 day'));
    }

    public function ascensionDay() {
        $easter = date("Y-m-d", easter_date($this->year));
        return date('d.m.', strtotime($easter . ' +39 day'));
    }

    public function whitMonday() {
        $easter = date("Y-m-d", easter_date($this->year));
        return date('d.m.', strtotime($easter . ' +50 day'));
    }

    public function corpusChristi() {
        $easter = date("Y-m-d", easter_date($this->year));
        $corpus_christi = date('Y-m-d', strtotime("next thursday", strtotime($easter)));;
        return date('d.m.', strtotime($corpus_christi . ' +56 day'));
    }

    private function repentanceAndPrayerDay() {
        return date('d.m.', strtotime("16.11.$this->year" . 'next wednesday'));
    }


}