<?php

namespace App\Controller;

use App\Entity\Frites;
use App\Repository\FritesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FritesController extends AbstractController
{
    #[Route('/gestionnaire/frite', name: 'app_gestionnaire_frite')]
    public function show(FritesRepository $repoFrite): Response
    {
        $datas=$repoFrite->findAll();
        return $this->render('frites/index.html.twig', [
            'datas'=>$datas,
        ]);
    }


    #[Route('/gestionnaire/frite/create', name: 'app_gestionnaire_frite_create')]
    public function create(Request $request,FritesRepository $repoFrite): Response
    {
        $datas=$repoFrite->findAll();
        $errors=[];
        //Si l'utilisateur clique sur le bouton enrgistrer du formulaire
        if($request->request->has("btnSave")){

            $nom=$request->request->get("nomFrite");
            $prix=$request->request->get("prixFrite");
            $image=$request->request->get("imgFrite");
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
                return $this->redirectToRoute('app_gestionnaire_frite_create',[
                    "errors"=>$errors
                ]);
            }

            if(trim($request->request->get("btnSave"))=='create'){
                //Création de l'objet de type frite
                $frite=new Frites;
    
            }else{

                //récupérer l'id du frite  qui se trouve dans le champ caché
                $idfrite=$request->request->get('id');
                $frite=$repoFrite->find($idfrite);

            }
            //Donner des états aux attributs avec les setters
            $frite->setNom($nom);
            $frite->setPrix($prix);
            $frite->setImage($image);
           
          
            //Appel de la méthode save qui se trouve dans BurgerRepository
            $repoFrite->save($frite,true);
            //redirection vers la liste des burgers
            return $this->redirectToRoute('app_gestionnaire_frite');
        }

        return $this->render('frites/index.html.twig', [
            'datas'=>$datas,
        ]);
    }

     //méthode pour modifier une frite
     #[Route('/gestionnaire/frite/edit/{idfrite}', name: 'app_gestionnaire_edit_frite',methods:["GET"])]
     public function edit($idfrite,FritesRepository $repoFrite): Response
     {
        $frite =$repoFrite->findOneBy(['id'=>$idfrite]);
        $datas=$repoFrite->findAll();
        return $this->render('frites/index.html.twig', [
                "datas"=>$datas,
                "frite"=>$frite
        ]);
     }

    //méthode pour supprimer une frite
    #[Route('/gestionnaire/frite/destroy/{idfrite}', name: 'app_gestionnaire_destroy_frite',methods:["GET"])]
    public function destroy($idfrite,FritesRepository $repoFrite): Response
    {

        //récupération de la l'id de l'agence
        $frite=$repoFrite->find($idfrite);
        //Appel de la méthode remove qui se trouve dans AgenceRepository
        $repoFrite->remove($frite,true);
        //redirection vers la liste des agences
        return $this->redirectToRoute('app_gestionnaire_frite');
    }


}
