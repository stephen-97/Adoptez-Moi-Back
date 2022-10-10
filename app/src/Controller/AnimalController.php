<?php

namespace App\Controller;


use App\Entity\Chien;
use App\Entity\Chat;
use App\Entity\Volatile;
use App\Entity\Reptile;
use App\Entity\Autres;
use App\Entity\Image;
use App\Entity\User;
use App\Service\AnimalService;
use App\Service\FileUploaderService;
use App\Service\JsonWebTokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AnimalController extends AbstractController
{

    private $JWTService;
    private $animalService;
    private $fileUploaderService;

    public function __construct( JsonWebTokenService $JWTService, AnimalService $animalService, FileUploaderService $fileUploaderService)
    {
        $this->JWTService = $JWTService;
        $this->animalService = $animalService;
        $this->fileUploaderService = $fileUploaderService;
    }

    #[Route('/animal/', name: 'animal')]
    public function index(): Response
    {
        return $this->render('wanted_animal/index.html.twig', [
            'controller_name' => 'WantedAnimalController',
        ]);
    }

    /**
     * @param Request $request
     * @param FileUploaderServiceInterface $fileUploaderService
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Exception
     */
    #[Route('/animal/new/', name: 'animal_new')]
    public function new(Request $request, FileUploaderService $fileUploaderService, EntityManagerInterface $entityManager): Response
    {
        $token = $request->headers->get('Authorization');
        if($this->JWTService->getUser($token) instanceof User){
            $user = $this->JWTService->getUser($token);
        }else {
            return new JsonResponse(["token expiré"]);
        }

        $name = $request->request->get('name');
        $species = $request->request->get('species');
        $vaccinated = $request->request->get('vaccinated');
        $description = $request->request->get('description');
        $race = $request->request->get('race');
        $age = $request->request->get('age');
        $sex = $request->request->get('sex');
        $department = $request->request->get('departement');
        $phoneNumber = $request->request->get('phoneNumber');
        $identificationNumber = $request->request->get('identificationNumber');
        $chipOrTatoo = $request->request->get('chipOrTatoo');
        $image = $request->request->get('image');


        switch ($species) {
            case "chat":
                $animal = new Chat($name, $species, $race, $vaccinated,$sex, $description, $age, $department, $phoneNumber, $user, $chipOrTatoo, $identificationNumber);
                break;
            case "chien";
                $animal = new Chien($name, $species, $race, $vaccinated,$sex, $description, $age, $department, $phoneNumber, $user, $chipOrTatoo, $identificationNumber);
                break;
            case "volatile":
                $animal = new Volatile($name, $species, $race, $vaccinated, $sex, $description, $age, $department, $phoneNumber, $user);
                break;
            case "reptile":
                $animal = new Reptile($name, $species, $race, $vaccinated, $sex, $description, $age, $department, $phoneNumber, $user);
                break;
            case "autres":
                $animal = new Autres($name, $species, $race, $vaccinated, $sex, $description, $age, $department, $phoneNumber, $user);
                break;
            default:
                return new JsonResponse(['message' => "Saisi incorrect", 'status' => 500]);
        }

       $file = $fileUploaderService->upload($image);
       $image = new Image();
       $image->setName($file);
       $image->setAnimal($animal);

       $entityManager->persist($animal);
       $entityManager->persist($image);
       $entityManager->flush();

       return new JsonResponse(["status" => 200, "message" => "Votre annonce a bien été créé ! "]);
    }

    #[Route('/animal/delete/{id}', name: 'animal_delete', methods: ['DELETE'])]
    public function animalDelete(Request $request,  $id): Response
    {

        $token = $request->headers->get('Authorization');
        if( !($this->JWTService->getUser($token) instanceof User)){
            return $this->JWTService->getUser($token);
        }

        $animal = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Animal')
            ->find($id);

        if($animal->getUser() !== $this->JWTService->getUser($token)) {
            return new JsonResponse(["status" => 403, "message" => "L'animal n'appartient pas à l'utilisateur' "]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($animal);
        $entityManager->flush();
        $this->fileUploaderService->deleteImages($animal->getImages());

        return new JsonResponse(['message' => "L'annonce a été supprimé", 'status' => 200]);
    }
}
