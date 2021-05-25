<?php


namespace App\Services;


class Ipv4netmaskService
{
    /**
     * @param string $binaryNetmask netmask binary format
     * @return string wildcard binary format
     */
    public static function convertNetmaskToWildcard(string $binaryNetmask): string
    {
        $out_local = "";
        $netMaskBin_local = $binaryNetmask;
        for ($i = 0; $i < strlen($netMaskBin_local); $i++) {
            ($netMaskBin_local[$i] == '0') ? $out_local .= '1' : $out_local .= '0';
        }
        return $out_local;
    }
}