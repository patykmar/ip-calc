<?php

namespace App\Services;

use App\Entity\IP\v4\Ipv4netmask;
use App\Entity\IP\v4\Ipv4subnet;
use App\Factories\Ipv4addressFactory;
use InvalidArgumentException;

class Ipv4subnetService
{
    public const IPV4_LAST_CIDR_NETWORK = 31;

    /**
     * Based on IPv4 Subnet return array for JSON format
     * @param Ipv4subnet $ipv4subnet
     * @return array
     */
    public function prepareJsonResponse(Ipv4subnet $ipv4subnet, array $smallerSubnets): array
    {

        // remap smaller subnet to real value instead of return objects
        $smallerSubnets_localArray = array();
//        foreach($smallerSubnets as $smallerSubnet){
//            foreach ($smallerSubnet as $subnet){
//                $smallerSubnets_localArray[$subnet->ipv4Netmask->getCidr()][] = $subnet->__toString();
//            }
//        }
        foreach ($smallerSubnets as $key => $smallerSubnet) {
            foreach ($smallerSubnet as $keyy => $subnet) {
                $smallerSubnets_localArray[$subnet->ipv4Netmask->getCidr().'-subnet'][$key.$keyy.'-value'] = [
                    'cidr' => $subnet->ipv4Netmask->getCidr(),
                    'subnet' => $subnet->__toString()
                ];
//                echo "key & keyy: " . $key . " - " . $keyy . '-subnet <br>';
//                echo "CIDR: " . $subnet->ipv4Netmask->getCidr() . "<br>";
//                echo "subnet: " . $subnet->__toString() . "<br><br>";
            }
        }

        dump($smallerSubnets_localArray);


        $returnArray = [
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
//            'smaller-subnets' => [
//                'key' => 'Smaller subnets',
//                'value' => $smallerSubnets_localArray
//            ],
        ];
//        dd($returnArray);
        return $returnArray;
    }


    /**
     * Based on current IPv4 subnet calculate smaller subnets and return they
     * as array of Ipv4subnet objects.
     * @param int $smallerCidr
     * @param Ipv4subnet $ipv4subnet
     * @return array Description array of Ipv4subnet objects.
     */
    public function calculateSmallerSubnets(int $smallerCidr, Ipv4subnet $ipv4subnet): array
    {
        $smallerNetwork = new Ipv4netmask($smallerCidr);

        if ($ipv4subnet->ipv4Netmask->getCidr() < $smallerCidr) {

            $biggerWildcard = $ipv4subnet->ipv4Netmask->getWildcardInt() + 1;
            $smallerWildcard = $smallerNetwork->getWildcardInt() + 1;

            $countOfLoop = $biggerWildcard / $smallerWildcard;

            $returnArray = array();

            // string [original address]/[smaller cidr] eg. 1.0.0.0/26
            $firstSubnet = $ipv4subnet->ipv4AddressNetwork->getDecadic() . '/' . $smallerNetwork->getCidr();

            $returnArray[] = new Ipv4subnet($firstSubnet);
            unset($firstSubnet);

            for ($i = 1; $i < $countOfLoop; $i++) {
                $networkAddress = $smallerWildcard * $i;
                $newIpAddressObject = Ipv4addressFactory::add($ipv4subnet->ipv4AddressNetwork, $networkAddress);
                $subnet = $newIpAddressObject->getDecadic() . '/' . $smallerNetwork->getCidr();
                $returnArray[] = new Ipv4subnet($subnet);
                unset($subnet, $newIpAddressObject, $networkAddress);
            }
            return $returnArray;
        } else {
            throw new InvalidArgumentException("Network subnet is out of allowed range, I can't calculate smaller network range");
        }
    }

    /**
     * Generate smaller subnets as array of Ipv4subnet objects by default for next 5 CIDR value
     * @param Ipv4subnet $ipv4subnet
     * @param int $cidrStartingPoint
     * @param int $deepIndex
     * @return array
     */
    public function getSmallerSubnet(Ipv4subnet $ipv4subnet, int $cidrStartingPoint, int $deepIndex = 4): array
    {
        // calculate smaller subnets, in range /1 - /29 next 4 smaller subnet only
        $subnetsArray = array();
        $iterationCount = 0;
        for ($i = ($cidrStartingPoint + 1); $i < self::IPV4_LAST_CIDR_NETWORK; $i++) {
            $subnetsArray[] = $this->calculateSmallerSubnets($i, $ipv4subnet);
            $iterationCount++;
            if ($iterationCount > $deepIndex) {
                break;
            }
        }
        return $subnetsArray;
    }


}