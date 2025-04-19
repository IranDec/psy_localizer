<?php
/**
 * Jalali Date Correction
 * 
 * Fixes the issue with Jalali calendar transition from 1403 to 1404
 * where Thursday (March 20, 2025) should be 30 Esfand 1403 and
 * Friday (March 21, 2025) should be 1 Farvardin 1404.
 * 
 * @author Mohammad Babaei
 * @website https://adschi.com
 */

namespace PrestaYar\Localizer;

use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class JalaliDateCorrection
{
    /**
     * Apply the correction to dates around March 20-21, 2025
     * 
     * @param string $gregorianDate The Gregorian date in Y-m-d format
     * @return array Corrected Jalali date [year, month, day]
     */
    public static function correctDate($gregorianDate)
    {
        if (empty($gregorianDate)) {
            return null;
        }
        
        // Parse the date
        $dateParts = explode('-', $gregorianDate);
        if (count($dateParts) !== 3) {
            // Try another format
            $date = new \DateTime($gregorianDate);
            $year = (int)$date->format('Y');
            $month = (int)$date->format('m');
            $day = (int)$date->format('d');
        } else {
            $year = (int)$dateParts[0];
            $month = (int)$dateParts[1];
            $day = (int)$dateParts[2];
        }
        
        // Special case for the problematic dates
        if ($year == 2025 && $month == 3 && $day == 20) {
            // This should be 30 Esfand 1403
            return [1403, 12, 30];
        }
        
        if ($year == 2025 && $month == 3 && $day == 21) {
            // This should be 1 Farvardin 1404
            return [1404, 1, 1];
        }
        
        // For all other dates, use the standard conversion
        return CalendarUtils::toJalali($year, $month, $day);
    }
    
    /**
     * Format a Jalali date correctly
     * 
     * @param string $gregorianDate The Gregorian date
     * @param string $format The desired format for the Jalali date
     * @return string Formatted Jalali date
     */
    public static function format($gregorianDate, $format = 'Y/m/d')
    {
        $jalaliDate = self::correctDate($gregorianDate);
        
        if (!$jalaliDate) {
            return '';
        }
        
        // Special handling for 30 Esfand 1403 and 1 Farvardin 1404
        if (($jalaliDate[0] == 1403 && $jalaliDate[1] == 12 && $jalaliDate[2] == 30) ||
            ($jalaliDate[0] == 1404 && $jalaliDate[1] == 1 && $jalaliDate[2] == 1)) {
            
            // Create a custom formatted date
            $formatted = str_replace(['Y', 'y', 'm', 'n', 'd', 'j'], [
                $jalaliDate[0],                // Y - Full year
                $jalaliDate[0] % 100,          // y - Two-digit year
                str_pad($jalaliDate[1], 2, '0', STR_PAD_LEFT),  // m - Month with leading zeros
                $jalaliDate[1],                // n - Month without leading zeros
                str_pad($jalaliDate[2], 2, '0', STR_PAD_LEFT),  // d - Day with leading zeros
                $jalaliDate[2]                 // j - Day without leading zeros
            ], $format);
            
            return $formatted;
        }
        
        // For other dates, use the standard Jalalian formatter
        $jDate = new Jalalian($jalaliDate[0], $jalaliDate[1], $jalaliDate[2]);
        return $jDate->format($format);
    }
    
    /**
     * Check if a Gregorian date falls on the problematic transition period
     */
    public static function isProblematicDate($gregorianDate)
    {
        if (empty($gregorianDate)) {
            return false;
        }
        
        // Parse the date
        $date = new \DateTime($gregorianDate);
        $year = (int)$date->format('Y');
        $month = (int)$date->format('m');
        $day = (int)$date->format('d');
        
        // Check if it's March 20-21, 2025
        return ($year == 2025 && $month == 3 && ($day == 20 || $day == 21));
    }
} 