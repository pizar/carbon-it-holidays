<?php 

require 'vendor/autoload.php';

use ITHolidays\Carbon;


$carbon = Carbon::create(2019, 1, 1);
$carbon->addHoliday("Sant'Apollinare", $year, 7, 23, ST_APOLLINARE, false);


for($counter=0; $counter<365; $counter++){
    echo $carbon->toDateString();
    echo " -> " . $carbon->getDateName();
    echo "\n";

    $carbon->addDay(1);
}