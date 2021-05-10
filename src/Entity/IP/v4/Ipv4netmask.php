<?php

namespace App\Entity\IP\v4;


use App\Model\Ipv4NetworkModel;
use App\Services\Ipv4netmaskService;
use InvalidArgumentException;

/**
 * Description of Ipv4netmask
 *
 * @author Martin Patyk
 */
class Ipv4netmask extends Ipv4address
{
    /** @const int IPv4 netmask CIDR min int value */
    public const IPV4_NETMASK_CIDR_VALUE_MIN = 1;

    /** @const int IPv4 netmask CIDR max int value */
    public const IPV4_NETMASK_CIDR_VALUE_MAX = 32;

    /** @var int network mask in CIDR notation */
    private int $cidr;

    /** @var string Wildcard mask in binary format */
    private string $wildcard;

    /** @var int Wildcard mask integer */
    private int $wildcardInt;

    public function __construct(int $cidr = 24)
    {
        if (filter_var($cidr, FILTER_VALIDATE_INT,
            ['options' => array(
                'min_range' => self::IPV4_NETMASK_CIDR_VALUE_MIN,
                'max_range' => self::IPV4_NETMASK_CIDR_VALUE_MAX,
            )])) {
            $this->cidr = $cidr;
            parent::__construct($this->getDecadic());
            $this->setWildcard()
                ->setWildcardInt();
        } else {
            throw new InvalidArgumentException("Netmask is in wrong range");
        }
    }

    /**
     * Set network wildcard based on network binary format
     * @return self
     */
    private function setWildcard(): self
    {
        $this->wildcard = Ipv4netmaskService::convertNetmaskToWildcard($this->getBinary());
        return $this;
    }

    /**
     * Set network wildcard as integer number
     */
    private function setWildcardInt(): void
    {
        $this->wildcardInt = bindec($this->getWildcard());
    }

    /**
     * @return int network mask in CIRD format
     */
    public function getCidr(): int
    {
        return $this->cidr;
    }

    /**
     * @return string network mask in dec format
     */
    public function getDecadic(): string
    {
        return Ipv4NetworkModel::CIDR_TO_NETWORK[$this->getCidr()];
    }

    /**
     * @return string network wildcard in binary
     */
    public function getWildcard(): string
    {
        return $this->wildcard;
    }

    /**
     * @return int number of wildcard mask
     */
    public function getWildcardInt(): int
    {
        return $this->wildcardInt;
    }

}
