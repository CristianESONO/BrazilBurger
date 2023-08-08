<?php

namespace App\Service\Panier;

use App\Repository\BoissonsRepository;
use App\Repository\BurgerRepository;
use App\Repository\FritesRepository;
use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService {

    protected $session;
    protected $repoBg;
    protected $repoMenu;
    protected $repoFrite;
    protected $repoBoisson;

    public function __construct(SessionInterface $session, BurgerRepository $repoBg, MenuRepository $repoMenu, BoissonsRepository $repoBoisson, FritesRepository $repoFrite)
    {
        $this->session =$session;
        $this->repoBg =$repoBg;
        $this->repoMenu =$repoMenu;
        $this->repoFrite =$repoFrite;
        $this->repoBoisson =$repoBoisson;
    }

    public function add(int $id) {
         //On recupere le panier actuel
         $panier= $this->session->get("panier",[]);
  
          if(!empty($panier[$id])){
            $panier[$id]++;
          }else{
            $panier[$id] = 1;
          }
  
          //On sauvegarde  dans la session
          $this->session->set("panier",$panier);
  
    }

    //Diminuer la quantité d'un produit
    public function delete(int $id) {
        //On recupere le panier actuel
        $panier= $this->session->get("panier",[]);
 
        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }
         //On sauvegarde  dans la session
         $this->session->set("panier",$panier);
 
   }
   
   //SUpprimer un produit
    public function remove(int $id) {
        //On recupere le panier actuel
        $panier= $this->session->get("panier",[]);
      if(!empty($panier[$id])){
        unset($panier[$id]);
      }
      $this->session->set("panier",$panier);
    }


//recuperer tous les elements dans un produit
     public function getFullPanier() : array 
     {
      $panier = $this->session->get("panier",[]);
       //On fabrique les données
       $dataPanier = [];
       foreach ($panier as $id => $quantite) 
       {
          if($burger=$this->repoBg->find($id)!=null)
          {
            $dataPanier[] = [
              "produit"=>$this->repoBg->find($id),
              "quantite"=> $quantite
            ];
          }

          else if($frite=$this->repoFrite->find($id)!=null)
          {
            $dataPanier[] = [
              "produit"=>$this->repoFrite->find($id),
              "quantite"=> $quantite
            ];
            
          }

          else if($boisson=$this->repoBoisson->find($id)!=null)
          {
            $dataPanier[] = [
              "produit"=>$this->repoBoisson->find($id),
              "quantite"=> $quantite
            ];
          }
          else {
            $dataPanier[] = [
              "produit"=>$this->repoMenu->find($id),
              "quantite"=> $quantite
            ];
          }

       }
       return $dataPanier;
     }
     public function getTotal() : float 
     {
 
         $total = 0;
         $dataPanier=$this->getFullPanier();
         
         foreach($dataPanier as $item){
          $total += $item["produit"]->getPrix() * $item["quantite"];
         }
         return $total;
     }
}