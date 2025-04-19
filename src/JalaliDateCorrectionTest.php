<?php
/**
 * Jalali Date Correction Test
 * 
 * Test file to verify the fixes for the Jalali calendar transition from 1403 to 1404
 * 
 * @author Mohammad Babaei
 * @website https://adschi.com
 */

namespace PrestaYar\Localizer;

class JalaliDateCorrectionTest
{
    /**
     * Run tests to verify the calendar fix
     * 
     * @return array Test results
     */
    public static function runTests()
    {
        $results = [];
        
        // Test case 1: March 20, 2025 should be 30 Esfand 1403
        $date1 = "2025-03-20";
        $jalali1 = JalaliDateCorrection::correctDate($date1);
        $formatted1 = JalaliDateCorrection::format($date1, 'Y/m/d');
        $results[] = [
            'test' => "March 20, 2025 should be 30 Esfand 1403",
            'gregorian' => $date1,
            'expected' => [1403, 12, 30],
            'actual' => $jalali1,
            'formatted' => $formatted1,
            'passed' => ($jalali1[0] == 1403 && $jalali1[1] == 12 && $jalali1[2] == 30)
        ];
        
        // Test case 2: March 21, 2025 should be 1 Farvardin 1404
        $date2 = "2025-03-21";
        $jalali2 = JalaliDateCorrection::correctDate($date2);
        $formatted2 = JalaliDateCorrection::format($date2, 'Y/m/d');
        $results[] = [
            'test' => "March 21, 2025 should be 1 Farvardin 1404",
            'gregorian' => $date2,
            'expected' => [1404, 1, 1],
            'actual' => $jalali2,
            'formatted' => $formatted2,
            'passed' => ($jalali2[0] == 1404 && $jalali2[1] == 1 && $jalali2[2] == 1)
        ];
        
        // Test case 3: March 19, 2025 should be 29 Esfand 1403
        $date3 = "2025-03-19";
        $jalali3 = JalaliDateCorrection::correctDate($date3);
        $formatted3 = JalaliDateCorrection::format($date3, 'Y/m/d');
        $results[] = [
            'test' => "March 19, 2025 should be 29 Esfand 1403",
            'gregorian' => $date3,
            'expected' => [1403, 12, 29],
            'actual' => $jalali3,
            'formatted' => $formatted3,
            'passed' => ($jalali3[0] == 1403 && $jalali3[1] == 12 && $jalali3[2] == 29)
        ];
        
        // Test case 4: March 22, 2025 should be 2 Farvardin 1404
        $date4 = "2025-03-22";
        $jalali4 = JalaliDateCorrection::correctDate($date4);
        $formatted4 = JalaliDateCorrection::format($date4, 'Y/m/d');
        $results[] = [
            'test' => "March 22, 2025 should be 2 Farvardin 1404",
            'gregorian' => $date4,
            'expected' => [1404, 1, 2],
            'actual' => $jalali4,
            'formatted' => $formatted4,
            'passed' => ($jalali4[0] == 1404 && $jalali4[1] == 1 && $jalali4[2] == 2)
        ];
        
        return $results;
    }
    
    /**
     * Output test results in a readable format
     */
    public static function displayResults()
    {
        $results = self::runTests();
        $output = "=== Jalali Calendar Correction Tests ===\n";
        $allPassed = true;
        
        foreach ($results as $result) {
            $status = $result['passed'] ? 'PASSED' : 'FAILED';
            $output .= "Test: {$result['test']} - {$status}\n";
            $output .= "  Gregorian: {$result['gregorian']}\n";
            $output .= "  Expected Jalali: " . implode('/', $result['expected']) . "\n";
            $output .= "  Actual Jalali: " . implode('/', $result['actual']) . "\n";
            $output .= "  Formatted: {$result['formatted']}\n\n";
            
            if (!$result['passed']) {
                $allPassed = false;
            }
        }
        
        $output .= "Overall Test Result: " . ($allPassed ? "PASSED" : "FAILED") . "\n";
        return $output;
    }
} 