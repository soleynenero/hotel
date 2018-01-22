<?php

    namespace Hotel\Controller ;
        use Silex\Application;
        use Symfony\Component\HttpFoundation\Request;
        use Hotel\Model\GestionReservationDAO ;

    class GestionReservationController
    {

        public function affichageReservationAction(Application $app, Request $request)
        {
            $affichageReservation = new GestionReservationDAO($app['db']);
            $reservation = $affichageReservation->selectReservation(false);
            // echo '<pre>';
            // var_dump($reservation);
            // echo '</pre>';
            // die();
            return $app['twig']->render('basic/gestion_reservations.html.twig', array(
                "reservations" => $reservation,
            ));
        }

        public function selectModifReservationAction(Application $app, Request $request , $id_reservation)
        {
            $affichageReservationModifiable = new GestionReservationDAO($app['db']);

            $reservation = $affichageReservationModifiable->selectmodifReservation($id_reservation);
            if(!$reservation)
                $reservation = $affichageReservationModifiable->selectmodifReservation(3);
            // echo '<pre>';
            // var_dump($id_reservation);
            // echo '</pre>';
            // echo '<pre>';
            // var_dump($reservation);
            // echo '</pre>';
            // die();

            
            // je renvoie sur la page du gestion_reservation les informations de ma reservation et des informations dans mon select qui est un array
            return $app['twig']->render('basic/modification_reservation.html.twig', array(
                "reservations" => $reservation,
            ));
        }

        public function updateModifReservationAction(Application $app, Request $request , int $id_reservation)
        {
            // recuperation de l'insertion de l'utilisateur       
            $date_debut = $request->get("date_debut");
            $date_fin = $request->get("date_fin");
            $nb_personne = $request->get("nb_personne");

            // echo "<pre>";
            // print_r($date_debut);
            // echo "</pre>";
            // echo "<pre>";
            // print_r($nb_personne);
            // echo "</pre>";
            // echo "<pre>";
            // print_r($date_fin);
            // echo "</pre>";
            // die();
            $errors ="";
            

            if($date_fin < $date_debut){
               
                $errors .= "La date de départ est toujours ultérieure a la date d'arrivée (Idiot)\n";
                }

            // on appelle la classe GestionReservationDAO pour se connecter a la bdd 

            $voirReservation = new GestionReservationDAO($app['db']);
            
            // ici je stock les informations 


            // pour voir les lignes affecter
            if(empty($errors))
                $rowAffect = $voirReservation->modifReservation($id_reservation, $date_debut, $date_fin, $nb_personne);


            $affichageReservationModifiable = new GestionReservationDAO($app['db']);
            $reservation = $affichageReservationModifiable->selectmodifReservation($id_reservation);

            // s'il y a des erreurs mettre le msg d'erreur
            if(!empty($errors))
            {
                return $app['twig']->render('basic/modification_reservation.html.twig', array(
                "error" => $errors,
                "reservations" => $reservation,
                ));
            }

            $affichageReservation = new GestionReservationDAO($app['db']);
            $reservation = $affichageReservation->selectReservation(false);
            
            return $app['twig']->render('basic/gestion_reservations.html.twig', array(
            "msgValidation" => "La modification de la reservation a bien été prise en compte",

            "reservations" => $reservation,
            // "numcommande" => $numcommande,
            // "nomcomplet" => "$nomcomplet",
            // "numeroFacture" => "$numeroFacture",
            // "datecommande" => "$datecommande",
            // "datearrivee" => "$datearrivee",
            // "datedepart" => "$datedepart",
            // "nbpersonne" => "$nbpersonne",
            ));
        }






    }