<?php


namespace App\Entity\IP;

use InvalidArgumentException;

class Ipv4address
{
    private const MIN_INTEGER_VALUE = 1;
    private const MAX_INTEGER_VALUE = 4294967296;
    private const OCTET_VALUE = 8;

    /** @var string Address in binary eg. 11000000101010000000000100000001 */
    private string $binary;
    /** @var string Human readable address eg. 192.168.1.1 */
    private string $decadic;
    /** @var int Address in Integer eg. 3232235777 */
    private int $integer;
    /** @var array IP address as array  eg. [10,0,0,1] */
    private array $addressArray;

    /**
     * @param string $address IPv4 address
     */
    public function __construct(string $address = "10.0.0.1")
    {
        if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->decadic = $address;
            $this->setAddressArray($address)
                ->setBinary()
                ->setInteger();
        } else {
            throw new InvalidArgumentException("IPv4 is not in correct form");
        }
    }


    /**
     * @return self
     * Set address in binary format
     */
    protected function setBinary(): self
    {
        $outputIP = "";
        foreach ($this->addressArray as $item) {
            $IPbin = decbin((int)$item);
            $outputIP .= str_pad($IPbin, self::OCTET_VALUE, "0", STR_PAD_LEFT);
        }
        $this->binary = $outputIP;
        return $this;
    }


    /**
     * Convert IP address from binary to integer
     */
    protected function setInteger(): void
    {
        $this->integer = bindec($this->getBinary());
    }

    /**
     * Calculate new IPv4 address based on current IP plus count of IP
     * @param int $addNumber count of IP
     * @return Ipv4address new object with result
     */
    public function add(int $addNumber): Ipv4address
    {
        if (filter_var($addNumber, FILTER_VALIDATE_INT, array(
            "options" => array(
                "min_range" => self::MIN_INTEGER_VALUE,
                "max_range" => self::MAX_INTEGER_VALUE,
            )))) {
            $addNumber_local = $this->getInteger() + $addNumber;

            if ($addNumber_local < self::MAX_INTEGER_VALUE) {
                return Ipv4address::binToDec(str_pad(decbin($addNumber_local), 32, "0", STR_PAD_LEFT));
            } else {
                throw new InvalidArgumentException("Result is out of range");
            }
        } else {
            throw new InvalidArgumentException("Input integer is out ouf range");
        }
    }

    /**
     * Convert IP from BIN to DEC
     * BIN=11000000101010000000000100000001 to 192.168.1.1
     * @param string $inputIP IPv4Address in binary format
     * @return Ipv4address New object of Ipv4address
     */
    public static function binToDec(string $inputIP): Ipv4address
    {       // input must be only in binary format and length must in range 1 - 32
        if (filter_var($inputIP, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => '/[0,1]/'))) and filter_var(strlen($inputIP), FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 32)))): $IpArray = str_split($inputIP, self::OCTET_VALUE);
            $ipOutput = "";
            foreach ($IpArray as $item): $ipOutput .= bindec($item) . "."; endforeach;
            return new Ipv4address(substr($ipOutput, 0, -1));
        else:
            throw new InvalidArgumentException("Input is not in valid format");
        endif;
    }

    /**
     * @param string $address
     * @return self
     */
    private function setAddressArray(string $address): self
    {
        $addressArray = explode('.', $address);
        foreach ($addressArray as $octet) {
            $this->addressArray[] = $octet;
        }
        return $this;
    }

    /**
     * @return string Binary format address eg. 11000000101010000000000100000001
     */
    public function getBinary(): string
    {
        return $this->binary;
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

}


