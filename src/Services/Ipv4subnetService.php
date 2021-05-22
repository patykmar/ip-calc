<?php

namespace App\Services;

use App\Entity\IP\v4\Ipv4netmask;
use App\Entity\IP\v4\Ipv4subnet;
use App\Factories\Ipv4addressFactory;
use InvalidArgumentException;

class Ipv4subnetService
{
    /**
     * Based on IPv4 Subnet return array for JSON format
     * @param Ipv4subnet $ipv4subnet
     * @return array
     */
    public function prepareJsonResponse(Ipv4subnet $ipv4subnet): array
    {
        return [
            'network-subnet' => [
                'key' => 'Network subnet:',
                'value' => $ipv4subnet->ipv4AddressNetwork->getDecadic() . "/" . $ipv4subnet->ipv4Netmask->getCidr()
            ],
            'netmask' => [
                'key' => 'Netmask:',
                'value' => $ipv4subnet->ipv4Netmask->getDecadic()
            ],
            'network-address' => [
                'key' => 'Network address:',
                'value' => $ipv4subnet->ipv4AddressNetwork->getDecadic()
            ],
            'first-address' => [
                'key' => 'First address:',
                'value' => $ipv4subnet->ipv4FirstAddress->getDecadic()
            ],
            'last-address' => [
                'key' => 'Last address:',
                'value' => $ipv4subnet->ipv4LastAddress->getDecadic()
            ],
            'broadcast-address' => [
                'key' => 'Broadcast address:',
                'value' => $ipv4subnet->ipv4AddressBroadcast->getDecadic()
            ],
            'number-of-usable-address' => [
                'key' => 'Number of usable address:',
                'value' => $ipv4subnet->ipv4LastAddress->getInteger() - $ipv4subnet->ipv4FirstAddress->getInteger() + 1
            ],
            'nsx-cidr' => [
                'key' => 'NSX CIDR:',
                'value' => $ipv4subnet->ipv4FirstAddress->getDecadic() . '/' . $ipv4subnet->ipv4Netmask->getCidr()
            ],
            'nsx-static-ip-pool' => [
                'key' => 'NSX Static IP pool:',
                'value' => $ipv4subnet->ipv4SecondAddress->getDecadic() . '-' . $ipv4subnet->ipv4LastAddress->getDecadic()
            ],
        ];
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
                $newIpAddressObject = Ipv4addressFactory::add($this->ipv4subnet->ipv4AddressNetwork, $networkAddress);
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