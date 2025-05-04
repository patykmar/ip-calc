<?php

namespace App\Domain\Ip\v6;

use App\Services\Ipv6netmaskService;
use InvalidArgumentException;

/**
 * Description of Ipv6netmask
 *
 * @author Martin Patyk 2021/03/03
 */
class Ipv6netmask extends Ipv6address
{

    /** @var int network mask in CIDR notation */
    private int $cidr;

    /** @var string Wildcard mask */
    private string $wildcard;


    public function __construct(int $cidr = 64)
    {
        if (filter_var($cidr, FILTER_VALIDATE_INT,
            ['options' => array(
                'min_range' => 1,
                'max_range' => Ipv6address::IPV6_BIN_LEN,
            )])) {
            $this->cidr = $cidr;
            parent::__construct(Ipv6netmaskService::binToHexa(Ipv6netmaskService::cidrToBinary($cidr)));
            $this->setWildcard();
        } else {
            throw new InvalidArgumentException("Netmask is in wrong range");
        }
    }

    /**
     * @return Ipv6netmask
     */
    private function setWildcard(): self
    {
        $this->wildcard = Ipv6netmaskService::convertNetmaskToWildcard($this->binary);
        return $this;
    }

    /**
     * @return int Integer value of CIDR number of IPv6 netmask
     */
    public function getCidr(): int
    {
        return $this->cidr;
    }

    /**
     * @return string binary representation of netmask wildcard
     */
    public function getWildcard(): string
    {
        return $this->wildcard;
    }

    /**
     * @return string Hexa representation of IPv6 netmask
     */
    public function __toString(): string
    {
        return parent::__toString();
    }
}
