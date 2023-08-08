<?php

namespace App\Controller;

use DateTime;
use App\Entity\Burger;
use App\Entity\Livreur;
use App\Entity\Commande;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use App\Repository\ClientRepository;
use App\Repository\LivreurRepository;
use App\Service\Panier\PanierService;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande/burger', name: 'app_commande')]
    public function show(): Response
    {
      
        return $this->render('commande/index.html.twig', [
        ]);
    }

    #[Route('/gestionnaire/commande/burger', name: 'app_gestionnaire_commande')]
    public function showG(Request $request, CommandeRepository $repoCommande, ClientRepository $repoClient): Response
    {
        $tel="";
        $date="";

        if($request->request->has("tel") && $request->request->get("tel")!=""){
            $tel=$request->request->get("tel");
            //Récupération de ce client
            $client=$repoClient->findOneBy(["tel"=>$tel]);
             //Récupération des comptes de ce client
            $datas=$client->getCommandes();

        }
        else if($request->request->has("date") && $request->request->get("date")!=""){

            $date=$request->request->get("date");
            //Récupération de la date
            $datas=$repoCommande->findBy(array("date"=>$date));
        }
        else if($request->request->has("etat") && $request->request->get("etat")!=""){

            $etat=$request->request->get("etat");
            //Récupération de l'etat
            $datas=$repoCommande->findBy(array("etat"=>$etat));
        }
        else{
            $datas=$repoCommande->findAll();
        }
        /*Appel de la méthode findAll() qui se trouve dans CompteRepository 
        pour retourner laa liste des comptes */
        return $this->render('commande/liste.html.twig',
            [
                "datas"=>$datas,
                "tel"=>$tel,
                "date"=>$date,
            ]
        );
    }


    #[Route('/commande/create', name: 'app_commande_create')]
    public function create(Request $request,CommandeRepository $repoCommande,LivreurRepository $repoLiv,PanierService $panierService): Response
    {
       //recuperer le total des prix du paniers
       $total=$panierService->getTotal();
       //recuperer la liste des livreurs
       $listLivreur=$repoLiv->findAll();

       $errors=[];
        //Si l'utilisateur clique sur le bouton enrgistrer du formulaire
        if($request->request->has("btnSave")){
            $livreur = new Livreur;
            $livreur= $listLivreur[array_rand($listLivreur)];
            $client = $this->getUser();
            $etat="En cours";
            $date= new DateTime('now');
            $dmy = $date->format('d-m-Y');
            //$dateString = $date->format('Y-m-d H:i:s');
            //$dateString = date('Y-m-d H:i:s', $date->getTimestamp());
            //$dateString= (string) $date;

            if(trim($request->request->get("btnSave"))=='create'){
              //Generer automatiquement le numero de la commande
              $num="COM-N°".(count($repoCommande->findAll())+1);
                //Création de l'objet de type commande
                $commande=new Commande;
                $commande->setNumero($num);
    
            }else{

                //récupérer l'id du frite  qui se trouve dans le champ caché
                //$idboisson=$request->request->get('id');
                //$boisson=$repoBoisson->find($idboisson);

            }
            //Donner des états aux attributs avec les setters
            $commande->setMontantTotal($total)
                    ->setEtat($etat)
                    ->setDate($dmy);
            $commande->setClient($client);
            $commande->setLivreur($livreur);
           
          
            //Appel de la méthode save qui se trouve dans CommandeRepository
            $repoCommande->save($commande,true);
         
        }

        return $this->render('commande/message.html.twig', [
        ]);
    }
    
    #[Route('/gestionnaire/commande/cancel/{idCm}', name: 'app_gestionnaire_cancel_commande', methods:['GET'])]
    public function annuler($idCm,Request $request,CommandeRepository $repoCommande): Response
    {
        $tel="";
        $date="";
        $etat='Annulé';
        $commande=$repoCommande->findOneBy(['id'=>$idCm]);
        $commande->setEtat($etat);
        $commande1=$repoCommande->save($commande,true);
        $datas=$repoCommande->findAll();
        return $this->render('commande/liste.html.twig',[
            "datas"=>$datas,
            "tel"=>$tel,
            "date"=>$date,
            "comm"=>$commande1
        ]);
    }

    #[Route('/gestionnaire/commande/edit/{idCm}', name: 'app_gestionnaire_edit_commande', methods:['GET'])]
    public function edit($idCm,Request $request,CommandeRepository $repoCommande): Response
    {
        $tel="";
        $date="";
        $etat='Terminé';
        $commande=new Commande;
        $commande=$repoCommande->findOneBy(['id'=>$idCm]);
        $commande->setEtat($etat);
        $commande1=$repoCommande->save($commande,true);
        $datas=$repoCommande->findAll();
        return $this->render('commande/liste.html.twig',[
            "datas"=>$datas,
            "tel"=>$tel,
            "date"=>$date,
            "comm"=>$commande1
        ]);
    }
    
}
