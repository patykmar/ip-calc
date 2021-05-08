<?php


namespace App\Services;


use App\Entity\IP\v4\Ipv4netmask;
use App\Entity\IP\v4\Ipv4subnet;
use App\Factories\Ipv4addressFactory;
use InvalidArgumentException;

class Ipv4subnetService
{
    private Ipv4subnet $ipv4subnet;

    /**
     * Ipv4subnetService constructor.
     * @param Ipv4subnet $ipv4subnet
     */
    public function __construct(Ipv4subnet $ipv4subnet)
    {
        $this->ipv4subnet = $ipv4subnet;
    }


    /**
     * Based on current IPv4 subnet calculate smaller subnets and return they
     * as array of Ipv4subnet objects.
     * @return array Description array of Ipv4subnet objects.
     */
    public function getSmallerSubnet(int $smallerCidr): array
    {
        $smallerNetwork = new Ipv4netmask($smallerCidr);

        if ($this->ipv4subnet->ipv4Netmask->getCidr() < $smallerCidr) {

            $biggerWildcard = $this->ipv4subnet->ipv4Netmask->getWildcardInt() + 1;
            $smallerWildcard = $smallerNetwork->getWildcardInt() + 1;

            $countOfLoop = $biggerWildcard / $smallerWildcard;

            $returnArray = array();

            // string [original address]/[smaller cidr] eg. 1.0.0.0/26
            $firstSubnet = $this->ipv4subnet->ipv4AddressNetwork->getDecadic() . '/' . $smallerNetwork->getCidr();

            $returnArray[] = new Ipv4subnet($firstSubnet);
            unset($firstSubnet);

            for ($i = 1; $i < $countOfLoop; $i++) {
                $networkAddress = $smallerWildcard * $i;
                $newIpAddressObject = Ipv4addressFactory::add($this->ipv4subnet->ipv4AddressNetwork,$networkAddress);
                $subnet = $newIpAddressObject->getDecadic() . '/' . $smallerNetwork->getCidr();
                $returnArray[] = new Ipv4subnet($subnet);
                unset($subnet, $newIpAddressObject, $networkAddress);
            }
            return $returnArray;
        } else {
            throw new InvalidArgumentException("Network subnet is out of allowed range, I can't calculate smaller network range");
        }
    }

}