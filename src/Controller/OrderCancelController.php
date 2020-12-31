<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/commande/erreur/{stripeSessionId}", name="order_cancel")
     */
    public function index($stripeSessionId): Response
    {

        $order = $this->manager->getRepository(Order::class)->findOneBy([
            'stripeSessionId' => $stripeSessionId
        ]);

        if (!$order || $order->getUser() != $this->getUser() ) {
            return $this->redirectToRoute('homepage');
        }

        // Envoyer un email pour informer l'utilisateur de l'echec de la transaction

        return $this->render('order_cancel/index.html.twig', [
            'order' => $order
        ]);
    }
}
