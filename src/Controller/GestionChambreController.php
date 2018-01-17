<?php

    namespace Hotel\Controller ;
        use Silex\Application;
        use Symfony\Component\HttpFoundation\Request;
        use Hotel\Model\GestionChambreDAO ;

    class GestionChambreController
    {
        public function affichageChambreAction(Application $app, Request $request)
        {
            // on appelle la classe GestionChambreDAO pour se connecter a la bdd et recupérer les informations des membres
            $affichageChambre = new GestionChambreDAO($app['db']);
            // ici je stock les informations de mes utilisateur dans un tableau appelé membre
            $chambre = $affichageChambre->selectChambre();
            // echo "<pre>";
            // print_r($chambre);
            // echo "</pre>";
            // die();
            
            // je renvoie sur la page du gestion_chambres les informations de mes membres dans un array
            return $app['twig']->render('basic/gestion_chambres.html.twig', array("chambres" => $chambre));
        }
    }