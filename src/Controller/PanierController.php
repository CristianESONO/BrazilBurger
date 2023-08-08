<?php

namespace App\Controller;

use App\Repository\FritesRepository;
use App\Service\Panier\PanierService;
use App\Repository\BoissonsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/client/panier', name: 'app_panier')]
    public function index(PanierService $panierService): Response
    {
        
        $dataPanier=$panierService->getFullPanier();
        $total = $panierService->getTotal();

        return $this->render('panier/index.html.twig', [
            "items" => $dataPanier,
          "total" => $total
        ]);
    }

    #[Route('/client/boisson', name: 'app_client_boisson')]
    public function showBoisson(BoissonsRepository $repoBoisson): Response
    {
        $datas=$repoBoisson->findAll();
        return $this->render('panier/listeBoissonComplement.html.twig', [
            'datas'=>$datas,
        ]);
    }

    #[Route('/client/frite', name: 'app_client_frite')]
    public function showFrite(FritesRepository $repoFrite): Response
    {
        $datas=$repoFrite->findAll();
        return $this->render('panier/listeFriteComplement.html.twig', [
            'datas'=>$datas,
        ]);
    }

    
    #[Route('/client/panier/add/{id}', name: 'app_add_client_commander')]
    public function addPanier($id, PanierService $panierService): Response
    {
      
      $panierService->add($id);

        return $this->redirectToRoute("app_panier");
    }

    #[Route('/client/panier/del/{idBg}', name: 'app_lower_client_commande_burger')]
    public function lowerBurger($idBg, PanierService $panierService): Response
    {
      
      $panierService->delete($idBg);

        //dd($session->get('panier'));
        return $this->redirectToRoute("app_panier");
    }


    #[Route('/client/panier/destroy/{idBg}', name: 'app_commande_destroy_burger', methods:['GET'])]
    public function destroy($idBg,PanierService $panierService): Response
    {
      $panierService->remove($idBg);

        return $this->redirectToRoute("app_panier");
    }

}
