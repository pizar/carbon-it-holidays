<?php 

namespace ITHolidays;

class Carbon extends \Carbon\Carbon {

    private function getHolidays( $year = null ) {
        
        if( $year === null) {
            $year = date('Y');
        }

        // Easter Monday
        $easter = Carbon::create($year, 3, 21)->addDays(easter_days($year));
        $easterMonday = Carbon::create($year, 3, 21)->addDays(easter_days($year) +1);

        $holidays = array(
        	array(
                'name' => "New Year's Day",
                'date' => Carbon::create($year, 1, 1),
                'bank_holiday' => true,
                'id' => 1
            ),
        	array(
                'name' => "Epiphany",
                'date' => Carbon::create($year, 1, 6),
                'bank_holiday' => true,
                'id' => 2
            ),
            array(
                'name' => "Easter",
                'date' => $easter,
                'bank_holiday' => true,
                'id' => 3
            ),
            array(
                'name' => "Easter Monday",
                'date' => $easterMonday,
                'bank_holiday' => true,
                'id' => 4
            ),
            array(
                'name' => "Liberation Day",
                'date' => Carbon::create($year, 4, 25),
                'bank_holiday' => true,
                'id' => 5
            ),
        	array(
                'name' => "Labour Day",
                'date' => Carbon::create($year, 5, 1),
                'bank_holiday' => true,
                'id' => 6
            ),
            array(
                'name' => "Republic day",
                'date' => Carbon::create($year, 6, 2),
                'bank_holiday' => true,
                'id' => 7
            ),
            array(
                'name' => "Assumption of Mary",
                'date' => Carbon::create($year, 8, 15),
                'bank_holiday' => true,
                'id' => 8
            ),
            array(
                'name' => "All Saints' Day",
                'date' => Carbon::create($year, 11, 1),
                'bank_holiday' => true,
                'id' => 9
            ),
            array(
                'name' => "Immaculate Conception Day",
                'date' => Carbon::create($year, 12, 8),
                'bank_holiday' => true,
                'id' => 10
            ),
        	array(
                'name' => "Christmas Day",
                'date' => Carbon::create($year, 12, 25),
                'bank_holiday' => true,
                'id' => 11
            ),
            array(
                'name' => "St. Stephen's Day",
                'date' => Carbon::create($year, 12, 26),
                'bank_holiday' => true,
                'id' => 12
            ),
        );

        return $holidays;
    }

    public function isHoliday($year = null)
	{
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);
        $isHoliday = false;

        foreach ($holidays as $holiday) {
            if( $this->isBirthday($holiday['date']) ) {
                $isHoliday = true;
            }
        }

        return $isHoliday;
    }

    public function isWorkingDay(){
        if ($this->isHoliday()){
            return false;
        }

        if ($this->dayOfWeek == 6 || $this->dayOfWeek == 0){
            return false;
        }

        return true;
    }

    public function isNotWorkingDay(){
        return !($this->isWorkingDay());
    }

    public function isBankHoliday($year = null)
	{
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);
        $isBankHoliday = false;

        foreach ($holidays as $holiday) {
            if( $this->isBirthday($holiday['date']) && $holiday['bank_holiday'] ) {
                if($this->dayOfWeek !== Carbon::SUNDAY && $this->dayOfWeek !== Carbon::SATURDAY) {
                    $isBankHoliday = true;
                }

            } else {
                if( $this->dayOfWeek === Carbon::MONDAY ) {
                    $this->subDay();

                    if( $this->isBirthday($holiday['date']) && $holiday['bank_holiday'] ) {
                        $isBankHoliday = true;
                    } else {
                        $this->addDay();
                    }
                } else if( $this->dayOfWeek === Carbon::FRIDAY ) {
                    $this->addDay();

                    if( $this->isBirthday($holiday['date']) && $holiday['bank_holiday'] ) {
                        $isBankHoliday = true;
                    } else {
                        $this->subDay();
                    }
                }
            }
        }

        return $isBankHoliday;
    }

    public function getHolidayName($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);
        $holidayName = false;

        foreach ($holidays as $holiday) {
            if( $this->isBirthday($holiday['date']) ) {
                $holidayName = $holiday['name'];
            } else {
                if( $this->dayOfWeek === Carbon::MONDAY ) {
                    $this->subDay();

                    if( $this->isBirthday($holiday['date']) && $holiday['bank_holiday'] ) {
                        $holidayName = $holiday['name'] . ' (Observed)';
                    } else {
                        $this->addDay();
                    }
                } else if( $this->dayOfWeek === Carbon::FRIDAY ) {
                    $this->addDay();

                    if( $this->isBirthday($holiday['date']) && $holiday['bank_holiday'] ) {
                        $holidayName = $holiday['name'] . ' (Observed)';
                    } else {
                        $this->subDay();
                    }
                }
            }
        }

        return $holidayName;
    }


    /**
     * getNewYearsDayHoliday
     */
    public function getNewYearsDayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(1, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * getEpiphany
     */
    public function getEpiphanyHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(2, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * getEaster
     */
    public function getEasterHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(3, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * getEasterMonday
     */
    public function getEasterMondayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(4, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * Liberation Day
     */
    public function getLiberationDayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(5, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * Labour Day
     */
    public function getLabourDayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(6, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * Republic Day
     */
    public function getRepublicDayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(7, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * Assumption of Mary
     */
    public function getAssumptionOfMaryHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(8, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * Assumption of Mary
     */
    public function getFerragostoHoliday($year = null)
    {
        return getAssumptionOfMaryHoliday($year);
    }

    /**
     * All Saints' Day
     */
    public function getAllSaintsDayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(9, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * Immaculate Conception Day
     */
    public function getImmaculateConceptionDayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(10, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * Christmas Day
     */
    public function getChristmasDayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(11, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * St. Stephen's Day
     */
    public function getStStephenDayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(12, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }
}