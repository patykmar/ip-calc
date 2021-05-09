<?php

namespace App\Entity\IP\v6;

use App\Entity\IP\Ip;
use App\Services\Ipv6addressService;
use InvalidArgumentException;

/**
 * Description of Ipv6address
 *
 * @author patykmar
 */
class Ipv6address extends Ip
{
    /** @var int binary length of IPv6 address */
    public const IPV6_BIN_LEN = 128;

    /** @var int number of bits */
    public const HEXTET_BIN_LEN = 16;

    /** @var int total number of hextet in IPv6 address */
    public const HEXTET_COUNT = 8;

    /** @var string IPv6 address in hexa format */
    private string $hexa = "";

    public function __construct(string $ipv6address = "::1")
    {
        if (filter_var($ipv6address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $this->hexa = strtolower($ipv6address);
            $this->setAddressArray();
            $this->setBinary();
        } else {
            throw new InvalidArgumentException("IPv6 is not in valid format");
        }

        if (strlen($this->binary) != self::IPV6_BIN_LEN) {
            throw new InvalidArgumentException("Len of IPv6 is out of expected range");
        }
    }

    /**
     * Set address array
     * @return self
     */
    protected function setAddressArray(): self
    {
        $this->addressArray = Ipv6addressService::hexToArray($this->hexa);
        return $this;
    }

    /**
     * Set binary format of IPv6 address.
     * @return self
     */
    protected function setBinary(): self
    {
        $this->binary = Ipv6addressService::hexToBin($this->addressArray);
        return $this;
    }

    /**
     * @param string $hexa
     */
    public function setHexa(string $hexa): void
    {
        $this->hexa = $hexa;
    }


    /**
     * @return string Hexa representation of IPv6 address
     */
    public function __toString(): string
    {
        return $this->getHexa();
    }

    /**
     * @return string IPv6 address in Hexa format
     */
    public function getHexa(): string
    {
        return $this->hexa;
    }
}
