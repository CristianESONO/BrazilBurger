<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Repository\BurgerRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BurgerController extends AbstractController
{
    #[Route('/burger', name: 'app_show_burger')]
    public function show(BurgerRepository $repoBg): Response
    {
        $datas=$repoBg->findAll();
        return $this->render('burger/index.html.twig',[
            "datas"=>$datas
        ]);
        
    }

    #[Route('/gestionnaire/burger', name: 'app_show_gestionnaire_update_burger')]
    public function showG(BurgerRepository $repoBg): Response
    {
        $datas=$repoBg->findAll();
        return $this->render('burger/liste.html.twig',[
            "datas"=>$datas
        ]);
    }

    #[Route('/client/detail/burger/{idBg}', name: 'app_show_client_details_burger')]
    public function showDetails($idBg,BurgerRepository $repoBg): Response
    {
        $burger=$repoBg->findOneBy(['id'=>$idBg]);
        //$datas=$repoBg->findAll();
        return $this->render('burger/detailBurger.html.twig',[
            //"datas"=>$datas,
            "burger"=>$burger
        ]);
    }


    #[Route('/gestionnaire/burger/create', name: 'app_gestionnaire_create_burger')]
    public function create(Request $request,BurgerRepository $repoBg): Response
    {
        $errors=[];
        if($request->request->has('btnSave')){
            $nom=$request->request->get("nom");
            $prix=$request->request->get("prix");
            $image=$request->request->get("img");
            $description=$request->request->get("description");
            //Validation
            if(empty($nom)){
                $errors['nom']="Le nom est obligatoire";
            }
            if(empty($prix)){
                $errors['prix']="Le prix est obligatoire";
            }
            if(empty($image)){
                $errors['img']="L'image est obligatoire";
            }
            if(empty($description)){
                $errors['description']="La description est obligatoire";
            }
            if(count($errors)!=0){
                return $this->redirectToRoute("app_gestionnaire_create_burger",[
                    "errors"=>$errors
                ]);
            }
            if(trim($request->request->get("btnSave"))=="create"){
                $burger=new Burger;
            }else{
                $idBg=$request->request->get("id");
                $burger=$repoBg->find($idBg);
            }
             $burger->setNom($nom)
                    ->setPrix($prix)
                    ->setImage($image)
                    ->setDescription($description);
            $repoBg->save($burger,true);
            return $this->redirectToRoute("app_show_gestionnaire_update_burger");     
        }
      
        //return $this->redirectToRoute("app_show_burger");
        return $this->render('burger/nouveau.html.twig');
    }

    #[Route('/gestionnaire/burger/edit/{idBg}', name: 'app_gestionnaire_edit_burger', methods:['GET'])]
    public function edit($idBg,Request $request,BurgerRepository $repoBg): Response
    {
        $burger=$repoBg->findOneBy(['id'=>$idBg]);
        $datas=$repoBg->findAll();
        return $this->render('burger/nouveau.html.twig',[
            "datas"=>$datas,
            "burger"=>$burger
        ]);
    }

    #[Route('/gestionnaire/burger/destroy/{idBg}', name: 'app_gestionnaire_destroy_burger', methods:['GET'])]
    public function destroy($idBg,BurgerRepository $repoBg): Response
    {
        $burger=$repoBg->find($idBg);
        $repoBg->remove($burger,true);
        return $this->redirectToRoute("app_show_gestionnaire_update_burger");
    }

    

  
}
