<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentFormType;
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
     * @Route("/appointment/create", name="appointment_create", methods={"GET","POST"})
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
     * @Route("/appointment/toggleStatus", name="appointment_update", methods={"PUT"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function toggleStatusAction(Request $request)
    {
        $appointment = $this->appointmentRepository->find($request->get('id'));
        if ($appointment != null) {
            $curStatus = $appointment->getComplete();
            $appointment->setComplete(!$curStatus);
            $this->entityManager->persist($appointment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Der Termin wurde erfolgreich ' . ($curStatus ? 'zurückgesetzt' : 'beendet') . '.');
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/appointment/delete", name="appointment_delete", methods={"DELETE"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $appointment = $this->appointmentRepository->find($request->get('id'));
        if ($appointment != null) {
            $this->entityManager->remove($appointment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Der Termin wurde erfolgreich gelöscht.');
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/appointment", name="appointment_list", methods={"GET"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        $assignee = $this->tokenStorage->getToken()->getUser();
        $appointments = $this->appointmentRepository->findByFilter($assignee, $request->get('customer'), $request->get('fromDate'), $request->get('toDate'), $request->get('status'));
        $customers = $this->appointmentRepository->findCustomers($assignee);
        return $this->render('appointment/index.html.twig', [
            'customers' => $customers,
            'appointments' => $appointments
        ]);
    }
}
