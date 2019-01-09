<?php 

require 'vendor/autoload.php';

use ITHolidays\Carbon;


$carbon = Carbon::create(2019, 1, 1);
for($i=0; $i<365; $i++){
    echo $carbon->toDateString();
    echo " :: isWorkingDay :: ". $carbon->isWorkingDay();
    if ($carbon->isHoliday()){
        echo " -> " . $carbon->getHolidayName();
    }
    echo "\n";

    $carbon->addDay(1);
}