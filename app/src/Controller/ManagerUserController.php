<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FileUploaderService;
use App\Service\JsonWebTokenService;
use App\Service\MailerService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ManagerUserController extends AbstractController
{

    private $entityManagerService;
    private $mailerService;
    private $userRepositoryService;
    private $JWTService;

    public function __construct(EntityManagerInterface $entityManager, MailerService $mailerService, UserRepository $userRepository, JsonWebTokenService $JWTService)
    {
        $this->entityManager = $entityManager;
        $this->mailerService = $mailerService;
        $this->userRepository = $userRepository;
        $this->JWTService = $JWTService;
    }

    #[Route('/login/authentification/newPassword', name: 'newPassword_account')]
    public function newPassword(Request $request,  UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $email = $request->request->get('email');
        $currentPassword = $request->request->get('currentPassword');
        $newPassword = $request->request->get('newPassword');

        $token = $request->headers->get('Authorization');

        $theUser = $this->JWTService->getUser($token);
        if(!($theUser instanceof User)){
            return $this->JWTService->getUser($token);
        }

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\User');

        $userCheck = $repository->findOneBy(['email' => $email ]);

        if(empty($userCheck)) {
            return new JsonResponse(['message' => "Erreur serveur", 'status' => 404]);
        }
        $passwordAreCorrect = $hasher->isPasswordValid($userCheck, $currentPassword);
        if(!$passwordAreCorrect){
            return new JsonResponse(['message' => "Mot de passe incorrect", 'status' => 404]);
        }
        $encoded = $hasher->hashPassword($user, $newPassword);
        $userCheck->setPassword($encoded);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userCheck);
        $entityManager->flush();
        return new JsonResponse(['message' => "Votre mot de passe a bien été changé", 'status' => 200]);
    }

    #[Route('/login/authentification/deleteAccount', name: 'delete_account')]
    public function deleteAccount(Request $request,  UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $email = $request->request->get('email');
        $currentPassword = $request->request->get('currentPassword');

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\User');

        $userCheck = $repository->findOneBy(['email' => $email ]);

        if(empty($userCheck)) {
            return new JsonResponse(['message' => "Erreur serveur", 'status' => 404]);
        }
        $passwordAreCorrect = $hasher->isPasswordValid($userCheck, $currentPassword);
        if(!$passwordAreCorrect){
            return new JsonResponse(['message' => "Mot de passe incorrect", 'status' => 404]);
        }
        return new JsonResponse(['message' => "Compte supprimé", 'status' => 200]);
    }


    #[Route('/managerUser/newEmail', name: 'change_email')]
    public function changeEmail(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $email = $request->request->get('email');
        $newEmail = $request->request->get('newEmail');
        $currentPassword = $request->request->get('currentPassword');

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\User');

        $userCheck = $repository->findOneBy(['email' => $email ]);

        if(empty($userCheck)) {
            return new JsonResponse(['message' => "Erreur serveur", 'code' => false]);
        }
        $userCheck->setActivationToken($this->generateToken());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($userCheck);
        $entityManager->flush();

        try{
            $this->mailerService->sendEmail($newEmail, $userCheck->getActivationToken());
        } catch (TransportException $e){
            return new JsonResponse(['message' => "Envoie d'email échoué", 'code' => false]);
        }
    }

    /**
     * @param Request $request
     * @param FileUploaderServiceInterface $fileUploaderService
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Exception
     */
    #[Route('/managerUser/newAvatar', name: 'change_email')]
    public function changeAvatar(Request $request, FileUploaderService $fileUploaderService, UserPasswordHasherInterface $hasher): Response
    {

        $token = $request->headers->get('Authorization');
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
        }else {
            return new JsonResponse(["token expiré"]);
        }
        $avatar = $request->request->get('avatar');
        if($avatar){
            if($user->getAvatar()){
                $fileUploaderService->deleteAvatar($user->getAvatar());
            }
            $file = $fileUploaderService->uploadAvatar($avatar);
            $user->setAvatar($file);
        } else {
            if($user->getAvatar()){
                $fileUploaderService->deleteAvatar($user->getAvatar());
            }
            $user->setAvatar(null);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $payload = [
            "user" => $user->getUsername(),
            "email" => $user->getEmail(),
            "avatar" => $user->getAvatar(),
            "role" => $user->getRoles(),
            "createdAt" => $user->getCreatedAt(),
            "exp"  => (new \DateTime())->modify("+1 day")->getTimestamp(),
        ];
        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return new JsonResponse(['message' => "changement effectué", 'status' => 200, "tokenType"=> "Bearer", 'token' => sprintf('%s', $jwt)]);
    }

    #[Route('/activation/newEmail/{token}', name: 'activation_new_email')]
    public function activationNewEmail($token)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\User');
        $user = $repository->findOneBy(['activation_token' => $token ]);

        // Si aucun utilisateur n'est associé à ce token
        if(!$user){
            // On renvoie une erreur 404
            return $this->render('login_authentification/activation_error.html.twig');
        }

        // On supprime le token
        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('message', 'Utilisateur activé avec succès');

        // On retourne à l'accueil
        return $this->render('login_authentification/activation_succes.html.twig');
    }

    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
