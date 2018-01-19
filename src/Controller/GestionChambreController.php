<?php

    namespace Hotel\Controller ;
        use Silex\Application;
        use Symfony\Component\HttpFoundation\Request;
        use Hotel\Model\GestionChambreDAO ;

    class GestionChambreController
    {

        // affichage des informations de mes chambres , la capacité et la categorie pour le select du formulaire
        public function affichageChambreAction(Application $app, Request $request)
        {
            // on appelle la classe GestionChambreDAO pour se connecter a la bdd et recupérer les informations des membres
            $affichageChambre = new GestionChambreDAO($app['db']);
            // ici je stock les informations de mes utilisateur dans un tableau appelé membre
            $chambre = $affichageChambre->selectChambre();

            $affichageCapacite = new GestionChambreDAO($app['db']);
            $capacite = $affichageCapacite->selectCapacite();

            $affichageCategorie = new GestionChambreDAO($app['db']);
            $categorie = $affichageCategorie->selectCategorie();
            // echo "<pre>";
            // print_r($capacite);
            // echo "</pre>";
            // die();

            
            // je renvoie sur la page du gestion_chambres les informations de ma chambre et des informations dans mon select qui est un array
            return $app['twig']->render('basic/gestion_chambres.html.twig', array("chambres" => $chambre,
                                                                                    "capacites" => $capacite,
                                                                                    "categories" => $categorie));
        }

        // lorsque l'utilisateur va vouloir ajouter une nouvelle chambre

        public function ajoutChambreAction(Application $app, Request $request)
        {

            // recuperation de l'insertion de l'utilisateur        
            $id_categorie = $request->get("id_categorie");
            $id_capacite = $request->get("id_capacite");     
            $numero_chambre = strip_tags($request->get("numero_chambre"));
            $telephone = strip_tags($request->get("telephone"));
            $prix = htmlspecialchars($request->get("prix"));

            // print_r($id_categorie);
            // print_r($id_capacite);
            // print_r($numero_chambre);
            // print_r($telephone);
            // print_r($prix);
            // die();

            $errors ="";

            if(!is_numeric($numero_chambre)){
                $errors .= "Le numéro de chambre doit etre numérique\n";
                }

            if(!is_numeric($prix)){
                $errors .= "Le prix doit etre numérique\n";
                }

            if(!is_numeric($telephone) || iconv_strlen($telephone) != 10){
                $errors .= "Votre telephone n'est pas au bon format : 0102030405\n";
            }          

            // je reutilise les elements qu'il y a dans ma fonction precedente pour ne pas avoir de message d'erreur


            $affichageChambre = new GestionChambreDAO($app['db']);
            // ici je stock les informations de mes utilisateur dans un tableau appelé membre
            $chambre = $affichageChambre->selectChambre();

            $affichageCapacite = new GestionChambreDAO($app['db']);
            $capacite = $affichageCapacite->selectCapacite();

            $affichageCategorie = new GestionChambreDAO($app['db']);
            $categorie = $affichageCategorie->selectCategorie();

            foreach($chambre AS $cle => $tab):
                if($numero_chambre == $tab["numero_chambre"]):
                    $errorsnb = "Le numero de chambre ". $numero_chambre." est deja pris \n";
                endif ;
            endforeach ;
        
            if(!empty($errors))
            {
                
                return $app['twig']->render('basic/gestion_chambres.html.twig', array(
                "error" => $errors,
                "errornb" => $errorsnb,
                "chambres" =>  $chambre,
                "capacites" => $capacite,
                "categories" => $categorie));
            }

            // on appelle la classe GestionChambreDAO pour se connecter a la bdd et recupérer les informations des membres
            $Chambre = new GestionChambreDAO($app['db']);
            $nlleChambre = $Chambre->insertChambre($numero_chambre, $id_categorie, $id_capacite, $telephone, $prix);
            return $app['twig']->render('basic/gestion_chambres.html.twig', array(
                "msgValidation" => "Merci d'avoir inséré une nouvelle chambre",
                "chambres" =>  $chambre,
                "capacites" => $capacite,
                "categories" => $categorie));
        }

        public function selectModifChambreAction(Application $app, Request $request , $id_chambres)
        {
            // on appelle la classe GestionChambreDAO pour se connecter a la bdd et recupérer les informations des membres
            $affichageChambreModifiable = new GestionChambreDAO($app['db']);
            // ici je stock les informations de mes utilisateur dans un tableau appelé membre
            $chambre = $affichageChambreModifiable->selectModifChambre($id_chambres);

            $affichageCapacite = new GestionChambreDAO($app['db']);
            $capacite = $affichageCapacite->selectCapacite();

            $affichageCategorie = new GestionChambreDAO($app['db']);
            $categorie = $affichageCategorie->selectCategorie();

            
            // je renvoie sur la page du gestion_chambres les informations de ma chambre et des informations dans mon select qui est un array
            return $app['twig']->render('basic/modification_chambre.html.twig', array("chambres" => $chambre,
                                                                                    "capacites" => $capacite,
                                                                                    "categories" => $categorie));
        }

        public function updateModifChambreAction(Application $app, Request $request)
        {

            // recuperation de l'insertion de l'utilisateur        
            $id_categorie = $request->get("id_categorie");
            $id_capacite = $request->get("id_capacite"); 
            $statut = $request->get("statut"); 
            $numero_chambre = strip_tags($request->get("numero_chambre"));
            $telephone = strip_tags($request->get("telephone"));
            $prix = htmlspecialchars($request->get("prix"));

            $errors ="";

            if(!is_numeric($numero_chambre)){
                $errors .= "Le numéro de chambre doit etre numérique\n";
                }

            if(!is_numeric($prix)){
                $errors .= "Le prix doit etre numérique\n";
                }

            if(!is_numeric($telephone) || iconv_strlen($telephone) != 10){
                $errors .= "Votre telephone n'est pas au bon format : 0102030405\n";
            }     

            // on appelle la classe GestionChambreDAO pour se connecter a la bdd et recupérer les informations des membres
            $affichageChambreModifiable = new GestionChambreDAO($app['db']);
            // ici je stock les informations de mes utilisateur dans un tableau appelé membre
            $chambre = $affichageChambreModifiable->selectModifChambre($numero_chambre , $id_categorie , $id_capacite , $telephone , $prix , $statut, $id_chambres);

            $affichageCapacite = new GestionChambreDAO($app['db']);
            $capacite = $affichageCapacite->selectCapacite();

            $affichageCategorie = new GestionChambreDAO($app['db']);
            $categorie = $affichageCategorie->selectCategorie();

            // verification si le num de chambre est le meme
            foreach($chambre AS $cle => $tab):
                if($numero_chambre == $tab["numero_chambre"]):
                    $errorsnb = "Le numero de chambre ". $numero_chambre." est deja pris \n";
                endif ;
            endforeach ;

            // s'il y a des erreurs mettre le msg d'erreur
            if(!empty($errors))
            {
                
                return $app['twig']->render('basic/modification_chambre.html.twig', array(
                "error" => $errors,
                "chambres" =>  $chambre,
                "capacites" => $capacite,
                "categories" => $categorie));
            }
            // echo "<pre>";
            // print_r($capacite);
            // echo "</pre>";
            // die();
            
            // j'ai un message de modification de ma chambre
            $msgValidation = "La chambre $numero_chambre a bien été modifié";

            
            return $app['twig']->render('basic/modification_chambre.html.twig', array("chambres" => $chambre,
                                                                                    "capacites" => $capacite,
                                                                                    "categories" => $categorie,
                                                                                    "msgValidation" => $msgValidation));
        }
        public function deleteChambreAction(Application $app, Request $request, $id_chambres)
        {
            $affichageCategorie = new GestionChambreDAO($app['db']);
            $categorie = $affichageCategorie->deleteChambre($id_chambres);
            // echo "<pre>";
            // print_r($capacite);
            // echo "</pre>";
            // die();
            $msgValidationSup = "La chambre $numero_chambre a bien été supprimé";

            return $app->redirect('/hotel/public/admin/gestion_chambres');
            
            return $app['twig']->render('basic/gestion_chambres.html.twig', array("chambres" => $chambre,
                                                                                    "capacites" => $capacite,
                                                                                    "categories" => $categorie,
                                                                                    "msgValidation" => $msgValidation));
        }

    }