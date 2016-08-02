<?php

class Layout
{
    /**
     * Returns human size
     *
     * @param  float $filesize   File size
     * @param  int   $precision  Number of decimals
     * @return string            Human size
     */
    public static function getSize($filesize, $precision = 2)
    {
        $units = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
        foreach ($units as $idUnit => $unit)
        {
            if ($filesize > 1024)
                $filesize /= 1024;
            else
                break;
        }
        
        return round($filesize, $precision).' '.$units[$idUnit].'B';
    }


    
}