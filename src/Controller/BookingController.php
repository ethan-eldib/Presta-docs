<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends AbstractController
{
    /**
     * @Route("/rendez-vous", name="booking_new")
     * @IsGranted("ROLE_USER")
     */
    public function book(Request $request, EntityManagerInterface $manager, BookingRepository $calendar): Response
    {
        $events = $calendar->findAll();
        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'startDate' => $event->getStartDate()->format('Y-m-d H:i:s'),
                'startTime' => $event->getStartTime()->format('H:i:s'),
                'endTime' => $event->getEndTime()->format('H:i:s')
            ];
        }

        $data = json_encode($rdvs);

        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $booking->setBooker($user);

            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('booking_show', ['id' => $booking->getId()]);
        }

        return $this->render('booking/book.html.twig', [
            'form' => $form->createView(),
            'data' => $data
        ]);
    }

    /**
     * Affiche la page de confirmation d'un rendez-vous
     * 
     * @Route("/rendez-vous/{id}", name="booking_show")
     *
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking)
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);
    }
}
