/**
 * Author: Mohammad Babaei
 * Email: info@adschi.com
 * Website: https://adschi.com
 * 
 * Jalali Helper JavaScript functions for PrestaShop localization
 */

var JalaliHelper = (function() {
    // Persian month names
    var monthNames = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
    
    // Persian day names
    var dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
    
    // Persian digits
    var persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /**
     * Convert Gregorian date to Jalali (Shamsi) date
     * @param {Date|string} date - JavaScript Date object or date string
     * @param {string} format - Output format (default: 'Y/m/d')
     * @param {boolean} convertToPersian - Convert digits to Persian (default: false)
     * @return {string} Formatted Jalali date
     */
    function toJalali(date, format, convertToPersian) {
        if (!date) {
            return '';
        }
        
        format = format || 'Y/m/d';
        convertToPersian = convertToPersian || false;
        
        var d = new Date(date);
        if (isNaN(d.getTime())) {
            return '';
        }
        
        var gregorianDate = {
            year: d.getFullYear(),
            month: d.getMonth() + 1,
            day: d.getDate(),
            hours: d.getHours(),
            minutes: d.getMinutes(),
            seconds: d.getSeconds()
        };
        
        var jalaliDate = gregorianToJalali(gregorianDate.year, gregorianDate.month, gregorianDate.day);
        
        var result = format;
        result = result.replace('Y', jalaliDate.year);
        result = result.replace('m', jalaliDate.month < 10 ? '0' + jalaliDate.month : jalaliDate.month);
        result = result.replace('n', jalaliDate.month);
        result = result.replace('d', jalaliDate.day < 10 ? '0' + jalaliDate.day : jalaliDate.day);
        result = result.replace('j', jalaliDate.day);
        result = result.replace('H', gregorianDate.hours < 10 ? '0' + gregorianDate.hours : gregorianDate.hours);
        result = result.replace('i', gregorianDate.minutes < 10 ? '0' + gregorianDate.minutes : gregorianDate.minutes);
        result = result.replace('s', gregorianDate.seconds < 10 ? '0' + gregorianDate.seconds : gregorianDate.seconds);
        result = result.replace('F', monthNames[jalaliDate.month - 1]);
        
        if (convertToPersian) {
            result = convertToPersianDigits(result);
        }
        
        return result;
    }
    
    /**
     * Convert a Jalali (Shamsi) date to Gregorian date
     * @param {number} jYear - Jalali year
     * @param {number} jMonth - Jalali month (1-12)
     * @param {number} jDay - Jalali day
     * @return {Date} JavaScript Date object
     */
    function toGregorian(jYear, jMonth, jDay) {
        var gregorian = jalaliToGregorian(jYear, jMonth, jDay);
        return new Date(gregorian.year, gregorian.month - 1, gregorian.day);
    }

    /**
     * Convert Gregorian date to Jalali date
     * @param {number} gYear - Gregorian year
     * @param {number} gMonth - Gregorian month (1-12)
     * @param {number} gDay - Gregorian day
     * @return {object} Jalali date object with year, month, and day properties
     */
    function gregorianToJalali(gYear, gMonth, gDay) {
        var g_days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        var j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

        var gy = gYear - 1600;
        var gm = gMonth - 1;
        var gd = gDay - 1;

        var g_day_no = 365 * gy + Math.floor((gy + 3) / 4) - Math.floor((gy + 99) / 100) + Math.floor((gy + 399) / 400);

        for (var i = 0; i < gm; ++i) {
            g_day_no += g_days_in_month[i];
        }

        if (gm > 1 && ((gy % 4 == 0 && gy % 100 != 0) || (gy % 400 == 0))) {
            g_day_no += 1;
        }

        g_day_no += gd;

        var j_day_no = g_day_no - 79;
        var j_np = Math.floor(j_day_no / 12053);
        j_day_no %= 12053;

        var jy = 979 + 33 * j_np + 4 * Math.floor(j_day_no / 1461);
        j_day_no %= 1461;

        if (j_day_no >= 366) {
            jy += Math.floor((j_day_no - 1) / 365);
            j_day_no = (j_day_no - 1) % 365;
        }

        var jm = 0;
        for (i = 0; i < 11 && j_day_no >= j_days_in_month[i]; ++i) {
            j_day_no -= j_days_in_month[i];
            jm++;
        }

        var jd = j_day_no + 1;
        
        return {
            year: jy,
            month: jm + 1,
            day: jd
        };
    }

    /**
     * Convert Jalali date to Gregorian date
     * @param {number} jYear - Jalali year
     * @param {number} jMonth - Jalali month (1-12)
     * @param {number} jDay - Jalali day
     * @return {object} Gregorian date object with year, month, and day properties
     */
    function jalaliToGregorian(jYear, jMonth, jDay) {
        var g_days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        var j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

        var jy = jYear - 979;
        var jm = jMonth - 1;
        var jd = jDay - 1;

        var j_day_no = 365 * jy + Math.floor(jy / 33) * 8 + Math.floor(((jy % 33) + 3) / 4);
        for (var i = 0; i < jm; ++i) {
            j_day_no += j_days_in_month[i];
        }

        j_day_no += jd;
        var g_day_no = j_day_no + 79;
        var gy = 1600 + 400 * Math.floor(g_day_no / 146097);
        g_day_no = g_day_no % 146097;

        var leap = true;
        if (g_day_no >= 36525) {
            g_day_no--;
            gy += 100 * Math.floor(g_day_no / 36524);
            g_day_no = g_day_no % 36524;

            if (g_day_no >= 365) {
                g_day_no++;
            } else {
                leap = false;
            }
        }

        gy += 4 * Math.floor(g_day_no / 1461);
        g_day_no %= 1461;

        if (g_day_no >= 366) {
            leap = false;
            g_day_no--;
            gy += Math.floor(g_day_no / 365);
            g_day_no = g_day_no % 365;
        }

        var gm = 0;
        for (i = 0; g_day_no >= g_days_in_month[i] + (i == 1 && leap ? 1 : 0); i++) {
            g_day_no -= g_days_in_month[i] + (i == 1 && leap ? 1 : 0);
            gm++;
        }

        var gd = g_day_no + 1;
        
        return {
            year: gy,
            month: gm + 1,
            day: gd
        };
    }

    /**
     * Convert digits to Persian digits
     * @param {string|number} input - Input string or number
     * @return {string} String with Persian digits
     */
    function convertToPersianDigits(input) {
        if (!input) return '';
        
        var str = input.toString();
        var result = '';
        
        for (var i = 0; i < str.length; i++) {
            var char = str.charAt(i);
            if (char >= '0' && char <= '9') {
                result += persianDigits[parseInt(char)];
            } else {
                result += char;
            }
        }
        
        return result;
    }

    /**
     * Convert Persian digits to Latin digits
     * @param {string} input - Input string with Persian digits
     * @return {string} String with Latin digits
     */
    function convertToLatinDigits(input) {
        if (!input) return '';
        
        var str = input.toString();
        
        for (var i = 0; i < 10; i++) {
            str = str.replace(new RegExp(persianDigits[i], 'g'), i);
        }
        
        return str;
    }

    /**
     * Check if a Jalali year is a leap year
     * @param {number} jYear - Jalali year
     * @return {boolean} True if the year is a leap year
     */
    function isLeapYear(jYear) {
        var remainder = jYear % 33;
        return (remainder == 1 || remainder == 5 || remainder == 9 || remainder == 13 || 
                remainder == 17 || remainder == 22 || remainder == 26 || remainder == 30);
    }

    /**
     * Get number of days in a Jalali month
     * @param {number} jYear - Jalali year
     * @param {number} jMonth - Jalali month (1-12)
     * @return {number} Number of days in the month
     */
    function daysInMonth(jYear, jMonth) {
        if (jMonth <= 6) {
            return 31;
        } else if (jMonth <= 11) {
            return 30;
        } else {
            return isLeapYear(jYear) ? 30 : 29;
        }
    }

    /**
     * Format current date in Jalali calendar
     * @param {string} format - Output format (default: 'Y/m/d')
     * @param {boolean} convertToPersian - Convert digits to Persian (default: false)
     * @return {string} Formatted Jalali date
     */
    function now(format, convertToPersian) {
        return toJalali(new Date(), format, convertToPersian);
    }

    /**
     * Get day of week for a Jalali date (0: Saturday, 6: Friday)
     * @param {number} jYear - Jalali year
     * @param {number} jMonth - Jalali month (1-12)
     * @param {number} jDay - Jalali day
     * @return {number} Day of week (0-6)
     */
    function dayOfWeek(jYear, jMonth, jDay) {
        var gregorian = jalaliToGregorian(jYear, jMonth, jDay);
        var date = new Date(gregorian.year, gregorian.month - 1, gregorian.day);
        var day = date.getDay(); // 0: Sunday, 6: Saturday
        return (day + 1) % 7; // Convert to 0: Saturday, 6: Friday
    }

    /**
     * Get Persian month name by month number
     * @param {number} month - Month number (1-12)
     * @return {string} Persian month name
     */
    function getMonthName(month) {
        if (month < 1 || month > 12) {
            return '';
        }
        return monthNames[month - 1];
    }
    
    /**
     * Get Persian day name by day number
     * @param {number} day - Day number (0-6, 0: Saturday, 6: Friday)
     * @return {string} Persian day name
     */
    function getDayName(day) {
        if (day < 0 || day > 6) {
            return '';
        }
        return dayNames[day];
    }

    // Public API
    return {
        toJalali: toJalali,
        toGregorian: toGregorian,
        convertToPersianDigits: convertToPersianDigits,
        convertToLatinDigits: convertToLatinDigits,
        isLeapYear: isLeapYear,
        daysInMonth: daysInMonth,
        now: now,
        dayOfWeek: dayOfWeek,
        getMonthName: getMonthName,
        getDayName: getDayName
    };
})(); 