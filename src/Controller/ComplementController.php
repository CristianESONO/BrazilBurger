<?php

namespace App\Controller;

use App\Entity\Complement;
use App\Repository\ComplementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ComplementController extends AbstractController
{
    #[Route('/client/complement', name: 'app_show_complement')]
    public function show(ComplementRepository $repoCp): Response
    {
        $datas=$repoCp->findAll();
        return $this->render('complement/index.html.twig',[
            "datas"=>$datas
        ]);
    }

    #[Route('/gestionnaire/complement', name: 'app_show_gestionnaire_update_complement')]
    public function showG(ComplementRepository $repoCp): Response
    {
        $datas=$repoCp->findAll();
        return $this->render('complement/liste.html.twig',[
            "datas"=>$datas
        ]);
    }

    #[Route('/gestionnaire/complement/create', name: 'app_gestionnaire_create_complement')]
    public function create(Request $request,ComplementRepository $repoCp): Response
    {
        $errors=[];
        if($request->request->has('btnSave')){
            $nom=$request->request->get("nom");
            $prix=$request->request->get("prix");
            $image=$request->request->get("img");
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
            if(count($errors)!=0){
                return $this->redirectToRoute("app_gestionnaire_create_complement",[
                    "errors"=>$errors
                ]);
            }
            if(trim($request->request->get("btnSave"))=="create"){
                $complement=new Complement;
            }else{
                $idCp=$request->request->get("id");
                $complement=$repoCp->find($idCp);
            }
             $complement->setNom($nom)
                    ->setPrix($prix)
                    ->setImage($image);
            $repoCp->save($complement,true);
            return $this->redirectToRoute("app_show_gestionnaire_update_complement");      
        }
      
        return $this->render('complement/index.html.twig');
    }

    #[Route('/gestionnaire/complement/edit/{idCp}', name: 'app_gestionnaire_edit_complement', methods:['GET'])]
    public function edit($idCp,Request $request,ComplementRepository $repoCp): Response
    {
        $complement=$repoCp->findOneBy(['id'=>$idCp]);
        $datas=$repoCp->findAll();
        return $this->render('complement/index.html.twig',[
            "datas"=>$datas,
            "complement"=>$complement
        ]);
    }

    #[Route('/gestionnaire/complement/destroy/{idCp}', name: 'app_gestionnaire_destroy_complement', methods:['GET'])]
    public function destroy($idCp,ComplementRepository $repoCp): Response
    {
        $complement=$repoCp->find($idCp);
        $repoCp->remove($complement,true);
        return $this->redirectToRoute("app_gestionnaire_show_complement");
    }
}
