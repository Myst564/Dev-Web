<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\UserChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    final public function home(UserChecker $checker): Response
    {
        $checker->checkAccessFromController(($user = $this->getUser()));

        $redirect = match (true) {
            $user->hasRole(User::ROLE_ADMIN) => 'admin_dashboard',
            default => 'user_dashboard',
        };

        return $this->redirectToRoute($redirect);
    }
}
