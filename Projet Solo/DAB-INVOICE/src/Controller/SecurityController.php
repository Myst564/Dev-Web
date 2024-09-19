<?php

namespace App\Controller;

use App\Service\Profile\View\ProfileService;
use App\Service\Profile\View\PasswordDto;
use App\Service\Security\ConfirmationEmail;
use App\Service\Security\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class SecurityController extends AbstractController
{
    #[Route("/api/security/password", name: "api_security_password_update", methods: ['PUT'])]
    final public function updatePassword(
        Request             $request,
        ProfileService      $service,
        SerializerInterface $serializer,
    ): JsonResponse
    {
        ! ($user = $this->getUser())
        && throw new UnauthorizedHttpException($this->getParameter('app.baseurl'));

        $passwordDto = $serializer->deserialize(
            $request->getContent(),
            PasswordDto::class,
            JsonEncoder::FORMAT
        );
        $result = $service->updateUserPassword($user, $passwordDto);

        return $this->json($result);
    }

    #[Route('/security/confirmation-email', name: 'security_confirmation_email', methods: 'GET')]
    final public function securityConfirmationEmail(
        Request                $request,
        ConfirmationEmail      $confirmationEmail,
    ): Response
    {
        return $confirmationEmail->processConfirmationEmailRequest($request);
    }

    #[Route('/security/create-password', name: 'security_create_password')]
    final public function createPassword(Request $request, PasswordService $passwordService): Response
    {
        return $passwordService->processResetForm($request, $this->getUser(), true);
    }

    #[Route('/security/set-password', name: 'security_set_password')]
    final public function resetPassword(Request $request, PasswordService $passwordService): Response
    {
        return $passwordService->processResetForm($request, $this->getUser());
    }

    #[Route('/api/security/forgottenPassword', name: 'security_forgotten_password', methods: ['POST'])]
    final public function forgottenPassword(Request $request, PasswordService $passwordService): JsonResponse
    {
        return $this->json(
            $passwordService->processForgottentPasswordRequest($request)
        );
    }

    #[Route('/security/mailAccess', name: 'security_mail_access', methods: ['POST'])]
    final public function mailAccess(Request $request, ConfirmationEmail $confirmationEmail): JsonResponse
    {
        return $this->json(
            $confirmationEmail->processSendMailAccessRequest($request)
        );
    }
}
