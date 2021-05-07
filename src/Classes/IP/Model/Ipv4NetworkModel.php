<?php


namespace App\Classes\IP\Model;



class Ipv4NetworkModel
{
    public const CIDR_TO_NETWORK = array(
        1 => "128.0.0.0",
        2 => "192.0.0.0",
        3 => "224.0.0.0",
        4 => "240.0.0.0",
        5 => "248.0.0.0",
        6 => "252.0.0.0",
        7 => "254.0.0.0",
        8 => "255.0.0.0",
        9 => "255.128.0.0",
        10 => "255.192.0.0",
        11 => "255.224.0.0",
        12 => "255.240.0.0",
        13 => "255.248.0.0",
        14 => "255.252.0.0",
        15 => "255.254.0.0",
        16 => "255.255.0.0",
        17 => "255.255.128.0",
        18 => "255.255.192.0",
        19 => "255.255.224.0",
        20 => "255.255.240.0",
        21 => "255.255.248.0",
        22 => "255.255.252.0",
        23 => "255.255.254.0",
        24 => "255.255.255.0",
        25 => "255.255.255.128",
        26 => "255.255.255.192",
        27 => "255.255.255.224",
        28 => "255.255.255.240",
        29 => "255.255.255.248",
        30 => "255.255.255.252",
        31 => "255.255.255.254",
        32 => "255.255.255.255"
    );
    public const SLASH_CIDR_NETWORK = array(
        1 => "/1 - 128.0.0.0",
        2 => "/2 - 192.0.0.0",
        3 => "/3 - 224.0.0.0",
        4 => "/4 - 240.0.0.0",
        5 => "/5 - 248.0.0.0",
        6 => "/6 - 252.0.0.0",
        7 => "/7 - 254.0.0.0",
        8 => "/8 - 255.0.0.0",
        9 => "/9 - 255.128.0.0",
        10 => "/10 - 255.192.0.0",
        11 => "/11 - 255.224.0.0",
        12 => "/12 - 255.240.0.0",
        13 => "/13 - 255.248.0.0",
        14 => "/14 - 255.252.0.0",
        15 => "/15 - 255.254.0.0",
        16 => "/16 - 255.255.0.0",
        17 => "/17 - 255.255.128.0",
        18 => "/18 - 255.255.192.0",
        19 => "/19 - 255.255.224.0",
        20 => "/20 - 255.255.240.0",
        21 => "/21 - 255.255.248.0",
        22 => "/22 - 255.255.252.0",
        23 => "/23 - 255.255.254.0",
        24 => "/24 - 255.255.255.0",
        25 => "/25 - 255.255.255.128",
        26 => "/26 - 255.255.255.192",
        27 => "/27 - 255.255.255.224",
        28 => "/28 - 255.255.255.240",
        29 => "/29 - 255.255.255.248",
        30 => "/30 - 255.255.255.252",
        31 => "/31 - 255.255.255.254",
        32 => "/32 - 255.255.255.255"
    );

    /** @var integer how many iteration will be use in function getCidrAndNetwork() */
    public const COUNT_OF_ITERATION = 5;

    /**
     * Based on CIDR number return array with smaller networks
     * @param int $cidr network CIDR number 1-32
     * @return array List of network mask /[CIDR] - [Netmask]
     */
    public static function getCidrAndNetwork(int $cidr): array
    {
        $returnArray_local = array();
        $i = 0;
        foreach (Ipv4NetworkModel::SLASH_CIDR_NETWORK as $key => $value) {
            if (($key > $cidr) and ($i < Ipv4NetworkModel::COUNT_OF_ITERATION)) {
                $returnArray_local[$key] = $value;
                $i++;
            }
        }
        return $returnArray_local;
    }
}