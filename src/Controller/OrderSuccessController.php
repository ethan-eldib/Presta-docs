<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Classes\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_success")
     */
    public function index($stripeSessionId, Cart $cart): Response
    {

        $order = $this->manager->getRepository(Order::class)->findOneBy([
            'stripeSessionId' => $stripeSessionId
        ]);

        if (!$order || $order->getUser() != $this->getUser() ) {
            return $this->redirectToRoute('homepage');
        }

        if (!$order->getIsPaid()) {
            // On vide la session Cart
            $cart->remove();

            // Passer le statut isPaid à true
            $order->setIsPaid(1);
            $this->manager->flush();

            // Envoyer un email au client pour la confirmation de la commande
            $content = "Bonjour". $order->getUser()->getFirstName()."<br/>Merci pour votre commande. <br/><br/>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia at praesentium eius quae dolorem quibusdam aliquid ipsum suscipit, est quos, id error debitis eligendi nostrum totam recusandae. Earum esse qui nemo, quas repellendus pariatur et. Eligendi, ratione deserunt! Cum dignissimos sint laboriosam esse? Vel, dolorem."; 
            $mail = new Mail();
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'Votre commande Presta-Doc est bien validée.', $content );
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
