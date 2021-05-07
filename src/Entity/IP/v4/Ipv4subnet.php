<?php

namespace App\Classes\IP\v4;

use InvalidArgumentException;
use Symfony\Component\String\UnicodeString;
use function Symfony\Component\String\u;

/**
 * Description of Ipv4subnet
 *
 * @author Martin Patyk
 */
class Ipv4subnet
{

    /** @var Ipv4address IPv4 address object */
    public Ipv4address $ipv4Address;

    /** @var Ipv4netmask IPv4 netmask object */
    public Ipv4netmask $ipv4Netmask;

    /** @var Ipv4address IPv4 network address */
    public Ipv4address $ipv4AddressNetwork;

    /** @var Ipv4address IPv4 broadcast address */
    public Ipv4address $ipv4AddressBroadcast;

    /** @var Ipv4address IPv4 First IP of subnet */
    public Ipv4address $ipv4FirstAddress;

    /** @var Ipv4address IPv4 Second IP of subnet */
    public Ipv4address $ipv4SecondAddress;

    /** @var Ipv4address IPv4 Last IP of subnet */
    public Ipv4address $ipv4LastAddress;

    public function __construct(string $subnet)
    {
        // $ipv4SubnetArray[0] -> Ipv4address
        // $ipv4SubnetArray[1] -> Ipv4netmask
        $ipv4SubnetArray = u($subnet)
            ->trim()
            ->split("/");
        $ipv4SubnetArray = UnicodeString::unwrap($ipv4SubnetArray);

        // standard format ip address/mask eg. 10.0.0.0/24
        if (count($ipv4SubnetArray) == 2) {
            if (filter_var($ipv4SubnetArray[1], FILTER_VALIDATE_INT,
                array(
                    'options' => array(
                        'min_range' => 1,
                        'max_range' => 32,
                    )))) {
                $this->ipv4Address = new Ipv4address($ipv4SubnetArray[0]);
                $this->ipv4Netmask = new Ipv4netmask($ipv4SubnetArray[1]);

                $this->setNetworkAddress();
                $this->setBroadcastkAddress();
                $this->setFirstAddressOfSubnet();
                $this->setLastAddressOfSubnet();
                $this->setSecondAddressOfSubnet();
            } else {
                throw new InvalidArgumentException("Netmask is in wrong range");
            }
        } else {
            throw new InvalidArgumentException("Invalid input parametr");
        }
    }

    /**
     * Compute network address based on address and network mask
     */
    private function setNetworkAddress(): void
    {
        $addBin_local = $this->ipv4Address->getBinary();
        $netCidr_local = $this->ipv4Netmask->getCidr();
        $addNetBin_local = "";

        for ($i = 0; $i < strlen($addBin_local); $i++):
            if ($i < $netCidr_local):
                $addNetBin_local .= $addBin_local[$i];
            else:
                $addNetBin_local .= "0";
            endif;
        endfor;

        $this->ipv4AddressNetwork = Ipv4address::binToDec($addNetBin_local);
        unset($addBin_local, $netCidr_local, $addNetBin_local);
    }

    /**
     * Compute Broadcast address based on address and network mask
     */
    private function setBroadcastkAddress(): void
    {
        $addBin_local = $this->ipv4Address->getBinary();
        $netCidr_local = $this->ipv4Netmask->getCidr();
        $addBroBin_local = "";

        for ($i = 0; $i < strlen($addBin_local); $i++):
            if ($i < $netCidr_local):
                $addBroBin_local .= $addBin_local[$i];
            else:
                $addBroBin_local .= "1";
            endif;
        endfor;

        $this->ipv4AddressBroadcast = Ipv4address::binToDec($addBroBin_local);
        unset($addBin_local, $netCidr_local, $addBroBin_local);
    }

    /**
     * Compute First usable address based on address and network mask
     */
    private function setFirstAddressOfSubnet(): void
    {
        if ($this->ipv4Netmask->getCidr() < 31):
            $addNet_local = $this->ipv4AddressNetwork->getInteger();
            $firtIpInt = ++$addNet_local;
            $this->ipv4FirstAddress = Ipv4address::binToDec(str_pad(decbin($firtIpInt), 32, "0", STR_PAD_LEFT));
            unset($firtIpInt, $firtIpInt);
        elseif ($this->ipv4Netmask->getCidr() == 31):
            $this->ipv4FirstAddress = $this->ipv4AddressNetwork;
        else:
            // netmask is /32
            $this->ipv4FirstAddress = $this->ipv4Address;
        endif;
    }

    /**
     * Compute First usable address based on address and network mask
     */
    private function setSecondAddressOfSubnet(): void
    {
        if ($this->ipv4Netmask->getCidr() < 30):
            $addNet_local = $this->ipv4AddressNetwork->getInteger();
            $firtIpInt = $addNet_local + 2;
            $this->ipv4SecondAddress = Ipv4address::binToDec(str_pad(decbin($firtIpInt), 32, "0", STR_PAD_LEFT));
            unset($firtIpInt, $firtIpInt);
        elseif ($this->ipv4Netmask->getCidr() == 30):
            $this->ipv4SecondAddress = $this->ipv4FirstAddress;
        else:
            // netmask is in range /31 - /32
            $this->ipv4SecondAddress = $this->ipv4Address;
        endif;
    }

    /**
     * Compute Last usable address based on address and network mask
     */
    private function setLastAddressOfSubnet(): void
    {
        if ($this->ipv4Netmask->getCidr() < 31):
            $addBro_local = $this->ipv4AddressBroadcast->getInteger();
            $lastIpInt = --$addBro_local;
            $this->ipv4LastAddress = Ipv4address::binToDec(str_pad(decbin($lastIpInt), 32, "0", STR_PAD_LEFT));
            unset($lastIpInt);
        elseif ($this->ipv4Netmask->getCidr() == 31):
            $this->ipv4LastAddress = $this->ipv4AddressBroadcast;
        else:
            // netmask is /32
            $this->ipv4LastAddress = $this->ipv4Address;
        endif;
    }

    /**
     * Based on current IPv4 subnet calculate smaller subnets and return they
     * as array of Ipv4subnet objects.
     * @return array Description array of Ipv4subnet objects.
     */
    public function getSmallerSubnet(int $smallerCidr): array
    {
        $smallerNetwork = new Ipv4netmask($smallerCidr);

        if ($this->ipv4Netmask->getCidr() < $smallerCidr):

            $biggerWildcard = $this->ipv4Netmask->getWildcardInt() + 1;
            $smallerWildcard = $smallerNetwork->getWildcardInt() + 1;

            $countOfLoop = $biggerWildcard / $smallerWildcard;

            $returnArray = array();

            // string [original address]/[smaller cidr] eg. 1.0.0.0/26
            $firstSubnet = $this->ipv4AddressNetwork->getDecadic() . '/' . $smallerNetwork->getCidr();

            $returnArray[] = new Ipv4subnet($firstSubnet);
            unset($firstSubnet);

            for ($i = 1; $i < $countOfLoop; $i++):
                $networkAddress = $smallerWildcard * $i;
                $newIpAddressObject = $this->ipv4AddressNetwork->add($networkAddress);
                $subnet = $newIpAddressObject->getDecadic() . '/' . $smallerNetwork->getCidr();
                $returnArray[] = new Ipv4subnet($subnet);
                unset($subnet, $newIpAddressObject, $networkAddress);
            endfor;
            return $returnArray;
        else:
            throw new InvalidArgumentException("Network subnet is out of allowed range, I can't calculate smaller network range");
        endif;
    }

    /**
     * @return string Return IPv4 network address slash network subnet in CIDR format eg. 192.168.1.0/24
     */
    public function __toString(): string
    {
        return $this->ipv4AddressNetwork->getDecadic() . "/" . $this->ipv4Netmask->getCidr();
    }

}
