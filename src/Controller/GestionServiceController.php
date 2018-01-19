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
}
