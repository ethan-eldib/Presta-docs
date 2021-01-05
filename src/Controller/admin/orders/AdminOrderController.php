<?php

namespace App\Controller\admin\orders;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    /**
     * @Route("/admin/commandes", name="admin_order")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orderRepository->findAll()
        ]);
    }
}
