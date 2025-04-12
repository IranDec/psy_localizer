# Jalali Calendar Fix

This override fixes an issue with the Morilog/Jalali library regarding the transition from the end of Esfand 1403 to the beginning of Farvardin 1404.

## The Issue

The original library incorrectly handled the transition at the end of year 1403, treating March 20, 2025 (Thursday) as 1 Farvardin 1404 instead of 30 Esfand 1403.

## The Fix

This override implements special handling for:
- March 20, 2025: Now correctly returns 30 Esfand 1403
- March 21, 2025: Correctly returns 1 Farvardin 1404

The implementation:
1. Patches the `CalendarUtils` class to properly handle these specific dates
2. Updates the `Jalalian` class to support validation of 30 Esfand 1403
3. Provides specific overrides for key methods to ensure correct date conversion

## Usage

The fix works transparently with no changes required to your code. The classes override the original Morilog/Jalali classes while maintaining the same API. 