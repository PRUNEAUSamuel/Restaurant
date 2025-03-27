<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Tables;
use App\Form\ReservationType1;
use App\Form\ReservationType2;
use App\Form\ReservationType3;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/reservation', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();

        // Récupérer toutes les tables disponibles
        $tables = $entityManager->getRepository(Tables::class)->findAll();

        // Filtrer les doublons en utilisant le nombre de places comme clé
        $uniqueTables = [];
        foreach ($tables as $table) {
            $nbPlaces = $table->getNbPlaces();
            if (!isset($uniqueTables[$nbPlaces])) {
                // Ajouter la table uniquement si ce nombre de places n'a pas déjà été ajouté
                $uniqueTables[$nbPlaces] = $table;
            }
        }

        // Convertir le tableau associatif en un tableau d'objets
        $uniqueTables = array_values($uniqueTables);

        // Trier les tables par nombre de places
        usort($uniqueTables, function ($a, $b) {
            return $a->getNbPlaces() - $b->getNbPlaces();
        });

        $step = $request->get('step', 1);

        if ($step === 1) {
            $form = $this->createForm(ReservationType1::class, $reservation);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Sauvegarder la table dans la session
                $session->set('reservation_date', $reservation->getDate());
                return $this->redirectToRoute('app_reservation_new', ['step' => 2]);
            }

            return $this->render('reservation/step1.html.twig', [
                'form' => $form->createView(),
            ]);
        }


        if ($step === 2) {
            $form = $this->createForm(ReservationType2::class, $reservation);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Sauvegarder l'heure d'arrivée dans la session
                $session->set('reservation_time', $reservation->getArrivalTime());
                return $this->redirectToRoute('app_reservation_new', ['step' => 3]);
            }

            return $this->render('reservation/step2.html.twig', [
                'form' => $form->createView(),
            ]);
        }


        if ($step === 3) {
            $form = $this->createForm(ReservationType3::class, $reservation, [
                'tables' => $tables,
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $reservation->setdate($session->get('reservation_date'));
                $reservation->setArrivalTime($session->get('reservation_time'));
                // Sauvegarder la table dans la session
                $entityManager->persist($reservation);
                $entityManager->flush();

                $session->clear();  // Réinitialiser la session

                return $this->redirectToRoute('app_user_edit', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('reservation/step3.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('app_reservation_new');
    }



    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType1::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
