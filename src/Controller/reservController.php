<?php

namespace Hotel\Controller;

    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Hotel\Model\ReservDAO;

    class ReservationControl { 

        private $reserv_error;

        public function verifAction(Application $app, Request $request){

            $idUser = htmlspecialchars(trim($request->get("user_id")));
            $nom = htmlspecialchars(trim($request->get("nom"))); 
            $prenom = htmlspecialchars(trim($request->get("prenom"))); 
            $email = htmlspecialchars(trim($request->get("email"))); 
            $cat1 = htmlspecialchars(trim($request->get("id_categorie1"))); 
            $debut1 = htmlspecialchars(trim($request->get("date_debut1"))); 
            $nbPerson1 = htmlspecialchars(trim($request->get("nb_personne1"))); 
            $fin1 = htmlspecialchars(trim($request->get("date_fin1")));
            $categorie = $app['db']->fetchAll("SELECT * FROM categorie_chambre"); //permet de voir les catégories de chambres

            
            if(empty($idUser)){ // si l'utilisateur n'est pas identifié
                $this->reserv_error = "Merci de vous connecter avant de réserver une chambre.";
                $listServices = ReservDAO::listServices($app['db']);
                return $app['twig']->render('index.html.twig', array(
                'reserv_error' => $this->reserv_error,
                "date_debut" => $debut1,
                "date_fin" => $fin1,
                "nbPersonne" => $nbPerson1,
                "categorie" => $cat1,
                "categories" => $categorie,
                "listService" => $listServices));
            }

            if(empty($nom) || empty($prenom) || empty($email) || empty($cat1) || empty($nbPerson1) || empty($debut1) || empty($fin1)) {// si des champs sont vides
                $this->reserv_error = "Vous devez remplir tous les champs, svp !";
                $listServices = ReservDAO::listServices($app['db']);
                return $app['twig']->render('index.html.twig', array(
                    'reserv_error' => $this->reserv_error,
                    "id_user" => $_SESSION['user']['user_id'],
                    "prenom" => $_SESSION['user']['prenom'],
                    "nom" => $_SESSION['user']['nom'],
                    "email" => $_SESSION['user']['email'],
                    "date_debut" => $debut1,
                    "date_fin" => $fin1,
                    "nbPersonne" => $nbPerson1,
                    "categorie" => $cat1,
                    "categories" => $categorie,
                    "listService" => $listServices
                )); // retour à la réservation 
            }

            /* contrôle des dates */
            $today = date("Y-m-d");

            if($debut1 < $today){
                $this->reserv_error = "Erreur : Le début du séjour est antérieur à la date actuelle";
                $listServices = ReservDAO::listServices($app['db']);
                return $app['twig']->render('index.html.twig', array(
                'reserv_error' => $this->reserv_error,
                "id_user" => $_SESSION['user']['user_id'],
                "prenom" => $_SESSION['user']['prenom'],
                "nom" => $_SESSION['user']['nom'],
                "email" => $_SESSION['user']['email'],
                "date_debut" => $debut1,
                "date_fin" => $fin1,
                "nbPersonne" => $nbPerson1,
                "categorie" => $cat1,
                "categories" => $categorie,
                "listService" => $listServices
                ));
            }

            if($fin1 < $debut1){
                $this->reserv_error = "Erreur : La fin du séjour est antérieure à la date de début !";
                $listServices = ReservDAO::listServices($app['db']);
                return $app['twig']->render('index.html.twig', array(
                'reserv_error' => $this->reserv_error,
                "id_user" => $_SESSION['user']['user_id'],
                "prenom" => $_SESSION['user']['prenom'],
                "nom" => $_SESSION['user']['nom'],
                "email" => $_SESSION['user']['email'],
                "date_debut" => $debut1,
                "date_fin" => $fin1,
                "nbPersonne" => $nbPerson1,
                "categorie" => $cat1,
                "categories" => $categorie,
                "listService" => $listServices
                ));
            }
            /* fin contrôle des dates*/

            if($request->get('idserv')){ // si des services ont été sélectionnés
                foreach($request->get('idserv') as $key => $val){
                    $key = htmlspecialchars(trim($val)); // on sécurise les valeurs de l'array généré par les checkboxes dans le formulaire
                }

                $idserv =  $request->get('idserv'); // on stocke l'array dans une variable
            }
            
            else{
                $idserv = []; // si aucun service n'est demandé on créé un array vide
            }

            $insert = new ReservDAO($app['db']); // appel du modèle ReservDAO
            $resultat = $insert->recupEmail($idUser, $email); // on récupère en bdd l'email de l'user

            if($resultat['email'] == $email) { // si l'id correspond au mail renseigné

                $req = $insert->insertReserv($idUser, $nbPerson1, $debut1, $fin1, $cat1, $idserv); // on créé la réservation en bdd
                if($req != "erreur"){ // si la réservation est possible (chambre dispo)

                    $insert->factureReserv(); // on génère la facture
                    $reserv = $insert->recapReserv(); // on passe les infos dans la page de recap avant paiement
                    return $app['twig']->render('basic/validation_reservation.html.twig', array('reserv' => $reserv));
                }

                else { // si pas de chambre, retour à la réservation 
                    $this->reserv_error = "Il n'y a plus de chambre disponible. Changez de catégorie de chambre, de date de séjour ou le nombre de personne.";
                    $listServices = ReservDAO::listServices($app['db']);
                    return $app['twig']->render('index.html.twig', array(
                    'reserv_error' => $this->reserv_error,
                    "id_user" => $_SESSION['user']['user_id'],
                    "prenom" => $_SESSION['user']['prenom'],
                    "nom" => $_SESSION['user']['nom'],
                    "email" => $_SESSION['user']['email'],
                    "date_debut" => $debut1,
                    "date_fin" => $fin1,
                    "nbPersonne" => $nbPerson1,
                    "categorie" => $cat1,
                    "categories" => $categorie,
                    "listService" => $listServices
                    )); 
                }

            }
            else{ // si problème d'email ou d'id_user, retour à la réservation
                $this->reserv_error = "Vos identifiants sont incorrects, veuillez renseigner votre mail de connexion. Si le problème persiste, déconnectez-vous et reconnectez-vous.";
                return $app['twig']->render('index.html.twig', array(
                    'reserv_error' => $this->reserv_error,
                    "categories" => $categorie,
                    // "listService" => $listServices
                ));  
            }
        } // fin de verifAction

    }

?>