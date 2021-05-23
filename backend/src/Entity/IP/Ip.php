<?php


namespace App\Entity\IP;


abstract class Ip
{
    /** @var string Address in binary eg. 11000000101010000000000100000001 */
    protected string $binary;

    /** @var array format of IP eg. [192,168,0,1] */
    protected array $addressArray = array();

    /** @return string Binary format address eg. 11000000101010000000000100000001 */
    public function getBinary(): string
    {
        return $this->binary;
    }

    /**
     * @return array
     */
    public function getAddressArray(): array
    {
        return $this->addressArray;
    }

    protected abstract function setAddressArray();

    protected abstract function setBinary();
}