<?php

    namespace Hotel\Controller;

    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Hotel\Model\TokensDAO;
    use Hotel\Model\ConnexionDAO;
    use Silex\Controller;
    use \DateTime;

    class ConnexionController
    {
        protected $stockMdp = "";
        protected $errors;

        public function login(Request $request, Application $app){
           
            $email = strip_tags(trim($request->request->get('email')));//modification des balises html
            $mdp = strip_tags(trim($request->request->get('mdp')));
            //suppresison des balise html php

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //verification de l'email
                return $app["twig"]->render("basic/inscription.html.twig");
            }

            $sql = "SELECT * FROM user WHERE email = ?";
            $user = $app['db']->fetchAssoc($sql, array((string) $email));

            // if($user == false)
            //     return $app->redirect("connexion");


            if($mdp == $user['mdp']){ //Si mdp bdd = mdp rentré par l'utilisateur

                
                $app['db']->delete('tokens', array('user_id' => 1, "type" =>"connexion")); //on efface le token de type connection de l'utilisateur qui resterait dans la bdd

                $token = $this->generateToken(); // on regenere un token tout beau qui sent le neuf
                $dateExpire = $this->expireToken(); // on gere l'expiration du token


                $app['db']->insert('tokens', array( // Insertion dans la BDD du token 
                    'token' => $token,
                    'date_expiration' => $dateExpire,
                    'user_id' => $user['user_id'],
                    'type' => 'connexion',
                    )
                );

                unset($user['mdp']);  // on ne veux pas garder le mdp
                $user['token'] = $token;
                $user['dateEnd'] = $dateExpire;
                $_SESSION["user"] = $user; //creation de la session
                // echo '<pre>';
                // var_dump($_SESSION);
                // echo '</pre>';
                // die();

                setcookie("hotel", $token, time()+3600 * 24); //Creation du Cookie
                // var_dump($_COOKIE);

                if ($user['statut'] == 'admin') { // Redirection vers le back si l'utilisateur est un admin
                    return $app->redirect('admin');
                }
                
                return $app['twig']->render('index.html.twig', array(
                    "id_user" => $_SESSION['user']['user_id'],
                    "prenom" => $_SESSION['user']['prenom'],
                    "nom" => $_SESSION['user']['nom'],
                    "email" => $_SESSION['user']['email']
                    )); // Si pas admn retirection vers la home
            }else {
                $errors = "email ou mot de passe renseigné incorrect"; // si mauvais mdp on remplit la var errors
            }


            // possibilité de remonter ça dans le else du desssus ??
            if (!empty($errors)) { 
                return $app["twig"]->render('basic/connexion.html.twig', array("error" => $errors)); 
                //Si erreur on renvoi vers la page de co' avec la variable errors
                }

            
            return $app['twig']->render('index.html.twig', array()); //A supprimer ?? doublon de la ligne 58

        }

        private function generateToken(){ // fonction de generation de token
            return substr( md5( uniqid().mt_rand() ), 0, 22 );
        }

        private function expireToken(){ // fonction de generation de la date d'expiration du token
            $dateNow = new DateTime();
            $dateNow->modify("+ 1 day");
            return $dateNow->format("Y-m-d H:i:s");
        }                         
    }

    