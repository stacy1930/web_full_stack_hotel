<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use App\Repository\CustomerRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("admin/booking", name="booking")
     */
    public function index(Request $request, BookingRepository $bookingRepository, CustomerRepository $customerRepository): Response
    {

        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        // $booking->setCreatedAt(new DateTime());
        if ($form->isSubmitted() && $form->isValid()) {

            $check = $bookingRepository->checkDispo($booking);

            if ($check) {
                // check Cusomer if exist
                $customer = $customerRepository->findOneBy([
                    'email' => $booking->getCustomer()->getEmail(),
                ]);
                // si le customer existe on recup
                if ($customer) {
                    $booking->setCustomer($customer);
                }
                // save
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($booking);
                $manager->flush();

                $this->addFlash('success', 'Le booking a bien été créé');

                return $this->redirectToRoute('default');
            } else {
                $this->addFlash('danger', 'Les dates ne sont pas disponibles');
            }
        }

        return $this->render('booking/booking.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
