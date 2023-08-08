<?php

namespace App\Controller;

use App\Entity\Boissons;
use App\Repository\BoissonsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoissonsController extends AbstractController
{
    #[Route('/gestionnaire/boisson', name: 'app_gestionnaire_boisson')]
    public function show(BoissonsRepository $repoBoisson): Response
    {
        $datas=$repoBoisson->findAll();
        return $this->render('boissons/index.html.twig', [
            'datas'=>$datas,
        ]);
    }



    #[Route('/gestionnaire/boisson/create', name: 'app_gestionnaire_boisson_create')]
    public function create(Request $request,BoissonsRepository $repoBoisson): Response
    {
        $datas=$repoBoisson->findAll();
        $errors=[];
        //Si l'utilisateur clique sur le bouton enrgistrer du formulaire
        if($request->request->has("btnSave")){

            $nom=$request->request->get("nomBoisson");
            $prix=$request->request->get("prixBoisson");
            $image=$request->request->get("imgBoisson");
            //Validation
            if(empty($nom)){
                $errors['nom']="nom obligatoire";
            }
            if(empty($prix)){
                $errors['prix']="Prix est obligatoire";
            }
            if(empty($image)){
                $errors['image']="L'image est obligatoire";
            }
            if(count($errors)!=0){
                return $this->redirectToRoute('app_gestionnaire_boisson_create',[
                    "errors"=>$errors
                ]);
            }

            if(trim($request->request->get("btnSave"))=='create'){
                //Création de l'objet de type boisson
                $boisson=new Boissons;
    
            }else{

                //récupérer l'id du frite  qui se trouve dans le champ caché
                $idboisson=$request->request->get('id');
                $boisson=$repoBoisson->find($idboisson);

            }
            //Donner des états aux attributs avec les setters
            $boisson->setNom($nom)
                    ->setPrix($prix)
                    ->setImage($image);
           
          
            //Appel de la méthode save qui se trouve dans BurgerRepository
            $repoBoisson->save($boisson,true);
            //redirection vers la liste des burgers
            return $this->redirectToRoute('app_gestionnaire_boisson');
        }

        return $this->render('boissons/index.html.twig', [
            'datas'=>$datas,
        ]);
    }

     //méthode pour modifier une frite
     #[Route('/gestionnaire/boisson/edit/{idboisson}', name: 'app_gestionnaire_edit_boisson',methods:["GET"])]
     public function edit($idboisson,BoissonsRepository $repoBoisson): Response
     {
        $boisson =$repoBoisson->findOneBy(['id'=>$idboisson]);
        $datas=$repoBoisson->findAll();
        return $this->render('boissons/index.html.twig', [
                "datas"=>$datas,
                "boisson"=>$boisson
        ]);
     }

    //méthode pour supprimer une frite
    #[Route('/gestionnaire/boisson/destroy/{idboisson}', name: 'app_gestionnaire_destroy_boisson',methods:["GET"])]
    public function destroy($idboisson,BoissonsRepository $repoBoisson): Response
    {

        //récupération de la l'id de l'agence
        $boisson=$repoBoisson->find($idboisson);
        //Appel de la méthode remove qui se trouve dans AgenceRepository
        $repoBoisson->remove($boisson,true);
        //redirection vers la liste des agences
        return $this->redirectToRoute('app_gestionnaire_boisson');
    }
}
