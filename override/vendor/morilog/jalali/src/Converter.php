<?php
/**
 * Author: Mohammad Babaei
 * Email: info@adschi.com
 * Website: https://adschi.com
 */
namespace Morilog\Jalali;

/**
 * A minimal implementation of the Converter trait used by the Jalalian class
 */
trait Converter
{
    /**
     * Get Jalali date string
     *
     * @return string
     */
    public function getJalali(): string
    {
        return $this->format('Y/m/d');
    }
} 