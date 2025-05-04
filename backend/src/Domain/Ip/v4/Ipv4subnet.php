<?php

namespace App\Domain\Ip\v4;

use App\Factories\Ipv4addressFactory;
use InvalidArgumentException;

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
        $ipv4SubnetArray = explode('/', trim($subnet));

        // standard format ip address/mask eg. 10.0.0.0/24
        if (count($ipv4SubnetArray) == 2) {
            if (filter_var($ipv4SubnetArray[1], FILTER_VALIDATE_INT,
                array(
                    'options' => array(
                        'min_range' => Ipv4netmask::IPV4_NETMASK_CIDR_VALUE_MIN,
                        'max_range' => Ipv4netmask::IPV4_NETMASK_CIDR_VALUE_MAX,
                    )))) {
                $this->ipv4Address = new Ipv4address($ipv4SubnetArray[0]);
                $this->ipv4Netmask = new Ipv4netmask((int)$ipv4SubnetArray[1]);

                $this->setNetworkAddress()
                    ->setBroadcastkAddress()
                    ->setFirstAddressOfSubnet()
                    ->setLastAddressOfSubnet()
                    ->setSecondAddressOfSubnet();
            } else {
                throw new InvalidArgumentException("Netmask is in wrong range");
            }
        } else {
            throw new InvalidArgumentException("Invalid input parametr");
        }
    }

    /**
     * Compute network address based on address and network mask
     * @return self
     */
    private function setNetworkAddress(): self
    {
        $this->ipv4AddressNetwork = Ipv4addressFactory::calculateNetworkAddress($this);
        return $this;
    }

    /**
     * Compute Broadcast address based on address and network mask
     * @return self
     */
    private function setBroadcastkAddress(): self
    {
        $this->ipv4AddressBroadcast = Ipv4addressFactory::calculateBroadcastAddress($this);
        return $this;
    }


    /**
     * Set first IP of subnet
     * @return self
     */
    private function setFirstAddressOfSubnet(): self
    {
        $this->ipv4FirstAddress = Ipv4addressFactory::setAddressOfSubnet($this, 1);
        return $this;
    }

    /**
     * Set second IP of subnet
     * @return self
     */
    private function setSecondAddressOfSubnet(): self
    {
        $this->ipv4SecondAddress = Ipv4addressFactory::setAddressOfSubnet($this, 2);
        return $this;
    }

    /**
     * Compute Last usable address based on address and network mask
     */
    private function setLastAddressOfSubnet(): self
    {
        if ($this->ipv4Netmask->getCidr() < 31) {
            $addressBroadcast_local = $this->ipv4AddressBroadcast->getInteger();
            $lastIpInt = --$addressBroadcast_local;
            $this->ipv4LastAddress = Ipv4addressFactory::binToDec(str_pad(
                decbin($lastIpInt),
                Ipv4netmask::IPV4_NETMASK_CIDR_VALUE_MAX,
                "0", STR_PAD_LEFT));
            unset($lastIpInt);
        } elseif ($this->ipv4Netmask->getCidr() == 31) {
            // last IP is the same as broadcast IP
            $this->ipv4LastAddress = $this->ipv4AddressBroadcast;
        } else {
            // netmask is /32
            $this->ipv4LastAddress = $this->ipv4Address;
        }
        return $this;
    }

    /**
     * @return string Return IPv4 network address slash network subnet in CIDR format eg. 192.168.1.0/24
     */
    public function __toString(): string
    {
        return $this->ipv4AddressNetwork->getDecadic() . "/" . $this->ipv4Netmask->getCidr();
    }

}
