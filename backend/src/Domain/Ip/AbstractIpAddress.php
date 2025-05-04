<?php

namespace App\Domain\Ip;

abstract class AbstractIpAddress implements IpAddress
{
    /** @var string Address in binary eg. 11000000101010000000000100000001 */
    protected string $binary;

    /** @var array<string> format of IP eg. [192,168,0,1] */
    protected array $addressArray = array();

    public function getBinary(): string
    {
        return $this->binary;
    }

    /**
     * @return array<string>
     */
    public function getAddressArray(): array
    {
        return $this->addressArray;
    }

    protected abstract function setAddressArray(): self;

    protected abstract function setBinary(): self;
}