<?php


namespace App\Factories;


use App\Entity\IP\v4\Ipv4address;
use InvalidArgumentException;

class Ipv4addressFactory
{
    /** @const int how many numbers are in one IP address octet */
    private const OCTET_LEN = 8;

    /** @const int IPv4 int value eg. 0.0.0.1 */
    private const IPV4_INT_VALUE_MAX = 1;

    /** @const int IPv4 int value eg. 255.255.255.255 */
    private const IPV4_INT_VALUE_MIN = 4294967296;

    /** @const int IPv4 netmask CIDR min int value */
    private const IPV4_NETMASK_CIDR_VALUE_MIN = 1;

    /** @const int IPv4 netmask CIDR max int value */
    private const IPV4_NETMASK_CIDR_VALUE_MAX = 32;

    /**
     * Calculate new IPv4 address based on current IP plus count of IP
     * @param int $addNumber count of IP
     * @return Ipv4address new object with result
     */
    public static function add(Ipv4address $address, int $addNumber): Ipv4address
    {
        if (filter_var($addNumber, FILTER_VALIDATE_INT,
            array("options" => array(
                "min_range" => Ipv4addressFactory::IPV4_INT_VALUE_MAX,
                "max_range" => Ipv4addressFactory::IPV4_INT_VALUE_MIN)
            )
        )) {
            $addNumber_local = $address->getInteger() + $addNumber;
            if ($addNumber_local < Ipv4addressFactory::IPV4_INT_VALUE_MIN) {
                return Ipv4addressFactory::binToDec(
                    str_pad(decbin($addNumber_local),
                        self::IPV4_NETMASK_CIDR_VALUE_MAX, "0",
                        STR_PAD_LEFT));
            } else {
                throw new InvalidArgumentException("Result is out of range");
            }
        } else {
            throw new InvalidArgumentException("Input integer is out ouf range");
        }
    }


    /**
     * Convert IP from BIN to DEC
     * BIN=11000000101010000000000100000001 to 192.168.1.1
     * @param string $inputIP IPv4Address in binary format
     * @return Ipv4address New object of Ipv4address
     */
    public static function binToDec(string $inputIP): Ipv4address
    {
        // input must be only in binary format and length must be in range 1 - 32
        if (filter_var($inputIP, FILTER_VALIDATE_REGEXP,
                array(
                    "options" => array(
                        "regexp" => '/[0,1]/'
                    )))
            and
            filter_var(strlen($inputIP), FILTER_VALIDATE_INT, array(
                'options' => array(
                    'min_range' => Ipv4addressFactory::IPV4_NETMASK_CIDR_VALUE_MIN,
                    'max_range' => Ipv4addressFactory::IPV4_NETMASK_CIDR_VALUE_MAX
                )))) {
            $IpArray = str_split($inputIP, self::OCTET_LEN);
            $ipOutput = "";
            foreach ($IpArray as $item) {
                $ipOutput .= bindec($item) . ".";
            }

            return new Ipv4address(substr($ipOutput, 0, -1));
        } else {
            throw new InvalidArgumentException("Input is not in valid format");
        }
    }


}