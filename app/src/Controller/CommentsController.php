<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Service\JsonWebTokenService;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{

    private $JWTService;

    public function __construct( JsonWebTokenService $JWTService)
    {
        $this->JWTService = $JWTService;
    }

    #[Route('/comments/question', name: 'comments_question', methods: ['POST'])]
    public function questionToAnAnnonce(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $token = $request->headers->get('Authorization');
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
        }else {
            $this->JWTService->getUser($token);
        }

        $content = $request->request->get('content');
        $animalIsUserid = $request->request->get('animalIsUserid');
        $animalId = $request->request->get('animalId');

        $theAnimal = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Animal')
            ->find($animalId);
        ;

        $theReceiver = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\User')
            ->find($animalIsUserid);
        ;

        if($user === $theReceiver) return new JsonResponse(['message' => "Erreur serveur", 'status' => 500]);
        if($theReceiver !== $theAnimal->getUser()) return new JsonResponse(['message' => "Erreur serveur", 'status' => 500]);

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setAnimalId($theAnimal);
        $comment->setSender($user);
        $comment->setReceiver($theReceiver);
        $comment->setAnswerTo(null);
        $comment->setIsRead(false);

        $entityManager->persist($comment);
        $entityManager->flush();

        return new JsonResponse(['message' => "Commentaire posté", 'status' => 200]);
    }

    #[Route('/comments/response', name: 'comments_response', methods: ['POST'])]
    public function responseToAnQuestion(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $token = $request->headers->get('Authorization');
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
        }else {
            $this->JWTService->getUser($token);
        }

        $content = $request->request->get('content');
        $animalId = $request->request->get('animalId');
        $commentWhoIsAnsweredId = $request->request->get('commentWhoIsAnsweredId');
        $userWhoIsAnsweredId = $request->request->get('userWhoIsAnsweredId');

        if(!$commentWhoIsAnsweredId) return new JsonResponse(['message' => "Erreur serveur", 'status' => 500]);
        $theAnimal = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Animal')
            ->find($animalId);
        ;
        $theCommentWhoIsAnswered = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Comment')
            ->find($commentWhoIsAnsweredId);
        ;

        $theUserWhoIsAnswered = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\User')
            ->find($userWhoIsAnsweredId);
        ;

        if($theAnimal->getUser() !== $user) return new JsonResponse(['message' => "Erreur serveur", 'status' => 500]);

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setAnimalId($theAnimal);
        $comment->setSender($user);
        $comment->setReceiver($theUserWhoIsAnswered);
        $comment->setAnswerTo($theCommentWhoIsAnswered);
        $comment->setIsRead(false);

        $entityManager->persist($comment);
        $entityManager->flush();

        return new JsonResponse(['message' => "Commentaire posté", 'status' => 200]);
    }

    #[Route('/comments/getNotViewedQuestion', name: 'comments_getNotViewedQuestion', methods: ['GET'])]
    public function getNotViewedQuestion(Request $request,  EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $token = $request->headers->get('Authorization');
        $user = null;
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
        }else {
            $this->JWTService->getUser($token);
        }

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Comment')
            ->findByUser($user);

        $data = $serializer->serialize($repository, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/comments/getCommentsFromAnAnimal/{animalId}', name: 'comments_getCommentsFromAnAnimal', methods: ['GET'])]
    public function getCommentsFromAnAnimal(Request $request,  EntityManagerInterface $entityManager, SerializerInterface $serializer, $animalId): Response
    {
        $animal = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Animal')
            ->find($animalId);
        ;

        $data = $serializer->serialize($animal->getComments(), 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/comments/userViewedTheMessages', name: 'comments_userViewedTheMessages', methods: ['GET'])]
    public function userViewedTheMessages(Request $request,  EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $token = $request->headers->get('Authorization');
        $user = null;
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
        }else {
            $this->JWTService->getUser($token);
        }
        for($i=0; $i<count($user->getCommentsReceived()); $i++){
            $comment = $user->getCommentsReceived()[$i];
            $comment->setIsRead(true);
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return new JsonResponse(["status" => "200", "message"=>"requête effectué"]);
    }
}
