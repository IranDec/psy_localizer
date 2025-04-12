<?php
/**
 * Author: Mohammad Babaei
 * Email: info@adschi.com
 * Website: https://adschi.com
 */
namespace PrestaYar\Localizer;

use Hekmatinasser\Verta\Verta;

/**
 * JalaliHelper class to provide helper functions for Jalali dates
 */
class JalaliHelper
{
    /**
     * Convert a standard DateTime string to Jalali (Shamsi) date
     *
     * @param string $date
     * @param string $format
     * @param bool $convertNumbers
     * @return string
     */
    public static function dateToJalali(string $date, string $format = 'Y/m/d', bool $convertNumbers = false): string
    {
        if (empty($date)) {
            return '';
        }
        
        try {
            $verta = new Verta($date);
            $result = $verta->format($format);
            
            if ($convertNumbers) {
                $result = self::convertNumbersToPersian($result);
            }
            
            return $result;
        } catch (\Exception $e) {
            return $date;
        }
    }
    
    /**
     * Convert timestamp to Jalali (Shamsi) date
     *
     * @param int $timestamp
     * @param string $format
     * @param bool $convertNumbers
     * @return string
     */
    public static function timestampToJalali(int $timestamp, string $format = 'Y/m/d', bool $convertNumbers = false): string
    {
        if (empty($timestamp)) {
            return '';
        }
        
        try {
            $verta = new Verta($timestamp);
            $result = $verta->format($format);
            
            if ($convertNumbers) {
                $result = self::convertNumbersToPersian($result);
            }
            
            return $result;
        } catch (\Exception $e) {
            return (string)$timestamp;
        }
    }
    
    /**
     * Convert numbers to Persian
     *
     * @param string $string
     * @return string
     */
    public static function convertNumbersToPersian(string $string): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $latin = range(0, 9);
        
        $output = str_replace($latin, $persian, $string);
        $output = str_replace($arabic, $persian, $output);
        
        return $output;
    }
    
    /**
     * Convert numbers to Latin
     *
     * @param string $string
     * @return string
     */
    public static function convertNumbersToLatin(string $string): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $latin = range(0, 9);
        
        $output = str_replace($persian, $latin, $string);
        $output = str_replace($arabic, $latin, $output);
        
        return $output;
    }
    
    /**
     * Check if a year is leap in Jalali calendar
     *
     * @param int $year
     * @return bool
     */
    public static function isLeapYear(int $year): bool
    {
        $verta = new Verta();
        $verta->setJalaliYear($year);
        return $verta->isLeapYear();
    }
    
    /**
     * Get number of days in a specific month of Jalali calendar
     *
     * @param int $year
     * @param int $month
     * @return int
     */
    public static function daysInMonth(int $year, int $month): int
    {
        $verta = new Verta();
        $verta->setJalaliYear($year);
        $verta->setJalaliMonth($month);
        return $verta->daysInMonth;
    }
    
    /**
     * Format current date and time in Jalali calendar
     *
     * @param string $format
     * @param bool $convertNumbers
     * @return string
     */
    public static function now(string $format = 'Y/m/d H:i:s', bool $convertNumbers = false): string
    {
        $verta = new Verta();
        $result = $verta->format($format);
        
        if ($convertNumbers) {
            $result = self::convertNumbersToPersian($result);
        }
        
        return $result;
    }
    
    /**
     * Get an array of Jalali months names
     *
     * @return array
     */
    public static function getMonthNames(): array
    {
        return [
            1 => 'فروردین',
            2 => 'اردیبهشت',
            3 => 'خرداد',
            4 => 'تیر',
            5 => 'مرداد',
            6 => 'شهریور',
            7 => 'مهر',
            8 => 'آبان',
            9 => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند',
        ];
    }
    
    /**
     * Get an array of Jalali day names
     *
     * @return array
     */
    public static function getDayNames(): array
    {
        return [
            0 => 'شنبه',
            1 => 'یکشنبه',
            2 => 'دوشنبه',
            3 => 'سه‌شنبه',
            4 => 'چهارشنبه',
            5 => 'پنج‌شنبه',
            6 => 'جمعه',
        ];
    }
} 