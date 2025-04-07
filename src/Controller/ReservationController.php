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
        /** @var \App\Entity\User $user */

        $user = $this->getUser();

        if ($user) {
            // Récupérer toutes les réservations de l'utilisateur
            $reservations = $reservationRepository->findByUser($user);

            return $this->render('reservation/index.html.twig', [
                'reservations' => $reservations,
            ]);
        }

        // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
        return $this->redirectToRoute('app_login');
    }

    #[Route('/clear', name: 'app_reservation_clear', methods: ['GET', 'POST'])]
    public function clear(SessionInterface $session): Response
    {
        $session->remove('reservation_date');
        $session->remove('reservation_time');

        /** @var \App\Entity\User $user */

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->redirectToRoute('app_main');
    }


    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();

        /** @var \App\Entity\User $user */

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $step = null;

        if ($session->get('reservation_date')) {
            $step = 2;
        }

        if ($session->get('reservation_time')) {
            $step = 3;
        }

        if ($step === null) {
            $form = $this->createForm(ReservationType1::class, $reservation);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {


                $date = $reservation->getDate(); // Cela peut être une instance de \DateTime
               
                if ($date instanceof \DateTime) {
                    $reservation->setDate(\DateTimeImmutable::createFromMutable($date));
                }
                $session->set('reservation_date', $reservation->getDate());

                return $this->redirectToRoute('app_reservation_new');
            }

            return $this->render('reservation/step1.html.twig', [
                'form' => $form->createView(),
                'user' => $user,
            ]);
        }


        if ($step === 2) {
            $form = $this->createForm(ReservationType2::class, $reservation);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            
                // Sauvegarder l'heure d'arrivée dans la session
                $session->set('reservation_time', $reservation->getArrivalTime());
               

                return $this->redirectToRoute('app_reservation_new');
            }

            return $this->render('reservation/step2.html.twig', [
                'form' => $form->createView(),
                'user' => $user,
            ]);
        }


        if ($step === 3) {
            $reservationTime = $session->get('reservation_time');
            $startTimeSoir = \DateTime::createFromFormat('H:i', '19:00');
            $endTimeSoir = \DateTime::createFromFormat('H:i', '20:45');
            $startTime = \DateTime::createFromFormat('H:i', '12:00');
            $endTime = \DateTime::createFromFormat('H:i', '13:45');
            if ($reservationTime >= $startTimeSoir && $reservationTime <= $endTimeSoir) {
                $reservations = $entityManager->getRepository(Reservation::class)
                    ->createQueryBuilder('r')
                    ->where('r.date = :reservationDate')
                    ->andWhere('r.arrivalTime BETWEEN :startTimeSoir AND :endTimeSoir')
                    ->setParameter('reservationDate', $session->get('reservation_date'))
                    ->setParameter('startTimeSoir', $startTimeSoir->format('H:i:s'))
                    ->setParameter('endTimeSoir', $endTimeSoir->format('H:i:s'))
                    ->getQuery()
                    ->getResult();
            } else {
                $reservations = $entityManager->getRepository(Reservation::class)
                    ->createQueryBuilder('r')
                    ->where('r.date = :reservationDate')
                    ->andWhere('r.arrivalTime BETWEEN :startTime AND :endTime')
                    ->setParameter('reservationDate', $session->get('reservation_date'))
                    ->setParameter('startTime', $startTime->format('H:i:s'))
                    ->setParameter('endTime', $endTime->format('H:i:s'))
                    ->getQuery()
                    ->getResult();
            }

            // récupérer toutes les tables disponibles
            $tables = $entityManager->getRepository(Tables::class)->findBy(['blocked' => false]);

            // on récupère les tables qui sont déjà prises sur ce créneau
            $takenTableIds = array_map(fn($reservation) => $reservation->getTable()->getId(), $reservations);

            // on les filtres afin de ne récupérer que celles disponibles
            $tables = array_filter($tables, fn($table) => !in_array($table->getId(), $takenTableIds));


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

            $form = $this->createForm(ReservationType3::class, $reservation, [
                'tables' => $uniqueTables,
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $reservation->setUser($user);
                $reservation->setDate($session->get('reservation_date'));
                $reservation->setArrivalTime($session->get('reservation_time'));
                $selectedTables = $form->get('table')->getData();
                $phoneNumber = $form->get('phoneNumber')->getData();
                $reservation->setTable($selectedTables);
                $reservation->setPhoneNumber($phoneNumber);
                $entityManager->persist($reservation);
                $entityManager->flush();

                $session->clear();  // Réinitialiser la session

                return $this->redirectToRoute('app_user_edit', [
                    'id' => $user->getId(),
                ], Response::HTTP_SEE_OTHER);
            }

            return $this->render('reservation/step3.html.twig', [
                'form' => $form->createView(),
                'user' => $user,
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
