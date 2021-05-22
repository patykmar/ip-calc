<?php


namespace App\Services;


use App\Entity\IP\v6\Ipv6subnet;

class Ipv6subnetService
{

    /**
     * Based on IPv6 Subnet return array for JSON format
     * @param Ipv6subnet $ipv6subnet
     * @return array
     */
    public function prepareJsonResponse(Ipv6subnet $ipv6subnet): array
    {
        return [
            'lookup-address' => [
                'key' => 'Lookup address:',
                'value' => $ipv6subnet->ipv6Address->getHexa() . "/" . $ipv6subnet->ipv6Netmask->getCidr()
            ],
            'network-subnet' => [
                'key' => 'Network subnet:',
                'value' => $ipv6subnet->ipv6NetworkAddress->getHexa() . '/' . $ipv6subnet->ipv6Netmask->getCidr()
            ],
            'netmask' => [
                'key' => 'Netmask:',
                'value' => $ipv6subnet->ipv6Netmask->getHexa()
            ],
            'network-address' => [
                'key' => 'Network address:',
                'value' => $ipv6subnet->ipv6NetworkAddress->getHexa()
            ],
            'last-address' => [
                'key' => 'Last address:',
                'value' => $ipv6subnet->ipv6LastAddress->getHexa()
            ],
        ];
    }
}