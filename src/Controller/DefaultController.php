<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="root")
     * @param TokenStorageInterface $tokenStorage
     * @return Response
     */
    public function index(TokenStorageInterface $tokenStorage)
    {
        if ($tokenStorage->getToken()->isAuthenticated()) {
            return $this->redirectToRoute(in_array(User::SECRETARY, $tokenStorage->getToken()->getRoleNames()) ? 'appointment_create' : 'appointment_list');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
