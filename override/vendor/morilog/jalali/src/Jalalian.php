<?php
/**
 * Author: Mohammad Babaei
 * Email: info@adschi.com
 * Website: https://adschi.com
 */
namespace Morilog\Jalali;

use Assert\Assertion;
use Carbon\Carbon;

/**
 * Custom override for the Jalalian class to fix the issue with the Jalali calendar
 * - Fixes the transition between the end of Esfand 1403 and the beginning of Farvardin 1404
 * - Corrects the number of days in Esfand for leap years
 */
class Jalalian
{
    use Converter;

    /**
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    /**
     * @var int
     */
    private $day;

    /**
     * @var int
     */
    private $hour;

    /**
     * @var int
     */
    private $minute;

    /**
     * @var int
     */
    private $second;

    /**
     * @var \DateTimeZone
     */
    private $timezone;

    public function __construct(
        int $year,
        int $month,
        int $day,
        int $hour = 0,
        int $minute = 0,
        int $second = 0,
        \DateTimeZone $timezone = null
    ) {
        // Validate dates
        Assertion::between($year, 1000, 3000);
        Assertion::between($month, 1, 12);
        
        // Get the maximum number of days for this month
        $maxDays = CalendarUtils::jalaliMonthLength($year, $month);
        Assertion::between($day, 1, $maxDays);
        
        Assertion::between($hour, 0, 24);
        Assertion::between($minute, 0, 59);
        Assertion::between($second, 0, 59);

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->timezone = $timezone;
    }

    public static function now(\DateTimeZone $timeZone = null): Jalalian
    {
        return static::fromCarbon(Carbon::now($timeZone));
    }

    /**
     * @param Carbon $carbon
     * @return Jalalian
     */
    public static function fromCarbon(Carbon $carbon): Jalalian
    {
        $jDate = CalendarUtils::toJalali($carbon->year, $carbon->month, $carbon->day);

        return new static(
            $jDate[0],
            $jDate[1],
            $jDate[2],
            $carbon->hour,
            $carbon->minute,
            $carbon->second,
            $carbon->getTimezone()
        );
    }

    // Other methods from the original class that are needed for our implementation
    
    public function getYear(): int
    {
        return $this->year;
    }
    
    public function getMonth(): int
    {
        return $this->month;
    }
    
    public function getDay(): int
    {
        return $this->day;
    }
    
    public function getHour(): int
    {
        return $this->hour;
    }
    
    public function getMinute(): int
    {
        return $this->minute;
    }
    
    public function getSecond(): int
    {
        return $this->second;
    }
    
    public function getTimezone()
    {
        return $this->timezone;
    }
    
    public function isLeapYear(): bool
    {
        return CalendarUtils::isLeapJalaliYear($this->year);
    }
    
    public function format(string $format): string
    {
        $d = function ($day) {
            return ($day < 10) ? '0' . $day : (string)$day;
        };
        $m = function ($month) {
            return ($month < 10) ? '0' . $month : (string)$month;
        };
        $y = function ($year) {
            return (string)$year;
        };

        $formatted = '';
        for ($i = 0; $i < strlen($format); $i++) {
            $char = $format[$i];
            switch ($char) {
                case 'Y':
                    $formatted .= $y($this->year);
                    break;
                case 'y':
                    $formatted .= substr($y($this->year), -2);
                    break;
                case 'm':
                    $formatted .= $m($this->month);
                    break;
                case 'n':
                    $formatted .= (string)$this->month;
                    break;
                case 'd':
                    $formatted .= $d($this->day);
                    break;
                case 'j':
                    $formatted .= (string)$this->day;
                    break;
                case 'H':
                    $formatted .= ($this->hour < 10) ? '0' . $this->hour : (string)$this->hour;
                    break;
                case 'i':
                    $formatted .= ($this->minute < 10) ? '0' . $this->minute : (string)$this->minute;
                    break;
                case 's':
                    $formatted .= ($this->second < 10) ? '0' . $this->second : (string)$this->second;
                    break;
                default:
                    $formatted .= $char;
                    break;
            }
        }
        return $formatted;
    }
    
    public function toString(): string
    {
        return $this->format('Y-m-d H:i:s');
    }
    
    public function __toString(): string
    {
        return $this->toString();
    }
    
    public static function fromFormat(string $format, string $timestamp, \DateTimeZone $timeZone = null): Jalalian
    {
        return static::fromCarbon(CalendarUtils::createCarbonFromFormat($format, $timestamp, $timeZone));
    }
    
    public static function forge($timestamp, \DateTimeZone $timeZone = null): Jalalian
    {
        return static::fromDateTime($timestamp, $timeZone);
    }
    
    public static function fromDateTime($dateTime, \DateTimeZone $timeZone = null): Jalalian
    {
        if (is_numeric($dateTime)) {
            return static::fromCarbon(Carbon::createFromTimestamp($dateTime, $timeZone));
        }

        return static::fromCarbon(new Carbon($dateTime, $timeZone));
    }
    
    public function toCarbon(): Carbon
    {
        $gregorian = CalendarUtils::toGregorian($this->year, $this->month, $this->day);
        $carbon = Carbon::createFromDate($gregorian[0], $gregorian[1], $gregorian[2], $this->timezone);
        $carbon->setTime($this->hour, $this->minute, $this->second);

        return $carbon;
    }
} 