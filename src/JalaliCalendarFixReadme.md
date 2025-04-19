# Jalali Calendar Fix for 1403-1404 Transition

## Overview
This fix addresses an issue in the Jalali (Persian) calendar implementation where Thursday, March 20, 2025, was incorrectly showing as "1 Farvardin 1404" instead of "30 Esfand 1403". The correct mapping should be:

- Thursday, March 20, 2025 → 30 Esfand 1403
- Friday, March 21, 2025 → 1 Farvardin 1404

## Implementation
The fix creates a custom class `JalaliDateCorrection` that overrides the default behavior for these specific dates. This approach maintains compatibility with the original library while ensuring correct date conversion for the 1403-1404 transition.

## Files
- `src/JalaliDateCorrection.php`: Main implementation that handles the correction
- `src/JalaliDateCorrectionTest.php`: Test file to verify the fix works correctly
- `vendor/morilog/jalali/src/JalaliCalendarFix.php`: Alternative approach that directly patches the original library

## Usage
The fix is automatically integrated into the module's date conversion functions. No additional configuration is required.

To manually convert a date:
```php
use PrestaYar\Localizer\JalaliDateCorrection;

// Format a date with the correction applied
$jalaliDate = JalaliDateCorrection::format('2025-03-20', 'Y/m/d');
// Returns: "1403/12/30"

$jalaliDate = JalaliDateCorrection::format('2025-03-21', 'Y/m/d');
// Returns: "1404/01/01"
```

## Testing
You can run the tests to verify the fix works as expected:
```php
$results = \PrestaYar\Localizer\JalaliDateCorrectionTest::displayResults();
echo $results;
```

## Credits
This fix was developed by:
- Mohammad Babaei
- Website: [https://adschi.com](https://adschi.com) 