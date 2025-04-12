<?php
/**
 * Author: Mohammad Babaei
 * Email: info@adschi.com
 * Website: https://adschi.com
 */
namespace PrestaYar\Localizer;

use Hekmatinasser\Verta\Verta;
use DateTimeZone;

/**
 * JalaliAdapter class to provide a compatible interface 
 * for Hekmatinasser\Verta to replace Morilog\Jalali
 */
class JalaliAdapter
{
    /**
     * @var Verta The Verta instance for handling Jalali dates
     */
    private $verta;

    /**
     * JalaliAdapter constructor.
     *
     * @param int|null $year
     * @param int|null $month
     * @param int|null $day
     * @param int|null $hour
     * @param int|null $minute
     * @param int|null $second
     * @param \DateTimeZone|null $timezone
     */
    public function __construct(
        ?int $year = null,
        ?int $month = null,
        ?int $day = null,
        ?int $hour = null,
        ?int $minute = null,
        ?int $second = null,
        ?\DateTimeZone $timezone = null
    ) {
        if ($year === null) {
            // Create from current time
            $this->verta = new Verta();
        } else {
            // Create from specified date
            $this->verta = new Verta();
            $this->verta->setJalaliYear($year);
            $this->verta->setJalaliMonth($month);
            $this->verta->setJalaliDay($day);
            
            if ($hour !== null) {
                $this->verta->setHour($hour);
                $this->verta->setMinute($minute ?? 0);
                $this->verta->setSecond($second ?? 0);
            }
            
            if ($timezone !== null) {
                $this->verta->setTimezone($timezone);
            }
        }
    }

    /**
     * Get current Jalali date
     *
     * @param \DateTimeZone|null $timeZone
     * @return JalaliAdapter
     */
    public static function now(\DateTimeZone $timeZone = null): JalaliAdapter
    {
        return new self(null, null, null, null, null, null, $timeZone);
    }

    /**
     * Convert from DateTime to Jalali
     *
     * @param \DateTime|\DateTimeInterface $dateTime
     * @param \DateTimeZone|null $timeZone
     * @return JalaliAdapter
     */
    public static function fromDateTime($dateTime, \DateTimeZone $timeZone = null): JalaliAdapter
    {
        $verta = new Verta($dateTime);
        $adapter = new self();
        $adapter->verta = $verta;
        
        if ($timeZone !== null) {
            $adapter->verta->setTimezone($timeZone);
        }
        
        return $adapter;
    }

    /**
     * Return formatted Jalali date
     *
     * @param string $format
     * @return string
     */
    public function format(string $format): string
    {
        // Convert from Morilog format to Verta format
        $format = str_replace(
            ['Y', 'm', 'd', 'H', 'i', 's'],
            ['Y', 'm', 'd', 'H', 'i', 's'],
            $format
        );
        
        return $this->verta->format($format);
    }

    /**
     * Return Jalali date string in Y/m/d format
     *
     * @return string
     */
    public function getJalali(): string
    {
        return $this->verta->format('Y/m/d');
    }

    /**
     * Convert to string in Y-m-d H:i:s format
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->verta->format('Y-m-d H:i:s');
    }

    /**
     * Convert to string in Y-m-d H:i:s format
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Get Jalali year
     *
     * @return int
     */
    public function getYear(): int
    {
        return $this->verta->year;
    }

    /**
     * Get Jalali month (1-12)
     *
     * @return int
     */
    public function getMonth(): int
    {
        return $this->verta->month;
    }

    /**
     * Get Jalali day (1-31)
     *
     * @return int
     */
    public function getDay(): int
    {
        return $this->verta->day;
    }

    /**
     * Get hour (0-23)
     *
     * @return int
     */
    public function getHour(): int
    {
        return $this->verta->hour;
    }

    /**
     * Get minute (0-59)
     *
     * @return int
     */
    public function getMinute(): int
    {
        return $this->verta->minute;
    }

    /**
     * Get second (0-59)
     *
     * @return int
     */
    public function getSecond(): int
    {
        return $this->verta->second;
    }

    /**
     * Get timezone
     *
     * @return \DateTimeZone
     */
    public function getTimezone()
    {
        return $this->verta->getTimezone();
    }

    /**
     * Whether the year is a leap year or not
     *
     * @return bool
     */
    public function isLeapYear(): bool
    {
        return $this->verta->isLeapYear();
    }

    /**
     * Convert Gregorian date to Jalali date
     *
     * @param int $gy
     * @param int $gm
     * @param int $gd
     * @return array
     */
    public static function toJalali($gy, $gm, $gd): array
    {
        $verta = new Verta(sprintf('%d-%d-%d', $gy, $gm, $gd));
        return [$verta->year, $verta->month, $verta->day];
    }

    /**
     * Convert Jalali date to Gregorian date
     *
     * @param int $jy
     * @param int $jm
     * @param int $jd
     * @return array
     */
    public static function toGregorian($jy, $jm, $jd): array
    {
        $verta = new Verta();
        $verta->setJalaliYear($jy);
        $verta->setJalaliMonth($jm);
        $verta->setJalaliDay($jd);
        
        $datetime = $verta->DateTime();
        return [$datetime->format('Y'), $datetime->format('n'), $datetime->format('j')];
    }

    /**
     * Convert Jalali date to Gregorian DateTime object
     *
     * @param int $jy
     * @param int $jm
     * @param int $jd
     * @return \DateTime
     */
    public static function toGregorianDate($jy, $jm, $jd)
    {
        $verta = new Verta();
        $verta->setJalaliYear($jy);
        $verta->setJalaliMonth($jm);
        $verta->setJalaliDay($jd);
        
        return $verta->DateTime();
    }

    /**
     * Convert to Carbon instance
     *
     * @return \Carbon\Carbon
     */
    public function toCarbon()
    {
        return \Carbon\Carbon::instance($this->verta->DateTime());
    }

    /**
     * Get underlying Verta instance
     *
     * @return Verta
     */
    public function getVerta(): Verta
    {
        return $this->verta;
    }

    /**
     * Get number of days in month for Jalali calendar
     *
     * @param int $jy
     * @param int $jm
     * @return int
     */
    public static function jalaliMonthLength($jy, $jm): int
    {
        $verta = new Verta();
        $verta->setJalaliYear($jy);
        $verta->setJalaliMonth($jm);
        
        return $verta->daysInMonth;
    }

    /**
     * Create a JalaliAdapter from format and timestamp
     *
     * @param string $format
     * @param string $timestamp
     * @param \DateTimeZone|null $timeZone
     * @return JalaliAdapter
     */
    public static function fromFormat(string $format, string $timestamp, \DateTimeZone $timeZone = null): JalaliAdapter
    {
        $dt = \DateTime::createFromFormat($format, $timestamp, $timeZone);
        return self::fromDateTime($dt, $timeZone);
    }
} 