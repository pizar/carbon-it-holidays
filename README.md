# Carbon Support for IT Holidays
This extends [Carbon](http://carbon.nesbot.com/) and adds support for several IT holidays.

I start from this repo: https://github.com/geoffreyrose/us-holidays and i customize for italian calendar 

### Supported Holidays
 * New Years Day
 * Epiphany
 * Easter
 * Easter Monday
 * Liberation Day
 * Labour Day
 * Republic day
 * Assumption of Mary
 * All Saints' Day
 * Immaculate Conception Day
 * Christmas Day
 * St. Stephen's Day

### Requirements
 * [Carbon](http://carbon.nesbot.com/)
 * PHP 8.1+

### Usage

#### With Composer
```
$ composer require pizar/carbon-it-holidays
```

```php
<?php
require 'vendor/autoload.php';

use ITHolidays\Carbon;
```

#### Without Composer

```php
<?php
require 'path/to/nesbot/Carbon.php';
require 'path/to/pizar/Carbon.php';

use ITHolidays\Carbon;
```
Check if date is holiday. Returns `boolean`
```php
$carbon = new Carbon();
$carbon = Carbon::create(2018, 12, 25);
$carbon->isHoliday(); // bool (true)
```

Get name if date is holiday. Returns `string` or `false`
```php
$carbon = new Carbon();
$carbon = Carbon::create(2018, 12, 31);
$carbon->getHolidayName(); // New Year's Eve
```

Get date for a specific holiday. Returns `string`
```php
$carbon = new Carbon();
$carbon = Carbon::create(2018, 1, 1);

$carbon->getNewYearsDayHoliday();                   // 2019-01-01 00:00:00
$carbon->getEpiphanyHoliday();                      // 2019-01-06 00:00:00
$carbon->getEasterMondayHoliday();                  // 2019-04-22 00:00:00
$carbon->getLiberationDayHoliday();                 // 2018-04-25 00:00:00
$carbon->getLabourDayHoliday();                     // 2018-05-01 00:00:00
$carbon->getRepublicDayHoliday();                   // 2018-06-02 00:00:00
$carbon->getAssumptionOfMaryHoliday();              // 2018-08-15 00:00:00
$carbon->getFerragosto();                           // 2018-08-15 00:00:00
$carbon->getAllSaintsDayHoliday();                  // 2018-11-01 00:00:00
$carbon->getImmaculateConceptionDayHoliday();       // 2018-12-08 00:00:00
$carbon->getChristmasDayHoliday();                  // 2018-12-25 00:00:00
$carbon->getStStephenDayHoliday();                  // 2018-12-26 00:00:00


```

see https://github.com/geoffreyrose/us-holidays for other examples too