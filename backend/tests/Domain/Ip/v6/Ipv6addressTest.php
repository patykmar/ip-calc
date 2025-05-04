<?php

namespace App\Tests\Domain\IP\v6;

use App\Domain\Ip\v6\Ipv6address;
use PHPUnit\Framework\TestCase;

class Ipv6addressTest extends TestCase
{
    public function testIpAAddress()
    {
        // default value is 10.0.0.1
        $ipV6Address = new Ipv6address();
        $this->assertEquals("::1", $ipV6Address->getHexa());
        $this->assertEquals("00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001", $ipV6Address->getBinary());
        $this->assertEquals(128, strlen($ipV6Address->getBinary()));
        $this->assertCount(8, $ipV6Address->getAddressArray());
        $this->assertEquals("0000", $ipV6Address->getAddressArray()[0]);
        $this->assertEquals("0000", $ipV6Address->getAddressArray()[1]);
        $this->assertEquals("1", $ipV6Address->getAddressArray()[7]);
    }

    public function testIpAAddressWithParameter()
    {
        // default value is 10.0.0.1
        $ipV6Address = new Ipv6address("abc::");
        $this->assertEquals("abc::", $ipV6Address->getHexa());
        $this->assertEquals("00001010101111000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000", $ipV6Address->getBinary());
        $this->assertEquals(128, strlen($ipV6Address->getBinary()));
        $this->assertCount(8, $ipV6Address->getAddressArray());
        $this->assertEquals("abc", $ipV6Address->getAddressArray()[0]);
        $this->assertEquals("0000", $ipV6Address->getAddressArray()[1]);
        $this->assertEquals("0000", $ipV6Address->getAddressArray()[7]);
    }
}
