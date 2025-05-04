<?php

namespace App\Controller;

use App\Domain\Ip\v4\Ipv4subnet;
use App\Services\Ipv4subnetService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ipv4calcController extends AbstractController
{
    /** @var int how many smaller subnets will be calculate */
    private const int DEEP_INDEX = 4;
    private const string ACCESS_CONTROL_ALLOW_ORIGIN = '*';

    public function __construct(
        private readonly Ipv4subnetService $ipv4subnetService
    )
    {
    }

    #[Route("ipv4calc/{ip}", name: "ipv4calc", requirements: ["ip" => ".+"], defaults: ["192.168.1.0/24"])]
    public function versionFourCalc(string $ip = "192.168.1.0/24"): Response
    {
        try {
            $ipv4Subnet = new Ipv4subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $ipv4Subnet = new Ipv4subnet("192.168.2.0/24");
        }

        $subnetsArray = $this->ipv4subnetService->getSmallerSubnet(
            $ipv4Subnet,
            self::DEEP_INDEX
        );

        return $this->render('ipcalc/ipv4.html.twig', [
            'subnet' => $ipv4Subnet,
            'smallerSubnets' => $subnetsArray,
        ]);
    }

    #[Route("api/ipv4calc/{ip}", name: "apiIpv4calc", requirements: ["ip" => ".+"], defaults: ["192.168.1.0/24"])]
    public function apiVersionFourCalc(string $ip = "172.10.20.0/24"): Response
    {
        try {
            $ipv4Subnet = new Ipv4subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $ipv4Subnet = new Ipv4subnet("192.168.2.0/24");
        }

        $jsonResponse = $this->json(
            $this->ipv4subnetService->prepareJsonResponse(
                $ipv4Subnet
            ));

        // allow access from origins
        $jsonResponse->headers->set('Access-Control-Allow-Origin', self::ACCESS_CONTROL_ALLOW_ORIGIN);

        return $jsonResponse;
    }

    #[Route("api/ipv4/smaller-subnets/{enteredSubnet}", name: "apiIpv4SmallerSubnet", requirements: ["enteredSubnet" => ".+"], defaults: ["10.20.30.0/24"])]
    public function apiSmallerSubnet(string $enteredSubnet = "10.20.30.0/24"): Response
    {
        try {
            $ipv4Subnet = new Ipv4subnet($enteredSubnet);
        } catch (InvalidArgumentException $exception) {
            $ipv4Subnet = new Ipv4subnet("11.22.33.0/24");
        }

        $smallerSubnets = $this->ipv4subnetService->getSmallerSubnet(
            $ipv4Subnet,
            self::DEEP_INDEX
        );

        $jsonResponse = $this->json(
            $this->ipv4subnetService->smallerSubnetJsonResponse(
                $smallerSubnets
            ));

        // allow access from all origin
        $jsonResponse->headers->set('Access-Control-Allow-Origin', self::ACCESS_CONTROL_ALLOW_ORIGIN);

        return $jsonResponse;
    }


}
