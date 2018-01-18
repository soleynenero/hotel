<?php

namespace Hotel\Controller;

    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Hotel\Model\ReservDAO;

    class ReservationControl { 

        public function verifAction(Application $app, Request $request){

            $idUser = htmlspecialchars(trim($request->get("user_id")));
            $nom = htmlspecialchars(trim($request->get("nom"))); 
            $prenom = htmlspecialchars(trim($request->get("prenom"))); 
            $email = htmlspecialchars(trim($request->get("email"))); 
            $cat1 = htmlspecialchars(trim($request->get("id_categorie1"))); 
            $cat2 = htmlspecialchars(trim($request->get("id_categorie2"))); 
            $nbPerson1 = htmlspecialchars(trim($request->get("nb_personne1"))); 
            $nbPerson2 = htmlspecialchars(trim($request->get("nb_personne2"))); 
            $debut1 = htmlspecialchars(trim($request->get("date_debut1"))); 
            $debut2 = htmlspecialchars(trim($request->get("date_debut2"))); 
            $fin1 = htmlspecialchars(trim($request->get("date_fin1"))); 
            $fin2 = htmlspecialchars(trim($request->get("date_fin2")));
            
            if($request->get('idserv')){ // si des services ont été sélectionnés
                foreach($request->get('idserv') as $key => $val){
                    $key = htmlspecialchars(trim($val)); // on sécurise les valeurs de l'array généré par les checkboxes dans le formulaire
                }

                $idserv =  $request->get('idserv'); // on stocke l'array dans une variable
            }
            
            else{
                $idserv = []; // si aucun service n'est demandé on créé un array vide
            }
       
            // echo '<pre>'; var_dump($idserv); echo '</pre>';

            if(empty($idUser)){ // si l'utilisateur n'est pas identifié
                return $app['twig']->render('basic/connexion.html.twig'); // demande de connexion
            }

            if(empty($nom) || empty($prenom) || empty($email) || empty($cat1) || empty($nbPerson1) || empty($debut1) || empty($fin1)) {// si la 1ère chambre réservée est vide
                return $app['twig']->render('index.html.twig'); // retour à la réservation 
            }

            $insert = new ReservDAO($app['db']);
            $resultat = $insert->recupEmail($idUser, $email);

            if($resultat['email'] == $email) { // si l'id correspond au mail renseigné

                // echo '<p style="color: black">Validation OK<p>';
                $insert->insertReserv($idUser, $nbPerson1, $debut1, $fin1, $cat1, $idserv);
                $insert->factureReserv();
                $idFacture = $insert->getIdFacture();
                return $app['twig']->render('basic/validation_reservation.html.twig', array("reservation"=>$idFacture));
            }

            return $app['twig']->render('index.html.twig');

        }

    }

?>