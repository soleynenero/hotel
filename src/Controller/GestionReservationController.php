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


        //---------------------------------------Soleyne

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
                "reservation" => $reservation,
            ));
        }

        // public function updateModifChambreAction(Application $app, Request $request , int $id_chambres)
        // {
        //     // recuperation de l'insertion de l'utilisateur        
        //     $id_categorie = $request->get("id_categorie");
        //     $id_capacite = $request->get("id_capacite"); 
        //     $statut = $request->get("statut"); 
        //     $telephone = strip_tags($request->get("telephone"));
        //     $prix = htmlspecialchars($request->get("prix"));

        //     $errors ="";


        //     if(!is_numeric($prix)){
        //         $errors .= "Le prix doit etre numérique\n";
        //         }

        //     if(!is_numeric($telephone) || iconv_strlen($telephone) != 10){
        //         $errors .= "Votre telephone n'est pas au bon format : 0102030405\n";
        //     }     

        //     // on appelle la classe GestionChambreDAO pour se connecter a la bdd et recupérer les informations des membres
        //     $voirChambre = new GestionChambreDAO($app['db']);
        //     // ici je stock les informations de mes utilisateur dans un tableau appelé membre
        //     // pour voir les lignes affecter
        //     if(empty($errors))
        //         $rowAffect = $voirChambre->modifChambre($id_categorie , $id_capacite , $telephone , $prix , $statut, $id_chambres);

        //     $affichageChambreModifiable = new GestionChambreDAO($app['db']);
        //     $chambre = $affichageChambreModifiable->selectmodifChambre($id_chambres);

        //     $affichageCapacite = new GestionChambreDAO($app['db']);
        //     $capacite = $affichageCapacite->selectCapacite();

        //     $affichageCategorie = new GestionChambreDAO($app['db']);
        //     $categorie = $affichageCategorie->selectCategorie();

        //     $affichageStatut = new GestionChambreDAO($app['db']);
        //     $statut = $affichageStatut->selectStatut();

        //     // s'il y a des erreurs mettre le msg d'erreur
        //     if(!empty($errors))
        //     {
                
        //         return $app['twig']->render('basic/modification_chambre.html.twig', array(
        //         "error" => $errors,
        //         "chambres" =>  $chambre,
        //         "capacites" => $capacite,
        //         "categories" => $categorie,
        //         "statuts" => $statut));
        //     }


        //     // echo "<pre>";
        //     // print_r($capacite);
        //     // echo "</pre>";
        //     // die();
            
        //     // j'ai un message de modification de ma chambr
            

            
        //     return $app['twig']->render('basic/modification_chambre.html.twig', array("chambres" => $chambre,
        //                                                                             "capacites" => $capacite,
        //                                                                             "msgValidation" => "Votre chambre a bien été modifié",
        //                                                                             "categories" => $categorie,
        //                                                                             "statuts" => $statut));
        // }






    }