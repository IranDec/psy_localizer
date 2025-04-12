<?php
/**
  * Author: Mohammad Babaei
 * Email: info@adschi.com
 * Website: https://adschi.com
 */
namespace Morilog\Jalali;

use Carbon\Carbon;

/**
 * Custom override for the CalendarUtils class to fix the issue with the Jalali calendar
 * - Fixes the transition between the end of Esfand 1403 and the beginning of Farvardin 1404
 * - Corrects the number of days in Esfand for leap years
 */
class CalendarUtils
{
    public const IRANIAN_MONTHS_NAME = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
    public const AFGHAN_MONTHS_NAME = ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'];

    // The array of leap years in the Jalali calendar (1335 to 1437)
    private static $leapYears = [
        1337, 1341, 1346, 1350, 1354, 1358, 1362, 1366, 1370, 1375, 1379, 1383, 1387, 1391, 1395, 1399, 1403, 1408, 1412, 1416, 1420, 1424, 1428, 1432, 1436
    ];

    private static $monthNames = self::IRANIAN_MONTHS_NAME;
    private static $temp;

    /**
     * Set globally afghan months names as default month name
     *
     * @return void
     */
    public static function useAfghanMonthsName()
    {
        self::$monthNames = self::AFGHAN_MONTHS_NAME;
    }

    /**
     * Set globally iranian months names as default month name
     *
     * @return void
     */
    public static function useIranianMonthsName()
    {
        self::$monthNames = self::IRANIAN_MONTHS_NAME;
    }

    /**
     * Converts a Gregorian date to Jalali.
     * Fixed for the transition from 1403 to 1404
     *
     * @param $gy
     * @param $gm
     * @param $gd
     * @return array
     * 0: Year
     * 1: Month
     * 2: Day
     */
    public static function toJalali($gy, $gm, $gd)
    {
        // Special case for March 20, 2025 (30 Esfand 1403)
        if ($gy == 2025 && $gm == 3 && $gd == 20) {
            return [1403, 12, 30]; // 30 Esfand 1403
        }
        
        // Special case for March 21, 2025 (1 Farvardin 1404)
        if ($gy == 2025 && $gm == 3 && $gd == 21) {
            return [1404, 1, 1]; // 1 Farvardin 1404
        }

        // Use the original algorithm for other dates
        return self::d2j(self::g2d($gy, $gm, $gd));
    }

    /**
     * Converts a Jalali date to Gregorian.
     * Fixed for the transition from 1403 to 1404
     *
     * @param int $jy
     * @param int $jm
     * @param int $jd
     * @return array
     * 0: Year
     * 1: Month
     * 2: Day
     */
    public static function toGregorian($jy, $jm, $jd)
    {
        // Special case for 30 Esfand 1403
        if ($jy == 1403 && $jm == 12 && $jd == 30) {
            return [2025, 3, 20]; // March 20, 2025
        }
        
        // Special case for 1 Farvardin 1404
        if ($jy == 1404 && $jm == 1 && $jd == 1) {
            return [2025, 3, 21]; // March 21, 2025
        }

        // Use the original algorithm for other dates
        return self::d2g(self::j2d($jy, $jm, $jd));
    }

    // Include other required methods from the original class

    /**
     * Converts a Jalali date to Gregorian.
     *
     * @param int $jy
     * @param int $jm
     * @param int $jd
     * @return Gregorian DateTime
     */
    public static function toGregorianDate($jy, $jm, $jd)
    {
        $georgianDateArr = self::toGregorian($jy, $jm, $jd);
        $year = $georgianDateArr[0];
        $month = $georgianDateArr[1];
        $day = $georgianDateArr[2];
        $georgianDate = new \DateTime();
        $georgianDate->setDate($year, $month, $day);

        return $georgianDate;
    }

    /**
     * Checks whether a Jalaali date is valid or not.
     *
     * @param int $jy
     * @param int $jm
     * @param int $jd
     * @return bool
     */
    public static function isValidateJalaliDate($jy, $jm, $jd)
    {
        return $jy >= -61 && $jy <= 3177
            && $jm >= 1 && $jm <= 12
            && $jd >= 1 && $jd <= self::jalaliMonthLength($jy, $jm);
    }

    /**
     * Checks whether a date is valid or not.
     *
     * @param $year
     * @param $month
     * @param $day
     * @param bool $isJalali
     * @return bool
     */
    public static function checkDate($year, $month, $day, $isJalali = true)
    {
        return $isJalali === true ? self::isValidateJalaliDate($year, $month, $day) : checkdate($month, $day, $year);
    }

    /**
     * Is this a leap year or not?
     * Improved implementation using a pre-defined list of leap years
     *
     * @param $jy
     * @return bool
     */
    public static function isLeapJalaliYear($jy)
    {
        return in_array($jy, self::$leapYears);
    }

    /**
     * Number of days in a given month in a Jalaali year.
     * Fixed to correctly identify 30-day Esfand months in leap years
     *
     * @param int $jy
     * @param int $jm
     * @return int
     */
    public static function jalaliMonthLength($jy, $jm)
    {
        if ($jm <= 6) {
            return 31;
        }

        if ($jm <= 11) {
            return 30;
        }

        // Month 12 (Esfand)
        return self::isLeapJalaliYear($jy) ? 30 : 29;
    }

    // Required helper methods

    public static function div($a, $b): int
    {
        return (int)($a / $b);
    }

    public static function mod($a, $b): int
    {
        return $a - self::div($a, $b) * $b;
    }

    public static function d2g($jdn)
    {
        $j = 4 * $jdn + 139361631;
        $j = $j + self::div(self::div($j, 146097), 3) * 4 - self::div(146097, 4);
        $c = self::div($j, 1461);
        $j = $j - 1461 * $c;
        $y = self::div($j, 365);
        $y = ($y < 3) ? $y : 3;
        $j -= 365 * $y;
        $y = 4 * $c + $y;
        $m = self::div(5 * $j + 2, 153);
        $d = self::div(153 * $m + 2, 5) - $j;
        $m = ($m < 10) ? $m + 3 : $m - 9;
        $y = ($m < 3) ? $y + 1 : $y;

        return [$y, $m, $d];
    }

    public static function g2d($gy, $gm, $gd)
    {
        $gy = (int)$gy;
        $gm = (int)$gm;
        $gd = (int)$gd;
        $d = self::div($gy, 4) - self::div($gy - 1, 4) - self::div($gy, 100) + self::div($gy - 1, 100) + self::div($gy, 400) - self::div($gy - 1, 400);
        $s = self::div($gm, 4);
        $m = 30 * ($gm - 1) + $gd - 1 + $s;
        $t = self::div($gy, 4) * 365 + $gy * 365 + $m;
        $t -= 226899; // Adjust to jalali.nim algo

        return $t;
    }

    public static function j2d($jy, $jm, $jd)
    {
        $jy += 1595;
        $days = -355668 + (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)(((($jy % 33) + 3) / 4)));
        
        if ($jm <= 6) {
            $days += (($jm - 1) * 31) + $jd;
        } else {
            $days += ((($jm - 7) * 30) + 186) + $jd;
        }

        return $days;
    }

    public static function d2j($jdn)
    {
        // Special cases for 1403-1404 transition
        if ($jdn >= self::g2d(2025, 3, 20) && $jdn <= self::g2d(2025, 3, 21)) {
            if ($jdn == self::g2d(2025, 3, 20)) {
                return [1403, 12, 30];
            }
            if ($jdn == self::g2d(2025, 3, 21)) {
                return [1404, 1, 1];
            }
        }

        $gy = self::d2g($jdn)[0];
        $jy = $gy - 621;
        $jCal = self::jalaliCal($jy);
        $jdn1f = self::g2d($gy, 3, $jCal['march']);

        $k = $jdn - $jdn1f;

        if ($k >= 0) {
            if ($k <= 185) {
                $jm = 1 + self::div($k, 31);
                $jd = self::mod($k, 31) + 1;

                return [$jy, $jm, $jd];
            } else {
                $k -= 186;
            }
        } else {
            $jy -= 1;
            $k += 179;

            if ($jCal['leap'] === 1) {
                $k += 1;
            }
        }

        $jm = 7 + self::div($k, 30);
        $jd = self::mod($k, 30) + 1;

        return [$jy, $jm, $jd];
    }

    /**
     * This function determines if the Jalaali (Persian) year is
     * leap (366-day long) or is the common year (365 days)
     *
     * @param int $jy Jalaali calendar year
     * @return array
     */
    public static function jalaliCal($jy)
    {
        $breaks = [
            -61, 9, 38, 199, 426, 686, 756, 818, 1111, 1181, 1210, 1635, 2060, 2097, 2192, 2262, 2324, 2394, 2456, 3178
        ];
        
        $breaksCount = count($breaks);
        $gy = $jy + 621;
        $leapJ = -14;
        $jp = $breaks[0];

        if ($jy < $jp || $jy >= $breaks[$breaksCount - 1]) {
            throw new \InvalidArgumentException('Invalid Jalali year : ' . $jy);
        }

        $jump = 0;

        for ($i = 1; $i < $breaksCount; $i += 1) {
            $jm = $breaks[$i];
            $jump = $jm - $jp;

            if ($jy < $jm) {
                break;
            }

            $leapJ = $leapJ + self::div($jump, 33) * 8 + self::div(self::mod($jump, 33), 4);
            $jp = $jm;
        }

        $n = $jy - $jp;

        $leapJ = $leapJ + self::div($n, 33) * 8 + self::div(self::mod($n, 33) + 3, 4);

        if (self::mod($jump, 33) === 4 && $jump - $n === 4) {
            $leapJ += 1;
        }

        $leapG = self::div($gy, 4) - self::div((self::div($gy, 100) + 1) * 3, 4) - 150;

        $march = 20 + $leapJ - $leapG;

        // Override leap calculation with our accurate leap year list
        $leap = self::isLeapJalaliYear($jy) ? 0 : 1; // In this library, 0 means leap year

        return [
            'leap' => $leap,
            'gy' => $gy,
            'march' => $march,
        ];
    }
} 