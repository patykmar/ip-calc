<?php
declare(strict_types=1);

namespace App\Tests\Domain\IP\v4;

use App\Domain\Ip\v4\Ipv4netmask;
use PHPUnit\Framework\TestCase;

final class Ipv4netmaskTest extends TestCase
{

    public function testBiggestSubnet(): void
    {
        $ipv4netmask = new Ipv4netmask(1);
        $this->assertEquals("128.0.0.0", $ipv4netmask->getDecadic());
        $this->assertEquals("10000000000000000000000000000000", $ipv4netmask->getBinary());
        $this->assertEquals(32, strlen($ipv4netmask->getBinary()));
        $this->assertEquals("01111111111111111111111111111111", $ipv4netmask->getWildcard());
        $this->assertEquals(32, strlen($ipv4netmask->getWildcard()));
        $this->assertEquals(1, $ipv4netmask->getCidr());
        unset($ipv4netmask);
    }

    public function testASubnet(): void
    {
        $ipv4netmask = new Ipv4netmask(8);
        $this->assertEquals("255.0.0.0", $ipv4netmask->getDecadic());
        $this->assertEquals("11111111000000000000000000000000", $ipv4netmask->getBinary());
        $this->assertEquals(32, strlen($ipv4netmask->getBinary()));
        $this->assertEquals("00000000111111111111111111111111", $ipv4netmask->getWildcard());
        $this->assertEquals(32, strlen($ipv4netmask->getWildcard()));
        $this->assertEquals(8, $ipv4netmask->getCidr());
        unset($ipv4netmask);
    }

    public function testBSubnet(): void
    {
        $ipv4netmask = new Ipv4netmask(16);
        $this->assertEquals("255.255.0.0", $ipv4netmask->getDecadic());
        $this->assertEquals("11111111111111110000000000000000", $ipv4netmask->getBinary());
        $this->assertEquals(32, strlen($ipv4netmask->getBinary()));
        $this->assertEquals("00000000000000001111111111111111", $ipv4netmask->getWildcard());
        $this->assertEquals(32, strlen($ipv4netmask->getWildcard()));
        $this->assertEquals(16, $ipv4netmask->getCidr());
        unset($ipv4netmask);
    }

    public function testCSubnet(): void
    {
        $ipv4netmask = new Ipv4netmask();
        $this->assertEquals("255.255.255.0", $ipv4netmask->getDecadic());
        $this->assertEquals("11111111111111111111111100000000", $ipv4netmask->getBinary());
        $this->assertEquals(32, strlen($ipv4netmask->getBinary()));
        $this->assertEquals("00000000000000000000000011111111", $ipv4netmask->getWildcard());
        $this->assertEquals(32, strlen($ipv4netmask->getWildcard()));
        $this->assertEquals(24, $ipv4netmask->getCidr());
        unset($ipv4netmask);
    }

    public function testSmallestSubnet(): void
    {
        $ipv4netmask = new Ipv4netmask(32);
        $this->assertEquals("255.255.255.255", $ipv4netmask->getDecadic());
        $this->assertEquals("11111111111111111111111111111111", $ipv4netmask->getBinary());
        $this->assertEquals(32, strlen($ipv4netmask->getBinary()));
        $this->assertEquals("00000000000000000000000000000000", $ipv4netmask->getWildcard());
        $this->assertEquals(32, strlen($ipv4netmask->getWildcard()));
        $this->assertEquals(32, $ipv4netmask->getCidr());
        unset($ipv4netmask);
    }

}
