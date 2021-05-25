<?php


namespace App\Services;


use App\Entity\IP\v6\Ipv6address;

class Ipv6addressService
{
    /**
     * Convert IPv6 address from Hexa to Binary format. Only for internal purpose
     * @return string binary number of IPv6 address
     */
    public static function hexToBin(array $ipv6Array): string
    {
        $localBin = "";
        foreach ($ipv6Array as $hextet) {
            $localBin .= str_pad(base_convert($hextet, 16, 2), Ipv6address::HEXTET_BIN_LEN, "0", STR_PAD_LEFT);
        }
        return $localBin;
    }

    /**
     * Convert IPv6 address to array splitted by colon
     * @param string $ipv6InHexaFormat Hexa format of IPv6 eg. abc::
     * @return array array of
     */
    public static function hexToArray(string $ipv6InHexaFormat): array
    {
        // split ipv6 address to array
        $ipv6Array = explode(':', $ipv6InHexaFormat);

        // is the IPv6 in shortened representation
        if (str_contains($ipv6InHexaFormat, "::")) {
            // then add missing zeros

            $ipv6ArrayAddMissingZeros = array();
            $countOfMissingHextet = Ipv6address::HEXTET_COUNT - count($ipv6Array);

            foreach ($ipv6Array as $hextet) {
                if (strlen($hextet) == 0) {
                    for ($i = 0; $i <= $countOfMissingHextet; $i++)
                        $ipv6ArrayAddMissingZeros[] = "0000";
                    // already added missing zeros in to IPv6 adderr, set variable to zero
                    $countOfMissingHextet = 0;
                } else {
                    $ipv6ArrayAddMissingZeros[] = $hextet;
                }
            }
            // set the original array with added zeros
            return $ipv6ArrayAddMissingZeros;
        } else {
            return $ipv6Array;
        }
    }
}