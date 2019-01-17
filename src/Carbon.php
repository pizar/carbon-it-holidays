<?php 

namespace ITHolidays;

class Carbon extends \Carbon\Carbon {

    /**
     * Return all the date for the italian holidays
     *
     * @param null $year
     * @return array
     */
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

    /**
     * Check if its an italian holiday. Comparing the date/month values of the two dates.
     *
     * @param null $year
     * @return bool
     */
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

    /**
     * Check if its an working day.
     *
     * @return bool
     */
    public function isWorkingDay(){
        if ($this->isHoliday()){
            return false;
        }

        if ($this->dayOfWeek == 6 || $this->dayOfWeek == 0){
            return false;
        }

        return true;
    }

    /**
     * Check if its not an working day.
     *
     * @return bool
     */
    public function isNotWorkingDay(){
        return !($this->isWorkingDay());
    }

    /**
     * Check if its an bank holiday.
     *
     * @return bool
     */
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

    /**
     * Check if its an italian holiday.
     * If it is return the name.
     *
     * @return bool
     */
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
     * Return the date for the "New Year's Day" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "Epiphany" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "Easter" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "Easter Monday" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "Liberation day" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "Labour day" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "Republic day" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "Assumption of Mary" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "Ferragosto" Holiday
     *
     * @param null $year
     * @return mixed
     */
    public function getFerragostoHoliday($year = null)
    {
        return getAssumptionOfMaryHoliday($year);
    }

    /**
     * Return the date for the "All Saints' Day" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "Immaculate Conception Day" Holiday
     *
     * @param null $year
     * @return mixed
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
     * Return the date for the "ChristmasDay" Holiday
     *
     * @param null $year
     * @return mixed
     */
    public function getHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(11, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * Return the date for the "St. Stephen's Day" Holiday
     *
     * @param null $year
     * @return mixed
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