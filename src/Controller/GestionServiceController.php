<?php

namespace Hotel\Controller ;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Hotel\Model\GestionServiceDAO ;

class GestionServiceController
{

    // affichage des informations de mes chambres , la capacité et la categorie pour le select du formulaire
    public function affichageServiceAction(Application $app, Request $request)
    {

        $selectService = new GestionServiceDAO($app['db']);
        $service = $selectService->selectService();

        return $app['twig']->render('basic/gestion_services.html.twig', array("services" => $service,));
    }

    public function ajoutChambreAction(Application $app, Request $request)
        {

            // recuperation de l'insertion de l'utilisateur        
            $prix_service = strip_tags($request->get("prix_service"));
            $nom_service = strip_tags($request->get("nom_service"));     
  
            // print_r($telephone);
            // print_r($prix);
            // die();

            $errors ="";

            if(!is_numeric($prix_service)){
                $errors .= "Le prix du service doit etre numérique\n";
                }


            if(iconv_strlen($nom_service) < 2 || iconv_strlen($nom_service) > 20){
                $errors .= "Votre nom de service doit être compris entre 2 et 20 caractères\n";
            }      


            // je reutilise les elements qu'il y a dans ma fonction precedente pour ne pas avoir de message d'erreur

            $selectService = new GestionServiceDAO($app['db']);
            $service = $selectService->selectService();


            foreach($service AS $cle => $tab):
                if($nom_service == $tab["nom_service"]):
                    $errorsNom = "Le nom de serivce ". $nom_service." est deja pris \n";
                endif ;
            endforeach ;

                    
                    // var_dump($chambre);
                    // die();
            if(!empty($errors) || !empty($errorsNom))
            {
                
                return $app['twig']->render('basic/gestion_services.html.twig', array(
                "error" => $errors,
                "errorsNom" => $errorsNom,
                "services" => $service,));
            }

            // on appelle la classe GestionChambreDAO pour se connecter a la bdd et recupérer les informations des membres
            if(empty($errors) && empty($errorsNom))
            {
                $insertService = new GestionServiceDAO($app['db']);
                $newservice = $insertService->insertService($nom_service, $prix_service);
                return $app['twig']->render('basic/gestion_services.html.twig', array(
                    "msgValidation" => "Merci d'avoir inséré un nouveau service",
                    "services" =>  $service,));
            }

        }

        public function selectModifServiceAction(Application $app, Request $request , $id_services)
        {
            $affichageServiceModifiable = new GestionServiceDAO($app['db']);
            $service = $affichageServiceModifiable->selectmodifService($id_services);
    
            return $app['twig']->render('basic/modification_service.html.twig', array("services" => $service,));
        }

        public function updateModifServiceAction(Application $app, Request $request , int $id_services)
        {
            // recuperation de l'insertion de l'utilisateur        
            $prix_service = strip_tags($request->get("prix_service"));
            $nom_service = strip_tags($request->get("nom_service"));     
  
            // print_r($telephone);
            // print_r($prix);
            // die();

            $errors ="";

            if(!is_numeric($prix_service)){
                $errors .= "Le prix du service doit etre numérique\n";
                }


            if(iconv_strlen($nom_service) < 2 || iconv_strlen($nom_service) > 20){
                $errors .= "Votre nom de service doit être compris entre 2 et 20 caractères\n";
            }     

            // on appelle la classe GestionChambreDAO pour se connecter a la bdd et recupérer les informations des membres
            $modification = new GestionServiceDAO($app['db']);
            // ici je stock les informations de mes utilisateur dans un tableau appelé membre
            // pour voir les lignes affecter
            if(empty($errors))
                $rowAffect = $modification->modifService($id_services,$nom_service, $prix_service);

            $affichageServiceModifiable = new GestionServiceDAO($app['db']);
            $service = $affichageServiceModifiable->selectmodifService($id_services);


            // s'il y a des erreurs mettre le msg d'erreur
            if(!empty($errors))
            {
                
                return $app['twig']->render('basic/modification_service.html.twig', array(
                "error" => $errors,
                "services" =>  $service,));
            }
            
            return $app['twig']->render('basic/modification_service.html.twig', array("services" =>  $service,
                                                                                    "msgValidation" => "Votre chambre a bien été modifié",));
        }
        public function deleteServiceAction(Application $app, Request $request, $id_services)
        {
            // $selectService = new GestionServiceDAO($app['db']);
            // $service = $selectService->selectService();

            $suppression = new GestionServiceDAO($app['db']);
            $deleteservice = $suppression->deleteService($id_services);
            // echo "<pre>";
            // print_r($deleteservice);
            // echo "</pre>";
            // die();
            // echo "<pre>";
            // print_r($capacite);
            // echo "</pre>";
            // die();
            // "msgValidation" => $msgValidation
            
            // $msgValidationSup = "La chambre $numero_chambre a bien été supprimé";

            return $app->redirect('/hotel/public/admin/gestion_services');
            
            // return $app['twig']->render('basic/gestion_services.html.twig', array("services" => $service,));
        }

}
