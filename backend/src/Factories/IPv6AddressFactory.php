<?php


namespace App\Factories;

use App\Domain\Ip\v6\Ipv6address;
use InvalidArgumentException;

class IPv6AddressFactory
{
    /**
     * Convert IP from BIN to HEXA
     * @param string $binaryV6Address IPv6 address in binary format
     * @return Ipv6address New object of Ipv4address
     */
    public static function binaryToHexa(string $binaryV6Address): Ipv6address
    {
        // input must be only in binary format and length must be 128 bits
        if (filter_var($binaryV6Address, FILTER_VALIDATE_REGEXP,
                array(
                    "options" => array(
                        "regexp" => '/[0,1]/'
                    )))
            and
            filter_var(strlen($binaryV6Address), FILTER_VALIDATE_INT, array(
                'options' => array(
                    'min_range' => Ipv6address::IPV6_BIN_LEN,
                    'max_range' => Ipv6address::IPV6_BIN_LEN
                )))) {

            $inputIpArray = str_split($binaryV6Address, Ipv6address::HEXTET_BIN_LEN);
            $ipOutput = "";
            foreach ($inputIpArray as $hextet) {
                $ipOutput .= base_convert($hextet, 2, 16) . ":";
            }
            return new Ipv6address(substr($ipOutput, 0, -1));
        } else {
            throw new InvalidArgumentException("Input is not in valid format");
        }
    }

    /**
     * Compute network address based on address and network mask
     * @param string $binaryV6Address
     * @param int $cidr
     * @return Ipv6address
     */
    public static function computeNetworkAddress(string $binaryV6Address, int $cidr): Ipv6address
    {
        return IPv6AddressFactory::computeNetworkOrLastAddress($binaryV6Address, $cidr, "0");
    }

    /**
     * Compute last address based on address and network mask
     * @param string $binaryV6Address
     * @param int $cidr
     * @return Ipv6address
     */
    public static function computeLastAddress(string $binaryV6Address, int $cidr): Ipv6address
    {
        return IPv6AddressFactory::computeNetworkOrLastAddress($binaryV6Address, $cidr, "1");
    }

    /**
     * Builder function for compute network or last address of IPv6 subnet
     * @param string $binaryV6Address
     * @param int $cidr
     * @param string $bit value 0, 1
     * @return Ipv6address
     */
    private static function computeNetworkOrLastAddress(string $binaryV6Address, int $cidr, string $bit): Ipv6address
    {
        $addressBinary_local = $binaryV6Address;
        $netmaskCidr_local = $cidr;
        $addressNetworkBin_local = "";
        for ($i = 0; $i < strlen($addressBinary_local); $i++) {
            if ($i < $netmaskCidr_local) {
                $addressNetworkBin_local .= $addressBinary_local[$i];
            } else {
                $addressNetworkBin_local .= $bit;
            }
        }
        return IPv6AddressFactory::binaryToHexa($addressNetworkBin_local);
    }

}