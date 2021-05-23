<?php

namespace App\Tests\Services;

use App\Entity\IP\v4\Ipv4subnet;
use App\Services\Ipv4subnetService;
use PHPUnit\Framework\TestCase;

class Ipv4subnetServiceTest extends TestCase
{
    public function testPrepareJsonResponse()
    {

        $ipv4subnet = new Ipv4subnet('172.20.30.0/21');
        $ipv4subnetService = new Ipv4subnetService();

        $response = $ipv4subnetService->prepareJsonResponse($ipv4subnet);

        $this->assertCount(9, $response);
        $this->assertEquals('Network subnet:', $response['network-subnet']['key']);
        $this->assertEquals('172.20.24.0/21', $response['network-subnet']['value']);
        $this->assertEquals('Netmask:', $response['netmask']['key']);
        $this->assertEquals('255.255.248.0', $response['netmask']['value']);
        $this->assertEquals('172.20.31.254', $response['last-address']['value']);
        $this->assertEquals(2046, $response['number-of-usable-address']['value']);
        $this->assertEquals("172.20.24.1/21", $response['nsx-cidr']['value']);
    }
}
