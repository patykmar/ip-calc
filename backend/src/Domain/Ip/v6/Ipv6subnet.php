<?php

namespace App\Domain\Ip\v6;

use App\Factories\IPv6AddressFactory;
use InvalidArgumentException;

/**
 * Description of Ipv6subnet
 *
 * @author Martin Patyk  - 2021/03/04
 */
class Ipv6subnet
{

    /** @var Ipv6address IPv6 address object */
    public Ipv6address $ipv6Address;

    /** @var Ipv6address IPv6 network address object */
    public Ipv6address $ipv6NetworkAddress;

    /** @var Ipv6address IPv6 last address in subnet */
    public Ipv6address $ipv6LastAddress;

    /** @var Ipv6netmask IPv6 network object */
    public Ipv6netmask $ipv6Netmask;

    /**
     * @param string $subnet IPv6 subnet eg. 2a01:4240:5f52:8a84:d440:866e:4bf7:e6f7/64
     */
    public function __construct(string $subnet)
    {
        // $ipv6AddressAndMask[0] -> \App\Classes\Ipv6address
        // $ipv6AddressAndMask[1] -> \App\Classes\Ipv6netmask
        $ipv6AddressAndMask = explode('/', trim($subnet));

        if (count($ipv6AddressAndMask) == 2) {
            try {
                $this->ipv6Address = new Ipv6address($ipv6AddressAndMask[0]);
                $this->ipv6Netmask = new Ipv6netmask((int)$ipv6AddressAndMask[1]);

                $this->setIpv6NetworkAddress()
                    ->setIpv6LastAddress();
            } catch (InvalidArgumentException $exc) {
                throw new InvalidArgumentException($exc->getMessage());
            }
        } else {
            throw new InvalidArgumentException("Invalid input parametr");
        }
    }

    /**
     * @return self
     */
    private function setIpv6LastAddress(): self
    {
        $this->ipv6LastAddress = IPv6AddressFactory::computeLastAddress(
            $this->getIpv6Address()->getBinary(),
            $this->ipv6Netmask->getCidr()
        );
        return $this;
    }

    /**
     * @return self
     */
    private function setIpv6NetworkAddress(): self
    {
        $this->ipv6NetworkAddress = IPv6AddressFactory::computeNetworkAddress(
            $this->ipv6Address->getBinary(),
            $this->ipv6Netmask->getCidr()
        );
        return $this;
    }

    /**
     * @return Ipv6address Object with IPv6 address
     */
    public function getIpv6Address(): Ipv6address
    {
        return $this->ipv6Address;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->ipv6NetworkAddress->getHexa().
            "/".
            $this->ipv6Netmask->getCidr();
    }


}
