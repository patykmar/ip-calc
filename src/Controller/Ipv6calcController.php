<?php

namespace App\Controller;

use App\Entity\IP\v6\Ipv6subnet;
use App\Services\Ipv6subnetService;
use InvalidArgumentException;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ipv6calcController extends AbstractController
{

    private const ACCESS_CONTROL_ALLOW_ORIGIN = '*';

    /**
     * @var Ipv6subnetService
     */
    private Ipv6subnetService $ipv6subnetService;

    /**
     * @var Logger
     */
    private Logger $logger;


    /**
     * Ipv6calcController constructor.
     * @param Ipv6subnetService $ipv6subnetService
     * @param Logger $logger
     */
    public function __construct(Ipv6subnetService $ipv6subnetService, Logger $logger)
    {
        $this->ipv6subnetService = $ipv6subnetService;
        $this->logger = $logger;
    }

    /**
     * @Route("/ipv6calc/{ip}", name="ipv6calc", requirements={"ip"=".+"}, defaults={"abc::/64"})
     * @param string $ip
     * @return Response
     */
    public function versionSixCalc(string $ip = "abc::/64"): Response
    {
        try {
            $ipv6Subnet = new Ipv6subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());
            $ipv6Subnet = new Ipv6subnet("abc::/64");
        }

        return $this->render('ipcalc/ipv6.html.twig', [
            'subnet' => $ipv6Subnet,
        ]);
    }

    /**
     * @Route("/api/ipv6calc/{ip}", name="apiIpv6calc", requirements={"ip"=".+"}, defaults={"abc::/64"})
     * @param string $ip
     * @return Response
     */
    public function apiVersionSixCalc(string $ip = "abc::/64")
    {
        try {
            $ipv6Subnet = new Ipv6subnet($ip);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());
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
