<?php

    namespace Hotel\Controller ;
        use Silex\Application;
        use Symfony\Component\HttpFoundation\Request;
        use Hotel\Model\GestionMembreDAO ;

    class GestionMembreController
    {
        public function affichageMembreAction(Application $app, Request $request)
        {
            // on appelle la classe GestionMembreDAO pour se connecter a la bdd et recupérer les informations des membres
            $affichageMembre = new GestionMembreDAO($app['db']);
            // ici je stock les informations de mes utilisateur dans un tableau appelé membre
            $membre = $affichageMembre->selectMembre();
            // echo "<pre>";
            // print_r($membre);
            // echo "</pre>";
            // die();
            
            // je renvoie sur la page du gestion_membre les informations de mes membres dans un array
            return $app['twig']->render('basic/gestion_membres.html.twig', array("membres" => $membre));
        }
    }