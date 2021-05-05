<?php

namespace App\Controller;

use App\Entity\IP\Ipv4address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    /**
     * @Route("/info", name="info")
     */
    public function index(): Response
    {
        $ipv4 = new Ipv4address();
        dd($ipv4);


        return $this->render('info/index.html.twig', [
            'controller_name' => 'InfoController',
        ]);
    }
}
