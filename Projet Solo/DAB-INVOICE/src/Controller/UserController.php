<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\ApiMessages;
use App\Service\Profile\View\ProfileDto;
use App\Service\Profile\View\ProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/dashboard', name: 'user_dashboard')]
    final public function dashboard(
    ): Response
    {
        if ($this->getUser()->hasRole(User::ROLE_ADMIN)){
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('user/dashboard.html.twig', []);
    }

    #[Route('/user/avatar/{avatarName}', name: 'user_avatar', methods: "GET")]
    final public function getUserAvatar(string $avatarName): Response
    {
        $filePath =
            $this->getParameter('kernel.project_dir')
            . $this->getParameter('app.path.upload_dir')
            . $this->getParameter('app.path.user_avatar')
            . "/$avatarName";
        try {
            $response = new BinaryFileResponse($filePath);
        } catch (FileException $exception) {
            throw new NotFoundHttpException();
        }

        return $response;
    }

    #[Route('/profil', name: 'form_profile')]
    public function formProfile(): Response
    {
        return $this->render('user/profile.html.twig', [
            'profile' => ProfileService::fromUserToObject($this->getUser()),
        ]);
    }

    #[Route('/api/user/profile', name: 'api_user_profile_update', methods: ['PUT'])]
    public function updateProfile(
        Request             $request,
        ProfileService      $service,
        SerializerInterface $serializer
    ): Response
    {
        /** @var User $user */
        !($user = $this->getUser()) && throw new UnauthorizedHttpException($this->getParameter('app.baseurl'));

        $profile = $serializer->deserialize($request->getContent(), ProfileDto::class, 'json');
        $result = $service->updateUserProfile($user, $profile);

        return $this->json($result);
    }

    #[Route('/api/user/avatar', name: 'api_user_avatar_update', methods: ['POST'])]
    public function updateAvatar(
        Request                 $request,
        ProfileService          $service,
    ): Response
    {
        /** @var User $user */
        !($user = $this->getUser()) && throw new UnauthorizedHttpException($this->getParameter('app.baseurl'));

        $avatarFile = $request->files->get("avatarFile");
        $avatarDelete = $request->request->get("avatarDelete");

        $result = $service->updateUserAvatar($user, $avatarFile, $avatarDelete);

        return $this->json($result);
    }

    #[Route('/utilisateur/{id}', name: 'user_card')]
    final public function userCard(
        ?User                       $user,
    ): Response
    {
        ($isNull = ($user === null))
        && $this->addFlash(ApiMessages::STATUS_WARNING, "Cet utilisateur n'existe pas");

        return $isNull ? $this->redirectToRoute('admin_user_list')
            : $this->render('user/userShow.html.twig', [
                'user' => $user,
            ]);
    }
}
