<?php


namespace App\Factories;

use App\Entity\IP\v6\Ipv6address;
use InvalidArgumentException;

class IPv6AddressFactory
{
    /**
     * Convert IP from BIN to HEXA
     * @param string $inputIP IPv6 address in binary format
     * @return Ipv6address New object of Ipv4address
     */
    public static function binaryToHexa(string $inputIP): Ipv6address
    {
        // input must be only in binary format and length must be 128 bits
        if (filter_var($inputIP, FILTER_VALIDATE_REGEXP,
                array(
                    "options" => array(
                        "regexp" => '/[0,1]/'
                    )))
            and
            filter_var(strlen($inputIP), FILTER_VALIDATE_INT, array(
                'options' => array(
                    'min_range' => Ipv6address::IPV6_BIN_LEN,
                    'max_range' => Ipv6address::IPV6_BIN_LEN
                )))) {

            $inputIpArray = str_split($inputIP, Ipv6address::HEXTET_BIN_LEN);
            $ipOutput = "";
            foreach ($inputIpArray as $hextet) {
                $ipOutput .= base_convert($hextet, 2, 16) . ":";
            }
            return new Ipv6address(substr($ipOutput, 0, -1));
        } else {
            throw new InvalidArgumentException("Input is not in valid format");
        }
    }
}