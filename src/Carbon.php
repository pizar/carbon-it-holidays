<?php 

namespace ITHolidays;

class Carbon extends \Carbon\Carbon {

    private $locale_holidays = array();

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
                'name' => "Nuovo anno",
                'date' => Carbon::create($year, 1, 1),
                'bank_holiday' => true,
                'id' => ItalianHoliday::NEW_YEAR
            ),
        	array(
                'name' => "Epifania",
                'date' => Carbon::create($year, 1, 6),
                'bank_holiday' => true,
                'id' => ItalianHoliday::EPIPHANY
            ),
            array(
                'name' => "Pasqua",
                'date' => $easter,
                'bank_holiday' => true,
                'id' => ItalianHoliday::EASTER
            ),
            array(
                'name' => "Lunedi di Pasqua",
                'date' => $easterMonday,
                'bank_holiday' => true,
                'id' => ItalianHoliday::EASTER_MONDAY
            ),
            array(
                'name' => "Giorno della liberazione",
                'date' => Carbon::create($year, 4, 25),
                'bank_holiday' => true,
                'id' => ItalianHoliday::LIBERATION_DAY
            ),
        	array(
                'name' => "Giornata dei lavoratori",
                'date' => Carbon::create($year, 5, 1),
                'bank_holiday' => true,
                'id' => ItalianHoliday::LABOUR_DAY
            ),
            array(
                'name' => "Giorno della repubblica",
                'date' => Carbon::create($year, 6, 2),
                'bank_holiday' => true,
                'id' => ItalianHoliday::REPUBLIC_DAY
            ),
            array(
                'name' => "Ferragosto",
                'date' => Carbon::create($year, 8, 15),
                'bank_holiday' => true,
                'id' => ItalianHoliday::FERRAGOSTO
            ),
            array(
                'name' => "Ogni santi",
                'date' => Carbon::create($year, 11, 1),
                'bank_holiday' => true,
                'id' => ItalianHoliday::ALL_SAINTS
            ),
            array(
                'name' => "Immacolata concezione",
                'date' => Carbon::create($year, 12, 8),
                'bank_holiday' => true,
                'id' => ItalianHoliday::IMMACULATE
            ),
        	array(
                'name' => "Natale",
                'date' => Carbon::create($year, 12, 25),
                'bank_holiday' => true,
                'id' => ItalianHoliday::XMAS_DAY
            ),
            array(
                'name' => "Santo Stefano",
                'date' => Carbon::create($year, 12, 26),
                'bank_holiday' => true,
                'id' => ItalianHoliday::ST_STEPHEN
            ),
        );

        $holidays = array_merge($holidays, $this->locale_holidays);
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
        $holidayName = null;

        foreach ($holidays as $holiday) {
            if( $this->isBirthday($holiday['date']) ) {
                $holidayName = $holiday['name'];
            }
        }

        return $holidayName;
    }

    /**
     * Return the holidayname or dayname
     * if the day is not an holiday
     *
     * @param null $year
     * @return bool|string|null
     */
    public function getDateName($year = null){
        $dayName = null;

        if ($this->isHoliday()) {
            $dayName = $this->getHolidayName();
        }else{
            $dayName = $this->format("l");
        }

        return $dayName;
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

        $index = array_search(ItalianHoliday::NEW_YEAR, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::EPIPHANY, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::EASTER, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::EASTER_MONDAY, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::LIBERATION_DAY, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::LABOUR_DAY, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::REPUBLIC_DAY, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::FERRAGOSTO, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::ALL_SAINTS, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::IMMACULATE, array_column($holidays, 'id') );
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
    public function getChristmasDayHoliday($year = null)
    {
        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search(ItalianHoliday::XMAS_DAY, array_column($holidays, 'id') );
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

        $index = array_search(ItalianHoliday::ST_STEPHEN, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * @param $key
     * @param null $year
     * @return mixed
     */
    public function getHolidayById($year = null, $id){

        $year = $year ? $year : $this->year;
        $holidays = $this->getHolidays($year);

        $index = array_search($id, array_column($holidays, 'id') );
        $date = $holidays[$index]['date'];

        $this->modify($date);
        return $date;
    }

    /**
     * @param $name
     * @param null $year
     * @param $month
     * @param $day
     * @param $bank_holiday
     */
    public function addHoliday($name, $year = null, $month, $day, $id, $bank_holiday){
        $tmp = array(
            'name' => $name,
            'date' => Carbon::create($year, $month, $day),
            'bank_holiday' => $bank_holiday,
            'id' => $id
        );
        array_push($this->locale_holidays, $tmp);
    }
}