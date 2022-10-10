<?php

namespace App\Controller;

use App\Service\AnimalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{

    private $animalService;

    public function __construct(AnimalService $animalService)
    {
        $this->animalService = $animalService;
    }

    #[Route('/statistics', name: 'statistics')]
    public function index(): Response
    {
        return $this->render('statistics/index.html.twig', [
            'controller_name' => 'StatisticsController',
        ]);
    }

    #[Route('/statistics/species/count', name: 'statistics_species', methods: ['GET'])]
    public function getCountForAnSpecie(): Response
    {
        $nbchien = $this->animalService->getCountForAnSpecie("chien");
        $nbchat = $this->animalService->getCountForAnSpecie("chat");
        $nbvolatile = $this->animalService->getCountForAnSpecie("volatile");
        $nbreptile = $this->animalService->getCountForAnSpecie("reptile");
        $nbautres = $this->animalService->getCountForAnSpecie("autres");
        $arrayKeys = ["chien" => $nbchien, "chat" => $nbchat, "volatile" => $nbvolatile, "reptile" => $nbreptile, "autre" => $nbautres];

        return new JsonResponse($arrayKeys);
    }

    #[Route('/statistics/species/count/department/{species}', name: 'statistics_species_department', methods: ['GET'])]
    public function getCountSpecieForAnDepartment($species): Response
    {
        return new JsonResponse($this->animalService->getTopMostPopularDepartment($species, 5));
    }
}
