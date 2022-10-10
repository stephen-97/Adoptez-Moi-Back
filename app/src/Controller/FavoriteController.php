<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AnimalService;
use App\Service\FileUploaderService;
use App\Service\JsonWebTokenService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class FavoriteController extends AbstractController
{

    private $JWTService;
    private $animalService;

    public function __construct( JsonWebTokenService $JWTService, AnimalService $animalService)
    {
        $this->JWTService = $JWTService;
        $this->animalService = $animalService;
    }

    /**
     * @param Request $request
     * @param FileUploaderServiceInterface $fileUploaderService
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Exception
     */
    #[Route('/favorite/addOrRemove/{id}', name: 'addOrRemove_favorite')]
    public function addOrRemove(Request $request, FileUploaderService $fileUploaderService, EntityManagerInterface $entityManager, $id): Response
    {
        $token = $request->headers->get('Authorization');
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
        }else {
            return new JsonResponse(["token expiré"]);
        }

        $animal = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Animal')
            ->find($id);
        //dd($animal->getFavorite()->toArray());
        //$favoriteArray = $animal->getFavorite()->toArray();

        if(in_array($user, $animal->getFavorite()->toArray())){
            $animal->removeFavorite($user);
        } else {
            $animal->addFavorite($user);
        }
        $entityManager->persist($animal);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(["status" => "200", "message" => "Requête accepté"]);
    }



    #[Route('/favorite/getAlls/{page}/{nbItemsOfPage}', name: 'getAll_favorite', methods: ['GET'])]
    public function getFavorites(Request $request, PaginatorInterface $paginator, SerializerInterface $serializer, $page, $nbItemsOfPage): Response
    {
        $token = $request->headers->get('Authorization');
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
        }else {
            return new JsonResponse(["token expiré"]);
        }

        $article = $paginator->paginate($user->getFavorites()->toArray(), $request->query->getInt('page', $page), $nbItemsOfPage);
        $data = $serializer->serialize($article, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
}
