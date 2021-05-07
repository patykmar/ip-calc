<?php

namespace App\Entity\IP\v4;

use App\Entity\IP\Ip;
use InvalidArgumentException;

/**
 * Description of Ipv4address
 *
 * @author Martin Patyk
 */
class Ipv4address extends Ip
{
    /** @var int how many numbers are in one IP address octet */
    protected const OCTET_LEN = 8;

    /** @var string Human readable address eg. 192.168.1.1 */
    private string $decadic;

    /** @var int Address in Integer eg. 3232235777 */
    private int $integer;

    /**
     * @param string $address IPv4 address
     * @throws InvalidArgumentException when IPv4 address is not in correct format
     */
    public function __construct(string $address = "10.0.0.1")
    {
        if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->setDecadic($address)
                ->setAddressArray();
            $this->setBinary();
            $this->setInteger();
        } else {
            throw new InvalidArgumentException("IPv4 is not in correct form");
        }
    }

    /**
     * @return string Human readable address eg. 192.168.1.1
     */
    public function getDecadic(): string
    {
        return $this->decadic;
    }

    /**
     * @return int 32bit integer number of address eg. 3232235777
     */
    public function getInteger(): int
    {
        return $this->integer;
    }

    /**
     * Set address in binary format
     */
    protected function setBinary(): void
    {
        $outputIP = "";
        foreach ($this->getAddressArray() as $item) {
            $IPbin = decbin((int)$item);
            $outputIP .= str_pad($IPbin, self::OCTET_LEN, "0", STR_PAD_LEFT);
        }
        $this->binary = $outputIP;
    }

    /**
     * @param string address in dec format eg. 192.168.1.1
     */
    protected function setDecadic(string $addressDec): self
    {
        $this->decadic = $addressDec;
        return $this;
    }

    /**
     * Convert IP address from binary to integer
     */
    protected function setInteger(): self
    {
        $this->integer = bindec($this->getBinary());
        return $this;
    }

    /**
     * @return self
     * Based on decadic format of IP address create array of octet
     */
    protected function setAddressArray(): self
    {
        $ipAdd = explode('.', $this->decadic);
        $this->addressArray = $ipAdd;
        return $this;
    }

    public function __toString()
    {
        return $this->getDecadic();
    }


}
