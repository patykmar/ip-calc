<?php


namespace App\Factories;


use App\Entity\IP\v4\Ipv4address;
use App\Entity\IP\v4\Ipv4subnet;
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

    /**
     * Calculate network instance of Ipv4address
     * @param Ipv4subnet $ipv4subnet
     * @return Ipv4address
     */
    public static function calculateNetworkAddress(Ipv4subnet $ipv4subnet): Ipv4address
    {
        return Ipv4addressFactory::calculateNetworkOrBroadcastAddress($ipv4subnet,"0");
    }

    /**
     * Based on parameter $bit can calculate network or broadcast instance of Ipv4address
     * @param Ipv4subnet $ipv4subnet
     * @return Ipv4address
     */
    public static function calculateBroadcastAddress(Ipv4subnet $ipv4subnet): Ipv4address
    {
        return Ipv4addressFactory::calculateNetworkOrBroadcastAddress($ipv4subnet);
    }

    /**
     * Based on parameter $bit can calculate network or broadcast instance of Ipv4address
     * @param Ipv4subnet $ipv4subnet
     * @param string $bit
     * @return Ipv4address
     */
    private static function calculateNetworkOrBroadcastAddress(Ipv4subnet $ipv4subnet, string $bit = "1"): Ipv4address
    {
        // check $bit for only permit value
        if ($bit == "0" or $bit == "1") {
            $addBin_local = $ipv4subnet->ipv4Address->getBinary();
            $netCidr_local = $ipv4subnet->ipv4Netmask->getCidr();
            $addBroBin_local = "";

            for ($i = 0; $i < strlen($addBin_local); $i++) {
                if ($i < $netCidr_local) {
                    $addBroBin_local .= $addBin_local[$i];
                } else {
                    $addBroBin_local .= $bit;
                }
            }
            unset($addBin_local, $netCidr_local);
            return Ipv4addressFactory::binToDec($addBroBin_local);
        }

        // $bit is something else
        throw new InvalidArgumentException("Bit can be only 0 or 1");
    }


    /**
     * Based on argument $addressPosition calculate first, second, etc. address
     * @param Ipv4subnet $ipv4subnet
     * @param int $addressPosition
     * @return Ipv4address
     */
    public static function setAddressOfSubnet(Ipv4subnet $ipv4subnet, int $addressPosition = 1): Ipv4address
    {
        // 32-1 or 32-2, define upper limit of netmask
        $cidrUpperLimit = self::IPV4_NETMASK_CIDR_VALUE_MAX - $addressPosition;


        if ($ipv4subnet->ipv4Netmask->getCidr() < $cidrUpperLimit) {
            // netmask CIDR is lower than upper limit I can calculate address in IP range
            $addNet_local = $ipv4subnet->ipv4AddressNetwork->getInteger();
            $requiredAddressInt = $addNet_local + $addressPosition;
            return Ipv4addressFactory::binToDec(
                str_pad(decbin($requiredAddressInt),
                    self::IPV4_NETMASK_CIDR_VALUE_MAX, "0", STR_PAD_LEFT));
        } elseif ($ipv4subnet->ipv4Netmask->getCidr() == $cidrUpperLimit) {
            return $ipv4subnet->ipv4LastAddress;
        } else {
            // netmask is in range /31 - /32
            return $ipv4subnet->ipv4Address;
        }

    }


}