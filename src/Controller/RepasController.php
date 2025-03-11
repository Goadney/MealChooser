<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Repository\RepasRepository;
use App\Repository\SaisonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RepasController extends AbstractController
{
    #[Route('/repas', name: 'app_repas_list')]
    public function index(RepasRepository $repasRepository, SaisonsRepository $saisonsRepository): Response
    {
        $repas = $repasRepository->findAll();
        $saisons = $saisonsRepository->findAll();
        $durees = ['COURT', 'NORMAL', 'LONG'];
        
        return $this->render('repas/repas-list.html.twig', [
            'repas' => $repas,
            'saisons' => $saisons,
            'durees' => $durees,

        ]);
    }

    #[Route('/filter-repas', name: 'filter_repas', methods: ['GET'])]
    public function filterMeals(Request $request, RepasRepository $repasRepository,SaisonsRepository $saisonsRepository): Response
    {
        $filter_saison = $request->query->get('filter_saison'); 
        $filter_duree = $request->query->get('filter_duree');
        $filter_weekend = $request->query->get('filter_weekend');
        $saison = $saisonsRepository->findOneBy(['name' => $filter_saison]);
        $repas = $repasRepository->findWithFilter($filter_duree,$filter_weekend, $saison); 
        return $this->render('partials/repas/_repas_list.html.twig', [
            'repas' => $repas,
        ]);
    }
    #[Route('/repas/{slug}', name: 'app_repas_show')]
    public function show(RepasRepository $repasRepository, string $slug): Response
    {
        // Récupérer le repas correspondant au slug
        $repas = $repasRepository->findOneBy(['slug' => $slug]);

        // Vérifier si le repas existe
        if (!$repas) {
            throw $this->createNotFoundException('Le repas avec ce slug n\'existe pas.');
        }

        return $this->render('repas/repas-show.html.twig', [
            'repas' => $repas,
        ]);
    }
}