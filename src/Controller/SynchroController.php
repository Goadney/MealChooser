<?php

namespace App\Controller;

use App\Entity\Repas;
use OpenFoodFacts\Api;
use Shuchkin\SimpleXLSX;
use App\Enum\SaisonsEnum;
use App\Entity\Categories;
use App\Entity\Ingredients;
use App\Services\SluggerService;
use App\Repository\RepasRepository;
use App\Repository\SaisonsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IngredientsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SynchroController extends AbstractController
{
    private EntityManagerInterface $em;
    private SluggerService $slugger;
    private CategoriesRepository $categoriesRepository;
    private RepasRepository $repasRepository;
    private IngredientsRepository $ingredientsRepository;
    private SaisonsRepository $saisonsRepository;

    public function __construct(
        EntityManagerInterface $em,
        SluggerService $slugger,
        RepasRepository $repasRepository,
        CategoriesRepository $categoriesRepository,
        IngredientsRepository $ingredientsRepository,
        SaisonsRepository $saisonsRepository
    ) {
        $this->em = $em;
        $this->slugger = $slugger;
        $this->repasRepository = $repasRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->ingredientsRepository = $ingredientsRepository;
        $this->saisonsRepository = $saisonsRepository;
    }
    #[Route('/synchro', name: 'synchro')]
    public function synchro_excel(Request $request): Response
    {   
        $token =  $request->query->get('token');
        if($token != $_ENV['API_TOKEN']){
            return $this->redirectToRoute('app_home');
        }
        $meals = $this->parseMealsXlsx('meals_');

        foreach($meals as $m){
            $repas = new Repas();
            $repas->setName($m['NOM']);
            $slug = $this->slugger->generateSlug($m['NOM']);
            
            // si le repas existe déjà, on met juste à jour ces infos ? 
            $already_exists = false;
            $repas_existant = $this->repasRepository->getBySlug($slug);
            if($repas_existant != null){
                $already_exists = true;
                $repas = $repas_existant;
            }
            
            $repas->setSlug($this->slugger->generateSlug($m['NOM']));
            $repas->setDuree($m['DUREE (LONG,COURT,NORMAL)']);
            switch($m['WEEK_END (Y/N)']){
                case 'Y' : $wk = true;break;
                case 'N' : $wk = false;break;
                default : $wk = false;break;
            }
            $repas->setWeekend($wk);
            
            //création des ingredients 
            //Garder tous les index de $m après l'index 4 
            $ingredients = array_slice($m, 5);
            foreach($ingredients as $key => $ingredient){
                if($ingredient != null){
                    // on créer la catégorie 
                    $categorie = $this->getOrCreate('categorie',$key, new Categories());
                    //dans chaque catégorie on peut avoir plusieurs ingredient, on créer la catégorie puis l'ingredient
                    $all_categories_ingredients = explode(';', $ingredient);
                    //asociation et création/récupération ingredients
                    foreach($all_categories_ingredients as $categorie_ingredient){
                        $ingredient = $this->getOrCreate('ingredient',$categorie_ingredient, new Ingredients(), $categorie);
                        $ingredient->setCategorie($categorie);
                        $repas->addIngredient($ingredient);
                    }
                }
            }
            
            //association saisons 
            $saisons = explode(';', $m['SAISONS (HIVER;ETE;AUTOMNE;PRINTEMPS)']);
            foreach($saisons as $saison){
                $saison = $this->saisonsRepository->findOneBy(['name' => $saison]);
                if ($saison) {
                    $repas->addSaison($saison);
                }
            }
            
            
            if($already_exists == false){ 
            //     // créer l'objet
                $this->em->persist($repas);
            }
            // //l'insert en db
            $this->em->flush();
            
        }

        return $this->redirectToRoute('app_home'); 
       }

    
    /**
     * Get or create a new object of type $type (categorie or ingredient)
     * @param string $type Type of object to get or create
     * @param string $name Name of the object
     * @param object $object Object to create if it doesn't exist
     * @return object The object created or fetched
     */
    private function getOrCreate($type,$name,$object, $categorie=null){

        $slug = $this->slugger->generateSlug($name);
        $exist = null;
        switch($type){
            case "categorie" : $exist = $this->categoriesRepository->getBySlug($slug);break;
            case "ingredient" : $exist = $this->ingredientsRepository->getBySlug($slug);break;
            default :null;break;
        }

        if($exist == null){
            $new_object = $object;
            $new_object->setName($name);
            $new_object->setSlug($slug);
            if($type == "ingredient"){
                $new_object->setCategorie($categorie);
            }
            $this->em->persist($new_object);
            $this->em->flush();   
            return $new_object;

        }
        else{
            return $exist;
        }
        
    }

    private function parseMealsXlsx($file){
        if($xlsx = SimpleXLSX::parse("files/import_files/$file.xlsx")){
            $mealsDetails = [];
            $headers = [];
            foreach($xlsx->rows() as $row){
                if($row[0] == "")
                    continue;
                if(empty($headers)){
                    $headers = $row;
                    if($headers[0] != 'NOM' || $headers[1] != 'TYPE (ENTREE,SAIN,GRAS,MOYEN)'|| $headers[2] != 'SAISONS (HIVER;ETE;AUTOMNE;PRINTEMPS)' || $headers[3] != 'DUREE (LONG,COURT,NORMAL)' || $headers[4] != 'WEEK_END (Y/N)'){
                        dd('error parsing xlxs');
                    }
                }
                else{
                    $mealsDetails[] = array_combine($headers, $row);
                }
            }
        }
        else {
            die(SimpleXLSX::parseError());
        }
        return $mealsDetails;
    }
}
