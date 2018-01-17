<?php

    namespace Hotel\Controller ;
    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Hotel\Model\UserDAO ;
    use Hotel\Model\InfoReservationDAO ;

    class InfoUserController
    {


        // fonction permettant l'affichage des données de l'utilisateur
        public function affichageUserAction(Application $app, Request $request)
        {
            // on appelle la classe UserDAO pour se connecter a la bdd et recupérer les informations de l'utilisateur
            $affichageUser = new UserDAO($app['db']);
            // ici je stock les informations de mon utilisateur dans ma variable user qui est un array
            $user = $affichageUser->selectUser($_SESSION['user']['user_id']);
            
            // je renvoie sur la page du profil_membre quand j'ai toutes les informations et les informations que je renvoie sur ma page est les informations de mon utilisateur qui est un array
            return $app['twig']->render('basic/profil_membre.html.twig', $user);
        }

        public function affichageFormAction(Application $app, Request $request)
        {
            $affichageForm = new UserDAO($app['db']);
            $affichageFormUser = $affichageForm->affichageForm($_SESSION['user']['user_id']);
            return $app['twig']->render('basic/modification_profil.html.twig', $affichageFormUser);
        }

        public function affichageReservationAction(Application $app, Request $request)
        {
            $affichageReservation = new InfoReservationDAO($app['db']);
            $reservations = $affichageReservation->selectReservation($_SESSION['user']['user_id']);
            // echo "<pre>";
            // print_r($reservations);
            // echo "</pre>";
            // die();
            return $app['twig']->render('basic/reservation.html.twig', array("reservations" => $reservations));
        }

        public function modificationProfilAction(Application $app, Request $request)
        {
           
            // ici je recupere les informations qu'il y a dans mon formulaire grace aux get et je fais un strip_tags et htmlspecialchars pour la securite pour que l'utilisateur ne nous envoie pas d'element qui pourront casser le site

            $prenom = strip_tags($request->get("prenom"));
            $nom = strip_tags($request->get("nom"));
            $adresse = htmlspecialchars($request->get("adresse"));
            $ville = strip_tags($request->get("ville"));
            $code_postal = strip_tags($request->get("code_postal"));
            $telephone = strip_tags($request->get("telephone"));
            $email = htmlspecialchars($request->get("email")); 

            $errors ="";
            if(iconv_strlen($prenom) < 2 || iconv_strlen( $prenom )> 15){
                $errors .= "Votre nom doit être compris entre 2 et 15 caractères";
                }
            if(iconv_strlen($nom) < 2 || iconv_strlen($nom) > 15){
                $errors .= "Votre nom doit être compris entre 2 et 15 caractères";
            }

            if(!is_numeric($code_postal) || iconv_strlen($code_postal) != 5){
                $errors .= "Votre code postal n'est pas au bon format : 0102030405";
                }

            if(!is_numeric($telephone) || iconv_strlen($telephone) != 10){
                $errors .= "Votre telephone n'est pas au bon format : 0102030405";
            }          

        
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors .= "Votre adresse mail n'est pas au bon format : hotel@hotel.com";
            }
        
            if(!empty($errors))
            {
                return $app['twig']->render('basic/modification_profil.html.twig', array(
                "error" => $errors ,
                "prenom" => $prenom , 
                "nom" => $nom , 
                "email" => $email , 
                "telephone" => $telephone , 
                "ville" => $ville , 
                "code_postal" => $code_postal , 
                "adresse" => $adresse,));
            } ;


            $modification = new UserDAO($app['db']);
            $modificationUser = $modification->modificationFormUser($prenom , $nom , $email , $telephone , $ville , $code_postal , $adresse, $_SESSION['user']['user_id']);
            // ici je dois mettre mes elements dans un tableau associatif sinon ca ne fonctionne pas
            return $app['twig']->render('basic/modification_profil.html.twig', array(
                "prenom" => $prenom , 
                "nom" => $nom , 
                "email" => $email , 
                "telephone" => $telephone , 
                "ville" => $ville , 
                "code_postal" => $code_postal , 
                "adresse" => $adresse,
                "msgmodif" => "votre demande de modification a bien été enregistré",
                "user_id" => $_SESSION['user']['user_id'],
                ));
        }

        public function modifMdpAction(Application $app, Request $request)
        {
            $email = htmlspecialchars($request->get("email"));
            $mdpvieux = htmlspecialchars($request->get("mdpvieux"));
            $mdp = htmlspecialchars($request->get("mdp"));

            $selectUser = new UserDAO($app['db']);
            $user = $selectUser->selectModifMdp($_SESSION['user']['user_id']);

            if($user["email"] == $email && $user['mdp'] == $mdpvieux)
            {
                $modifMdp = new UserDAO($app['db']);
                $modification = $modifMdp->modificationMdpUser($mdp , $_SESSION['user']['user_id']);
                return $app['twig']->render('basic/modification_mdp.html.twig', array(
                    "email" => $email , 
                    "mdp" => $mdp ,
                    "msgmodif" => "votre demande de modification a bien été enregistré",
                    "id" => $_SESSION['user']['user_id']));
            }
            else
                $errors = "Mot de passe ou adresse mail renseigné incorrect";

            if(!empty($errors))
            {
                return $app['twig']->render('basic/modification_mdp.html.twig', array("error" => $errors));
            } ;
        }
    }