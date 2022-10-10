<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FileUploaderService;
use App\Service\JsonWebTokenService;
use App\Service\MailerService;
use App\Service\UserService;
use App\Service\AnimalService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    private $JWTService;
    private $userService;
    private $animalService;
    private $mailerService;
    private $fileUploaderService;

    public function __construct( JsonWebTokenService $JWTService, UserService $userService, AnimalService $animalService, MailerService $mailerService, FileUploaderService $fileUploaderService)
    {
        $this->JWTService = $JWTService;
        $this->userService = $userService;
        $this->animalService = $animalService;
        $this->mailerService = $mailerService;
        $this->fileUploaderService = $fileUploaderService;
    }

    public function isAdmin(Request $request) {
        $token = $request->headers->get('Authorization');
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
            if(in_array("ROLE_ADMIN",$user->getRoles())) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/allUsers', name: 'admin_users', defaults: ["username" => ""], methods: ['POST'])]
    public function getAllUsers(Request $request, SerializerInterface $serializer, PaginatorInterface $paginator): Response
    {
        if(!$this->isAdmin($request)) return new JsonResponse(["status" => 404, "message"=>"erreur serveur"]);

        $page = $request->request->get('page');
        $nbItemsOfPage = $request->request->get('nbItemsOfPage');
        $username = $request->request->get('username');


        $usersList = $this->userService->findUserByUsername($username);
        $article = $paginator->paginate($usersList, $request->query->getInt('page', $page), $nbItemsOfPage);
        $data = $serializer->serialize($article, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/admin/animalListUser/{id}', name: 'admin_animalList_user', methods: ['GET'])]
    public function getAnimalListForAnUser(Request $request, SerializerInterface $serializer, $id): Response
    {
        if(!$this->isAdmin($request)) return new JsonResponse(["status" => "404", "message"=>"erreur serveur"]);
        $selectedUser = $this->userService->findUserByid($id);
        if(!$selectedUser) return new JsonResponse(["status" => "404", "message" => "not found"]);
        $animalList = $this->animalService->getAllAnimalsForAnUser($selectedUser);


        $data = $serializer->serialize($animalList, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/admin/banUser/{id}', name: 'admin_ban_user', methods: ['DELETE'])]
    public function banUser(Request $request, SerializerInterface $serializer, $id): Response
    {
        if(!$this->isAdmin($request)) return new JsonResponse(["status" => 404, "message"=>"erreur serveur"]);

        $selectedUser = $this->userService->findUserByid($id);
        if(!$selectedUser) return new JsonResponse(["status" => 404, "message" => "not found"]);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($selectedUser);
        $entityManager->flush();

        return new JsonResponse(["status" => 200, "message" => "utilisateur supprimé !"]);
    }

    #[Route('/admin/animalDelete/{id}/{message}', name: 'admin_delete_animal', methods: ['DELETE'])]
    public function deleteAnimal(Request $request, SerializerInterface $serializer, $id, $message): Response
    {
        if(!$this->isAdmin($request)) return new JsonResponse(["status" => "404", "message"=>"erreur serveur"]);

        $animal = $this->animalService->findAnimal($id);
        $user = $animal->getUser();

        /**try{
            $this->mailer->adminDeleteAnimal($user->getEmail(), $message);
        } catch (TransportException $e){
            return new JsonResponse(['message' => "Envoie d'email échoué", 'code' => false]);
        }**/

        //$imageArray = $animal->getImages();
        /**foreach ($imageArray as $image) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }**/
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($animal);
        $entityManager->flush();
        $this->fileUploaderService->deleteImages($animal->getImages());

        return new JsonResponse(["status" => 200, "message" => "Annonce supprimé!"]);
    }

}


/**
 *
 *
 * #[Route('/admin/allUsers/{currentPage}/{nbofItemsPorPage}/{username}', name: 'admin_users', defaults: ["username" => ""], methods: ['GET'])]
public function getAllUsers(Request $request, SerializerInterface $serializer, PaginatorInterface $paginator, $currentPage, $nbofItemsPorPage, $username): Response
{
$token = $request->headers->get('Authorization');
if($this->JWT->getUser($token) instanceof User){
$user = $this->JWT->getUser($token);
}else {
return new JsonResponse(["token expiré"]);
}$usersList = $this->userService->getAllUsers();
$data = $serializer->serialize($usersList, 'json');
$response = new Response($data);
$response->headers->set('Content-Type', 'application/json');
$usersList = $this->userService->findUserByUsername($username);
$article = $paginator->paginate($usersList, $request->query->getInt('page', $currentPage), $nbofItemsPorPage);
$data = $serializer->serialize($article, 'json');
$response = new Response($data);
$response->headers->set('Content-Type', 'application/json');

return $response;
}**/
