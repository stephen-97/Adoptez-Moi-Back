<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\JsonWebTokenService;
use App\Service\MailerService;
use App\Service\UserService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    private $entityManager;
    private $mailerService;
    private $userRepository;
    private $userService;
    private $jwtService;

    public function __construct(EntityManagerInterface $entityManager, MailerService $mailerService, UserRepository $userRepository, UserService $userService, JsonWebTokenService $jwtService)
    {
        $this->entityManagerService = $entityManager;
        $this->mailerService = $mailerService;
        $this->userRepositoryService = $userRepository;
        $this->userService = $userService;
        $this->jwtService= $jwtService;
    }


    #[Route('/registration/new', name: 'new_account', methods: ['POST'])]
    public function new(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $username = $request->request->get('username');

        if(!$this->userService->emailIsCorrect($email))
            return new JsonResponse(['message' => "Adresse mail incorrect", 'status' => 400]);
        if(!$this->userService->passwordIsCorrect($password))
            return new JsonResponse(['message' => "mot de passe incorrect", 'status' => 400]);
        if($this->userService->accountIsNotEnabledYet($email))
            return new JsonResponse(['message' => "Ce compte est en attente de vérification", 'status' => 409]);
        if($this->userService->emailIsAlreadyTaken($email))
            return new JsonResponse(['message' => "Cet email est déjà utilisé", 'status' => 409]);
        if($this->userService->usernameIsAlreadyTaken($username))
            return new JsonResponse(['message' => "Ce pseudo est déjà utilisé", 'status' => 409]);

        $user = new User();
        $user->setRoles([]);
        $user->setEmail($email);
        $user->setUsername($username);
        $encoded = $hasher->hashPassword($user, $password);
        $user->setPassword($encoded);
        $payload = ["exp"  => (new \DateTime())->modify("+1 day")->getTimestamp()];
        $activationToken = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        $user->setActivationToken($activationToken);

        try{
            $this->mailerService->sendEmail($user->getEmail(), $user->getActivationToken());
        } catch (TransportException $e){
            return new JsonResponse(['message' => "Envoie du mail échoué", 'status' => 404]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse(['message' => "Un email vous a été envoyé pour confirmer votre compte", 'status' => 200]);
    }

    #[Route('/activation/{token}', name: 'confirm_account', methods: ['POST'])]
    public function activation($token)
    {
        $this->userService->deleteALlUsersWithDeprecatedToken();
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\User');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $repository->findOneBy(['activation_token' => $token ]);

        if(!$user){
            return $this->render('login_authentification/activation_error.html.twig');
        } else if($this->jwtService->tokenActivationHasExpired($user->getActivationToken())){
            $entityManager->remove($user);
            $entityManager->flush();
            return $this->render('login_authentification/activation_error.html.twig');
        }

        $user->setActivationToken(null);
        $user->setEnabled(true);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('message', 'Utilisateur activé avec succès');

        return $this->render('login_authentification/activation_succes.html.twig');
    }

}
