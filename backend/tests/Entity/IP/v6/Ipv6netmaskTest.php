<?php

namespace App\Tests\Entity\IP\v6;

use App\Entity\IP\v6\Ipv6netmask;
use PHPUnit\Framework\TestCase;

class Ipv6netmaskTest extends TestCase
{

    public function testNoParameter(): void
    {
        // default value is 64
        $ipv6netmask = new Ipv6netmask();
        $this->assertEquals("ffff:ffff:ffff:ffff:0:0:0:0", $ipv6netmask->getHexa());
        $this->assertEquals(
            "11111111111111111111111111111111111111111111111111111111111111110000000000000000000000000000000000000000000000000000000000000000",
            $ipv6netmask->getBinary()
        );
        $this->assertEquals(
            "00000000000000000000000000000000000000000000000000000000000000001111111111111111111111111111111111111111111111111111111111111111",
            $ipv6netmask->getWildcard()
        );
        $this->assertEquals(128, strlen($ipv6netmask->getBinary()));
        $this->assertCount(8, $ipv6netmask->getAddressArray());
        $this->assertEquals("ffff", $ipv6netmask->getAddressArray()[0]);
        $this->assertEquals("ffff", $ipv6netmask->getAddressArray()[1]);
        $this->assertEquals("0", $ipv6netmask->getAddressArray()[7]);
    }

    public function testBigestValueOfParameter(): void
    {
        $ipv6netmask = new Ipv6netmask(128);
        $this->assertEquals("ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff", $ipv6netmask->getHexa());
        $this->assertEquals(
            "11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111",
            $ipv6netmask->getBinary()
        );
        $this->assertEquals(
            "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000",
            $ipv6netmask->getWildcard()
        );
        $this->assertEquals(128, strlen($ipv6netmask->getBinary()));
        $this->assertCount(8, $ipv6netmask->getAddressArray());
        $this->assertEquals("ffff", $ipv6netmask->getAddressArray()[0]);
        $this->assertEquals("ffff", $ipv6netmask->getAddressArray()[1]);
        $this->assertEquals("ffff", $ipv6netmask->getAddressArray()[7]);
    }

    public function testLowestValueOfParameter(): void
    {
        $ipv6netmask = new Ipv6netmask(1);
        $this->assertEquals("8000:0:0:0:0:0:0:0", $ipv6netmask->getHexa());
        $this->assertEquals(
            "10000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000",
            $ipv6netmask->getBinary()
        );
        $this->assertEquals(
            "01111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111",
            $ipv6netmask->getWildcard()
        );
        $this->assertEquals(128, strlen($ipv6netmask->getBinary()));
        $this->assertCount(8, $ipv6netmask->getAddressArray());
        $this->assertEquals("8000", $ipv6netmask->getAddressArray()[0]);
        $this->assertEquals("0", $ipv6netmask->getAddressArray()[1]);
        $this->assertEquals("0", $ipv6netmask->getAddressArray()[7]);
    }

}
