<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PackPrestaDocController extends AbstractController
{
    /**
     * @Route("/pack-presta-doc", name="pack_doc")
     */
    public function index(): Response
    {
        return $this->render('pack_presta_doc/index.html.twig');
    }
}
