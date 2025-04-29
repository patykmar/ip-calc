<?php

namespace App\Controller;

use App\Entity\IP\v6\Ipv6subnet;
use App\Services\Ipv6subnetService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ipv6calcController extends AbstractController
{
    private const string ACCESS_CONTROL_ALLOW_ORIGIN = '*';

    public function __construct(
        private readonly Ipv6subnetService $ipv6subnetService
    )
    {
    }

    #[Route("ipv6calc/{ip}", name: "ipv6calc", requirements: ["ip" => ".+"], defaults: ["abc::/64"])]
    public function versionSixCalc(string $ip = "abc::/64"): Response
    {
        try {
            $ipv6Subnet = new Ipv6subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $ipv6Subnet = new Ipv6subnet("abc::/64");
        }

        return $this->render('ipcalc/ipv6.html.twig', [
            'subnet' => $ipv6Subnet,
        ]);
    }

    #[Route("/api/ipv6calc/{ip}", name: "apiIpv6calc", requirements: ["ip" => ".+"], defaults: ["abc::/64"])]
    public function apiVersionSixCalc(string $ip = "abc::/64"): Response
    {
        try {
            $ipv6Subnet = new Ipv6subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $ipv6Subnet = new Ipv6subnet("abc::/64");
        }

        $jsonResponse = $this->json(
            $this->ipv6subnetService->prepareJsonResponse($ipv6Subnet)
        );

        // allow access from all origin
        $jsonResponse->headers->set(
            'Access-Control-Allow-Origin',
            self::ACCESS_CONTROL_ALLOW_ORIGIN
        );

        return $jsonResponse;
    }
}
