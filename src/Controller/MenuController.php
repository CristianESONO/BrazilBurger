<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Burger;
use App\Entity\Frites;
use App\Entity\Boissons;
use App\Entity\Complement;
use Doctrine\ORM\Query\Expr\From;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use App\Repository\FritesRepository;
use App\Repository\BoissonsRepository;
use App\Repository\ComplementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    #[Route('/client/menu', name: 'app_show_menu_burger')]
    public function show(MenuRepository $repoMenu): Response
    {
        $datas=$repoMenu->findAll();
        return $this->render('menu/index.html.twig', [
            "datas"=>$datas
        ]);
    }


    #[Route('/gestionnaire/menu', name: 'app_show_gestionnaire_update_menu')]
    public function showG(MenuRepository $repoMenu): Response
    {
        $datas=$repoMenu->findAll();
        return $this->render('menu/liste.html.twig',[
            "datas"=>$datas
        ]);
    }

    #[Route('/client/detail/menu/{idMenu}', name: 'app_show_client_details_menu')]
    public function showDetails($idMenu,MenuRepository $repoMenu): Response
    {
        $menu=$repoMenu->findOneBy(['id'=>$idMenu]);
        //$datas=$repoBg->findAll();
        return $this->render('menu/detailMenu.html.twig',[
            //"datas"=>$datas,
            "menu"=>$menu
        ]);
    }

    #[Route('/gestionnaire/menu/create', name: 'app_gestionnaire_create_menu')]
    public function create(Request $request,MenuRepository $repoMenu,BurgerRepository $repoBg,BoissonsRepository $repoBoisson,FritesRepository $repoFrite): Response
    {
        $burgers= $repoBg->findAll();
        $Boissons = $repoBoisson->findAll();
        $Frites = $repoFrite->findAll();
        //var_dump($complements);
        $errors=[];
        if($request->request->has('btnSave')){
            $nom=$request->request->get("nom");
            $prix=$request->request->get("prix");
            $image=$request->request->get("img");
            $burger=$request->request->get("burger");
            $boisson=$request->request->get("boisson");
            $frite=$request->request->get("frite");
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
            if(empty($burger)){
                $errors['burger']="Veuillez selectionnez un burger";
            }
            if(empty($boisson)){
                $errors['boisson']="Veuillez selectionnez une boisson";
            }
            if(empty($frite)){
                $errors['frite']="Veuillez selectionnez une frite";
            }
            if(empty($description)){
                $errors['description']="La description est obligatoire";
            }
            if(count($errors)!=0){
                return $this->redirectToRoute("app_gestionnaire_create_menu",[
                    "errors"=>$errors
                ]);
            }
            if(trim($request->request->get("btnSave"))=="create"){
                $menu=new Menu;
            }else{
                $idMenu=$request->request->get("id");
                $menu=$repoMenu->find($idMenu);
            }
            $bgMenu= new Burger;
            $bsMenu = new Boissons;
            $frMenu= new Frites;
            foreach ($burgers as $bg) {
                if($burger==$bg->getNom()){
                    $bgMenu=$bg;
                }
            }
            foreach ($Boissons as $bc) {
                if($boisson==$bc->getNom()){
                    $bsMenu=$bc;
                }
            }

            foreach ($Frites as $frc) {
                if($frite==$frc->getNom()){
                    $frMenu=$frc;
                }
            }
            
             $menu->setNom($nom)
                    ->setPrix($prix)
                    ->setImage($image)
                    ->setBurger($bgMenu)
                    ->setBoissons($bsMenu)
                    ->setFrites($frMenu)
                    ->setDescription($description);
                   
                   
           
            $repoMenu->save($menu,true);
            return $this->redirectToRoute("app_show_gestionnaire_update_menu");     
        }
      
        //return $this->redirectToRoute("app_show_burger");
        return $this->render('menu/nouveau.html.twig',[
            "burgers"=>$burgers,
            "Frites"=>$Frites,
            "Boissons"=>$Boissons
        ]);
        

        
    }

    #[Route('/gestionnaire/menu/edit/{idMenu}', name: 'app_gestionnaire_edit_menu', methods:['GET'])]
    public function edit($idMenu,Request $request,BurgerRepository $repoBg,MenuRepository $repoMenu,BoissonsRepository $repoBoisson,FritesRepository $repoFrite): Response
    {
        $burgers= $repoBg->findAll();
        $menu=$repoMenu->findOneBy(['id'=>$idMenu]);
        $Boissons = $repoBoisson->findAll();
        $Frites = $repoFrite->findAll();
        $datas=$repoMenu->findAll();
        return $this->render('menu/nouveau.html.twig',[
            "datas"=>$datas,
            "burgers"=>$burgers,
            "Frites"=>$Frites,
            "Boissons"=>$Boissons,
            "menu"=>$menu
        ]);
    }

    #[Route('/gestionnaire/burger/destroy/{idMenu}', name: 'app_gestionnaire_destroy_menu', methods:['GET'])]
    public function destroy($idMenu,MenuRepository $repoMenu): Response
    {
        $menu=$repoMenu->find($idMenu);
        $repoMenu->remove($menu,true);
        return $this->redirectToRoute("app_show_gestionnaire_update_menu");
    }

    
}
