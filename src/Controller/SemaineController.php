<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Form\RepasType;
use App\Form\RepasModifyType;
use App\Repository\RepasRepository;
use App\Repository\SaisonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SemaineController extends AbstractController
{
    public function __construct(private RepasRepository $repasRepository, private EntityManagerInterface $entityManager){}  

    #[Route('/semaine', name: 'app_semaine_home', methods: ['GET'])]
    public function index(): Response
    {
        $repas = [];
        return $this->render('semaine/semaine-home.html.twig',
        [
            'repas' => $repas
        ]);
    }
    #[Route('/semaine', name: 'app_semaine_generer', methods: ['POST'])]
    public function generateWeek(): Response
    {
        //récuperer repas où le type n'est pas égale a ENTREE
        $repas = $this->repasRepository->findAllRepasNoEntree();
        //garder seulement 7 repas aléatoire 
        $randomKeys = array_rand($repas, 7);
        $repas = array_map(fn($key) => $repas[$key], $randomKeys);

        return $this->render('partials/semaine/semaine-list.html.twig', [
            'repas' => $repas
        ]);
    }
    
}