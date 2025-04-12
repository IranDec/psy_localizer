# Jalali Calendar Fix

This override fixes issues with the Morilog/Jalali library regarding:
1. The transition from the end of Esfand 1403 to the beginning of Farvardin 1404
2. The number of days in Esfand for all leap years

## The Issues

1. The original library incorrectly handled the transition at the end of year 1403, treating March 20, 2025 (Thursday) as 1 Farvardin 1404 instead of 30 Esfand 1403.
2. The library sometimes incorrectly calculated whether a year is a leap year, resulting in Esfand having 29 days when it should have had 30 days.

## The Fix

### Transition between 1403 and 1404
- March 20, 2025: Now correctly returns 30 Esfand 1403
- March 21, 2025: Correctly returns 1 Farvardin 1404

### Accurate Leap Year Calculation
- Added a predefined list of Jalali leap years (from 1337 to 1436)
- Modified `isLeapJalaliYear()` to use this accurate list
- Updated `jalaliMonthLength()` to correctly return 30 days for Esfand in leap years

## Implementation Details
1. Patched the `CalendarUtils` class to properly handle specific dates and leap years
2. Updated the `Jalalian` class to validate dates using the correct month lengths
3. Improved validation to ensure that dates like 30 Esfand are only allowed in leap years

## Usage

The fix works transparently with no changes required to your code. The classes override the original Morilog/Jalali classes while maintaining the same API.

## Author
Mohammad Babaei
Website: https://adschi.com 