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

        $this->assertEquals(9, count($response));
    }
}
