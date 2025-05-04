<?php


namespace App\Services;


use App\Domain\Ip\v6\Ipv6address;

class Ipv6netmaskService extends Ipv6addressService
{
    /**
     * Convert binary netmask to hexa format
     */
    public static function binToHexa(string $ipv6InBinaryFormat): string
    {
        $localString = "";
        $localArrayBinary = str_split($ipv6InBinaryFormat, Ipv6address::HEXTET_BIN_LEN);

        foreach ($localArrayBinary as $item) {
            $localString .= base_convert($item, 2, 16) . ":";
        }
        // remove last colon
        return rtrim($localString, ":");
    }

    /**
     * Set binary format of netmask
     * @param int $cidr
     * @return string Ipv6 netmask in binary format
     */
    public static function cidrToBinary(int $cidr): string
    {
        $binaryNetmask = "";
        for ($i = 0; $i < Ipv6address::IPV6_BIN_LEN; $i++) {
            ($cidr > $i) ? $binaryNetmask .= "1" : $binaryNetmask .= "0";
        }
        return $binaryNetmask;
    }

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