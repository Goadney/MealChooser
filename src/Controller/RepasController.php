<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Repository\RepasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RepasController extends AbstractController
{
    #[Route('/repas', name: 'app_repas_list')]
    public function index(RepasRepository $repasRepository): Response
    {
        $repas = $repasRepository->findAll();
        return $this->render('repas/repas-list.html.twig', [
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