<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AnimalService;
use App\Service\JsonWebTokenService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use JMS\Serializer\SerializationContext;

class WantedAnimalController extends AbstractController
{

    private $JWTService;
    private $animalService;

    public function __construct( JsonWebTokenService $JWTService, AnimalService $animalService)
    {
        $this->JWTService = $JWTService;
        $this->animalService = $animalService;
    }
    #[Route('/wanted/animal', name: 'wanted_animal')]
    public function index(): Response
    {
        return $this->render('wanted_animal/index.html.twig', [
            'controller_name' => 'WantedAnimalController',
        ]);
    }

    #[Route('/wanted/sliderHomePage/', name: 'wantedAnimal_Homepage')]
    public function new(Request $request, SerializerInterface $serializer): Response
    {
        $nb = $request->request->get('numberOfAnimal');

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Animal')
            ->findBy([], ['id' => 'DESC'], $nb);


        if(empty($repository)){
            return new JsonResponse(['message' => "Aucuns avis disponible", 'code' => false]);
        }
        $data = $serializer->serialize($repository, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/wanted/user/', name: 'wantedAnimal_accountPage')]
    public function listforUser(Request $request, PaginatorInterface $paginator, SerializerInterface $serializer): Response
    {

        $token = $request->headers->get('Authorization');
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
        }else {
            $this->JWTService->getUser($token);
        }

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Animal')
            ->findByUser($user);

        $data = $serializer->serialize($repository, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/wanted/animalsListForAnUser/', name: 'wantedAnimal_animalAnnoncePage')]
    public function animalsForAUser(Request $request, PaginatorInterface $paginator, SerializerInterface $serializer): Response
    {
        $userId = $request->request->get('userId');

        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\User')
            ->find($userId);

        $animalsOfThisUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Animal')
            ->findByUser($user);

        $data = $serializer->serialize($animalsOfThisUser, 'json',  SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/, json');
        return $response;
    }

    #[Route('/wanted/page/', name: 'wantedAnimal_searchPage')]
    public function pagination(Request $request, PaginatorInterface $paginator, SerializerInterface $serializer): Response
    {
        $nbitemsPorPage = $request->request->get('nbitemsPorPage');
        $currentPage = $request->request->get('currentPage');

        $species = $request->request->get('specie');
        $sex = $request->request->get('sex');
        $department = $request->request->get('department');
        $order = $request->request->get('order');

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Animal')
            ->animalListFilter($species, $sex, $department, $order);
        $article = $paginator->paginate($repository, $request->query->getInt('page', $currentPage), $nbitemsPorPage);

        $data = $serializer->serialize($article, 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/wanted/randomAnimal/{specie}/{numberOfResult}', name: 'wantedAnimal_random', methods: ['GET'])]
    public function randomAnimal(Request $request, SerializerInterface $serializer, $specie, $numberOfResult): Response
    {
        $data = $serializer->serialize($this->animalService->getRandomAnimal($specie, $numberOfResult), 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    #[Route('/wanted/randomAnimalOfAllSpecies/{numberOfResult}', name: 'wantedAnimalAllSpecies_random', methods: ['GET'])]
    public function randomAnimalOfAllSpecies(Request $request, SerializerInterface $serializer, $numberOfResult): Response
    {
        $data = $serializer->serialize($this->animalService->getRandomAnimalWhateverTheSpecie($numberOfResult), 'json', SerializationContext::create()->enableMaxDepthChecks());
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
