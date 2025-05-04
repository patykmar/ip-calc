<?php
declare(strict_types=1);

namespace App\Tests\Domain\IP\v4;

use App\Domain\Ip\v4\Ipv4subnet;
use PHPUnit\Framework\TestCase;

final class Ipv4subnetTest extends TestCase
{

    public function testRegularSubnet(): void
    {
        $ipSubnet = new Ipv4subnet("192.168.168.0/24");
        $this->assertEquals("192.168.168.0", $ipSubnet->ipv4AddressNetwork);
        $this->assertEquals("255.255.255.0", $ipSubnet->ipv4Netmask->getDecadic());
        $this->assertEquals(24, $ipSubnet->ipv4Netmask->getCidr());
        $this->assertEquals("192.168.168.1", $ipSubnet->ipv4FirstAddress);
        $this->assertEquals("192.168.168.2", $ipSubnet->ipv4SecondAddress);
        $this->assertEquals("192.168.168.254", $ipSubnet->ipv4LastAddress);
        $this->assertEquals("192.168.168.255", $ipSubnet->ipv4AddressBroadcast);
        unset($ipSubnet);
    }

    public function testSmallSubnet(): void
    {
        $ipSubnet = new Ipv4subnet("192.168.168.0/30");
        $this->assertEquals("192.168.168.0", $ipSubnet->ipv4AddressNetwork);
        $this->assertEquals("255.255.255.252", $ipSubnet->ipv4Netmask->getDecadic());
        $this->assertEquals(30, $ipSubnet->ipv4Netmask->getCidr());
        $this->assertEquals("192.168.168.1", $ipSubnet->ipv4FirstAddress);
        $this->assertEquals("192.168.168.2", $ipSubnet->ipv4SecondAddress);
        $this->assertEquals("192.168.168.2", $ipSubnet->ipv4LastAddress);
        $this->assertEquals("192.168.168.3", $ipSubnet->ipv4AddressBroadcast);
        unset($ipSubnet);
    }

    public function testHost(): void
    {
        $ipSubnet = new Ipv4subnet("192.168.168.0/32");
        $this->assertEquals("192.168.168.0", $ipSubnet->ipv4AddressNetwork);
        $this->assertEquals("255.255.255.255", $ipSubnet->ipv4Netmask->getDecadic());
        $this->assertEquals(32, $ipSubnet->ipv4Netmask->getCidr());
        $this->assertEquals("192.168.168.0", $ipSubnet->ipv4FirstAddress);
        $this->assertEquals("192.168.168.0", $ipSubnet->ipv4SecondAddress);
        $this->assertEquals("192.168.168.0", $ipSubnet->ipv4LastAddress);
        $this->assertEquals("192.168.168.0", $ipSubnet->ipv4AddressBroadcast);
        unset($ipSubnet);
    }

    public function testFullRange(): void
    {
        $ipSubnet = new Ipv4subnet("0.0.0.0/1");
        $this->assertEquals("0.0.0.0", $ipSubnet->ipv4AddressNetwork);
        $this->assertEquals("128.0.0.0", $ipSubnet->ipv4Netmask->getDecadic());
        $this->assertEquals(1, $ipSubnet->ipv4Netmask->getCidr());
        $this->assertEquals("0.0.0.1", $ipSubnet->ipv4FirstAddress);
        $this->assertEquals("0.0.0.2", $ipSubnet->ipv4SecondAddress);
        $this->assertEquals("127.255.255.254", $ipSubnet->ipv4LastAddress);
        $this->assertEquals("127.255.255.255", $ipSubnet->ipv4AddressBroadcast);
        unset($ipSubnet);
    }


}
