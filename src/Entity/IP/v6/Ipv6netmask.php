<?php

namespace App\Entity\IP\v6;

use InvalidArgumentException;

/**
 * Description of Ipv6netmask
 *
 * @author Martin Patyk 2021/03/03
 */
class Ipv6netmask extends Ipv6address
{

    /** @var int network mask in CIDR notation */
    private $cidr;

    /** @var string Wildcard mask */
    private $wildcard;


    public function __construct(int $cidr = 64)
    {
        if (filter_var($cidr, FILTER_VALIDATE_INT,
            ['options' => array(
                'min_range' => 1,
                'max_range' => 128,
            )])) {
            $this->setCidr($cidr);
            $this->cidrToBin();
            $this->convertNetmaskToWildcard();
            $this->binToHexa();
            parent::__construct($this->getHexa());
        } else {
            throw new InvalidArgumentException("Netmask is in wrong range");
        }
    }

    /**
     * @param int $cidr Integer value of IPv4 netmask CIDR
     */
    private function setCidr(int $cidr): void
    {
        $this->cidr = $cidr;
    }

    /**
     * @param string $wildcard Binary representation of netmask
     */
    private function setWildcard(string $wildcard): void
    {
        $this->wildcard = $wildcard;
    }

    /**
     * Set binary format of netmask
     */
    private function cidrToBin(): void
    {
        $binNetmask = "";
        for ($i = 0; $i < parent::IPV6_BIN_LEN; $i++) {
            ($this->getCidr() > $i) ? $binNetmask .= "1" : $binNetmask .= "0";
        }

        $this->setBin($binNetmask);
    }

    private function convertNetmaskToWildcard(): void
    {
        $out_local = "";
        $netMaskBin_local = $this->getBin();
        for ($i = 0; $i < strlen($netMaskBin_local); $i++) {
            ($netMaskBin_local[$i] == '0') ? $out_local .= '1' : $out_local .= '0';
        }

        $this->setWildcard($out_local);
        unset($out_local);
    }

    /**
     * Convert binary netmask to hexa format
     */
    private function binToHexa(): void
    {
        $localString = "";
        $localArrayBinary = str_split($this->getBin(), parent::HEXTET_BIN_LEN);
        $localArrayHexa = array();

        foreach ($localArrayBinary as $item) {
            $localString .= base_convert($item, 2, 16) . ":";
            $localArrayHexa[] = base_convert($item, 2, 16);
        }

        // remove last colon
        $localString = rtrim($localString, ":");

        $this->setIpv6Array($localArrayHexa);
        $this->setHexa($localString);

        unset($localString, $localArrayBinary, $localArrayHexa);
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
