<?php

namespace App\Controller;


use App\Entity\User;
use App\Service\MessageNotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;
use App\Service\JsonWebTokenService;

class AuthenticationController extends AbstractController
{
    private $JWTService;

    public function __construct( JsonWebTokenService $JWTService)
    {
        $this->JWTService = $JWTService;
    }


    #[Route('/authentication', name: 'auth_account', methods: ['POST'])]
    public function auth(Request $request,  UserPasswordHasherInterface $hasher, MessageNotificationService $notificationService): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\User');

        $user = $repository->findOneBy(['email' => $email ]);
        if(empty($user)) {
            return new JsonResponse(['message' => "Email ou mot de passe incorrect", 'status' => 403]);
        }

        $passwordAreCorrect = $hasher->isPasswordValid($user, $password);
        if(!$passwordAreCorrect){
            return new JsonResponse(['message' => "Email ou mot de passe incorrect", 'status' => 403]);
        }
        if($user->getEnabled()===false){
            return new JsonResponse(['message' => "Ce compte est en attente de vérification sur la boite mail", 'status' => 409]);
        }

        $payload = [
            "user" => $user->getUsername(),
            "email" => $user->getEmail(),
            "avatar" => $user->getAvatar(),
            "role" => $user->getRoles(),
            "createdAt" => $user->getCreatedAt(),
            "newMessages" => $notificationService->haveNewCommentOrNot($user),
            "exp"  => (new \DateTime())->modify("+1 day")->getTimestamp(),
        ];
        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return new JsonResponse(['message' => "Connexion réussi", 'status' => 200, "tokenType"=> "Bearer", 'token' => sprintf('%s', $jwt)]);
    }

    #[Route('/authentication/refreshToken', name: 'auth_refreshToken')]
    public function refreshToken(Request $request,  UserPasswordHasherInterface $hasher): Response
    {
        $token = $request->headers->get('Authorization');
        if($this->JWTService->getUser($token) instanceof User){
            return new JsonResponse($this->JWTService->newToken($this->JWTService->getUser($token), $this->getParameter('jwt_secret')));
        }
        return $this->JWTService->getUser($token);
    }

}


