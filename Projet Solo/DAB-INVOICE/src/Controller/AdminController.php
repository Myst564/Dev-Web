<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User\RegisterUser;
use App\Service\User\UserActivate;
use App\Service\User\UserArchive;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    final public function adminDashboard(): Response
    {
        return $this->render('admin/adminDashboard.html.twig',[]);
    }

    #[Route('/admin/utilisateurs/lister', name: 'admin_user_list')]
    final public function adminUserList(UserRepository $repository): Response
    {
        $loggedInUserId = $this->getUser()->getId();
        $users = $repository->findAllActiveUsersExceptMe($loggedInUserId);

        return $this->render('user/userListing.html.twig', [
            'title' => 'Liste des utilisateurs',
            'users' => $users
        ]);
    }

    #[Route('/admin/utilisateurs/archive/lister', name: 'admin_user_archive_list')]
    final public function archiveList(UserRepository $repository): Response
    {
        $users = $repository->findAllNotActiveUser();

        return $this->render('user/userListing.html.twig', [
            'title' => 'Archive des utilisateurs',
            'archived' => true,
            'users' => $users
        ]);
    }

    #[Route('/api/admin/utilisateurs/archive', name: 'admin_user_archive', methods: "PUT")]
    final public function userArchive(
        Request     $request,
        UserArchive $userArchive,
    ): JsonResponse
    {
        return $this->json(
            $userArchive->process($request)
        );
    }

    #[Route('/api/admin/utilisateurs/activate', name: 'admin_user_activate', methods: "PUT")]
    final public function userActivate(
        Request      $request,
        UserActivate $userActivate,
    ): Response
    {
        return $this->json(
            $userActivate->process($request)
        );
    }

    #[Route('/admin/utilisateurs/ajouter', name: 'admin_user_add')]
    #[Route('/admin/utilisateurs/editer/{id}', name: 'admin_user_edit')]
    final public function adminPersistUser(?User $user, Request $request, RegisterUser $register): Response
    {
        return $register->persist($user, $request);
    }
}
