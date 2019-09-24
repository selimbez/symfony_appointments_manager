<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentFormType;
use App\Repository\AppointmentRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AppointmentController
 * @package App\Controller
 */
class AppointmentController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $userRepository;

    /**
     * @var ObjectRepository
     */
    private $appointmentRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * AppointmentController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository("App:User");
        $this->appointmentRepository = $entityManager->getRepository("App:Appointment");
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/appointment/create", name="appointment_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAppointmentAction(Request $request)
    {
        $appointment = new Appointment();
        $appointment->setComplete(false);

        $form = $this->createForm(AppointmentFormType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($appointment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Der Termin wurde erfolgreich angelegt.');
            return $this->redirectToRoute('appointment_create');
        }

        return $this->render('appointment/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/appointment", name="appointment_list")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        return $this->render('appointment/index.html.twig', [
            'appointments' => $this->appointmentRepository->findBy(["assignee" => $this->tokenStorage->getToken()->getUser()])
        ]);
    }
}