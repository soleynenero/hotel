<?php

namespace Hotel\Controller ;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Hotel\Model\GestionServiceDAO ;

class GestionServiceController
{

    // meme code que sur gestionChambreController
    public function affichageServiceAction(Application $app, Request $request)
    {

        $selectService = new GestionServiceDAO($app['db']);
        $service = $selectService->selectService();

        return $app['twig']->render('basic/gestion_services.html.twig', array("services" => $service,));
    }

    public function ajoutChambreAction(Application $app, Request $request)
    {

                
        $prix_service = strip_tags($request->get("prix_service"));
        $nom_service = strip_tags($request->get("nom_service"));     

        $errors ="";

        if(!is_numeric($prix_service)){
            $errors .= "<li>Le prix du service doit etre numérique</li>\n";
            }


        if(iconv_strlen($nom_service) < 2 || iconv_strlen($nom_service) > 20){
            $errors .= "<li>Votre nom de service doit être compris entre 2 et 20 caractères</li>\n";
        }      


        $selectService = new GestionServiceDAO($app['db']);
        $service = $selectService->selectService();


        foreach($service AS $cle => $tab):
            if($nom_service == $tab["nom_service"]):
                $errors.= "<li>Le nom de serivce ". $nom_service." est deja pris </li>\n";
            endif ;
        endforeach ;


        if(!empty($errors)) 
            return $app->json(array("errors" => true, "message" => $errors));


        if(empty($errors))
        {
            $insertService = new GestionServiceDAO($app['db']);
            $idService = $insertService->insertService($nom_service, $prix_service);
            return $app->json( array("errors" => false, "id" => $idService) );
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
      
            $prix_service = strip_tags($request->get("prix_service"));
            $nom_service = strip_tags($request->get("nom_service"));     


            $errors ="";

            if(!is_numeric($prix_service)){
                $errors .= "Le prix du service doit etre numérique\n";
                }


            if(iconv_strlen($nom_service) < 2 || iconv_strlen($nom_service) > 20){
                $errors .= "Votre nom de service doit être compris entre 2 et 20 caractères\n";
            }     


            $modification = new GestionServiceDAO($app['db']);

            if(empty($errors))
                $rowAffect = $modification->modifService($id_services,$nom_service, $prix_service);

            $affichageServiceModifiable = new GestionServiceDAO($app['db']);
            $service = $affichageServiceModifiable->selectmodifService($id_services);

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

            $suppression = new GestionServiceDAO($app['db']);
            $deleteservice = $suppression->deleteService($id_services);

            return $app->redirect('/hotel/public/admin/gestion_services');
            

        }

}
