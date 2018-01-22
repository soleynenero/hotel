<?php

    namespace Hotel\Controller ;
        use Silex\Application;
        use Symfony\Component\HttpFoundation\Request;
        use Hotel\Model\GestionChambreDAO ;

    class GestionChambreController
    {
        // le code qui est ici ca sera le meme que le code de service

        // affichage des informations de mes chambres , la capacité et la categorie pour le select du formulaire
        public function affichageChambreAction(Application $app, Request $request)
        {
            // j'appelle mes classes pour les reutiliser et utiliser les données pour ma page twig
            $affichageChambre = new GestionChambreDAO($app['db']);
            $chambre = $affichageChambre->selectChambre();

            $affichageCapacite = new GestionChambreDAO($app['db']);
            $capacite = $affichageCapacite->selectCapacite();

            $affichageCategorie = new GestionChambreDAO($app['db']);
            $categorie = $affichageCategorie->selectCategorie();

            
            // j'envoie les données dans ma page gestion_chambres
            return $app['twig']->render('basic/gestion_chambres.html.twig', array("chambres" => $chambre,
                                                                                    "capacites" => $capacite,
                                                                                    "categories" => $categorie));
        }

        
        // ici on ajoute une nouvelle chambre
        public function ajoutChambreAction(Application $app, Request $request)
        {

            // recuperation de l'insertion de l'utilisateur        
            $id_categorie = $request->get("id_categorie");
            $id_capacite = $request->get("id_capacite");     
            $numero_chambre = strip_tags($request->get("numero_chambre"));
            $telephone = strip_tags($request->get("telephone"));
            $prix = htmlspecialchars($request->get("prix"));

            // creation de la variable erreur
            $errors ="";
            // verification s'il y a des erreurs ou non
            if(!is_numeric($numero_chambre)){
                $errors .= "<li>Le numéro de chambre doit etre numérique</li>\n";
                }

            if(!is_numeric($prix)){
                $errors .= "<li>Le prix doit etre numérique</li>\n";
                }

            if(!is_numeric($telephone) || iconv_strlen($telephone) != 10){
                $errors .= "<li>Votre telephone n'est pas au bon format : 0102030405</li>\n";
            }          


            // j'utilise mes classes et je les instancie, je rappelle capacite , categorie et chambre car sinon lors du render j'aurais un msg d'erreur car je serais sur la page gestion_chambres on doit obligatoirement utiliser ses variables
            $affichageChambre = new GestionChambreDAO($app['db']);
            // ici je stock les informations de mes utilisateur dans un tableau appelé membre
            $chambre = $affichageChambre->selectChambre();

            $affichageCapacite = new GestionChambreDAO($app['db']);
            $capacite = $affichageCapacite->selectCapacite();

            $affichageCategorie = new GestionChambreDAO($app['db']);
            $categorie = $affichageCategorie->selectCategorie();

            // je verifie si le numero chambre est pris, si c'est le cas alors on aura une erreur 
            foreach($chambre AS $cle => $tab):
                if($numero_chambre == $tab["numero_chambre"]):
                    $errors .= "<li>Le numero de chambre ". $numero_chambre." est deja pris</li> \n";
                endif ;
            endforeach ;
                    
            // s'il y a des erreurs alors je fais du ajax qui me permet de charger ma page automatiquement et envoyer les msg d'erreurs
            if(!empty($errors))
                return $app->json(array("errors" => true, "message" => $errors));

            // s'il n'y a pas d'erreur alors je fais mon insert grance a la classe que j'instancie et je renvoie sur mon ajax pour que la page se recharge automatiquement
            if(empty($errors))
            {
                $Chambre = new GestionChambreDAO($app['db']);
                $idChambre = $Chambre->insertChambre($numero_chambre, $id_categorie, $id_capacite, $telephone, $prix);
                return $app->json( array("errors" => false, "id" => $idChambre) );
            } 

        }

        // quand je clique sur modification ca me renvoie sur une page et me permettant de modifier en fonction de l'id_chambres pour recuperer les données qui ont cet id
        public function selectModifChambreAction(Application $app, Request $request , $id_chambres)
        {
            // ici j'instancie les classes
            $affichageChambreModifiable = new GestionChambreDAO($app['db']);
            $chambre = $affichageChambreModifiable->selectModifChambre($id_chambres);

            $affichageCapacite = new GestionChambreDAO($app['db']);
            $capacite = $affichageCapacite->selectCapacite();

            $affichageCategorie = new GestionChambreDAO($app['db']);
            $categorie = $affichageCategorie->selectCategorie();

            $affichageStatut = new GestionChambreDAO($app['db']);
            $statut = $affichageStatut->selectStatut();

            // je renvoie les données sur la page modification_chambre
            return $app['twig']->render('basic/modification_chambre.html.twig', array("chambres" => $chambre,
                                                                                    "capacites" => $capacite,
                                                                                    "categories" => $categorie,
                                                                                    "statuts" => $statut,));
        }

        // maintenant lors que je valide mon formulaire je vais faire un update de ma chambre
        public function updateModifChambreAction(Application $app, Request $request , int $id_chambres)
        {
            // recuperation de l'insertion de l'utilisateur        
            $id_categorie = $request->get("id_categorie");
            $id_capacite = $request->get("id_capacite"); 
            $statut = $request->get("statut"); 
            $telephone = strip_tags($request->get("telephone"));
            $prix = htmlspecialchars($request->get("prix"));

            // je creais ma variable erreur
            $errors ="";

            // je verifie s'il y a des erreurs
            if(!is_numeric($prix)){
                $errors .= "Le prix doit etre numérique\n";
                }

            if(!is_numeric($telephone) || iconv_strlen($telephone) != 10){
                $errors .= "Votre telephone n'est pas au bon format : 0102030405\n";
            }     

            // ici s'il n'y a pas d'erreur alors je modifie
            $voirChambre = new GestionChambreDAO($app['db']);
            if(empty($errors))
                $rowAffect = $voirChambre->modifChambre($id_categorie , $id_capacite , $telephone , $prix , $statut, $id_chambres);

            // j'instancie mes classes pour pouvoir les reutiliser dans mon code car sinon j'aurais des msg d'erreur cr je les utilise dans les pages ou j'envoie mes informations
            $affichageChambreModifiable = new GestionChambreDAO($app['db']);
            $chambre = $affichageChambreModifiable->selectmodifChambre($id_chambres);

            $affichageCapacite = new GestionChambreDAO($app['db']);
            $capacite = $affichageCapacite->selectCapacite();

            $affichageCategorie = new GestionChambreDAO($app['db']);
            $categorie = $affichageCategorie->selectCategorie();

            $affichageStatut = new GestionChambreDAO($app['db']);
            $statut = $affichageStatut->selectStatut();

            // s'il y a des erreurs mettre le msg d'erreur et utiliser les variables pour ne pas avoir d'erreur
            if(!empty($errors))
            {
                
                return $app['twig']->render('basic/modification_chambre.html.twig', array(
                "error" => $errors,
                "chambres" =>  $chambre,
                "capacites" => $capacite,
                "categories" => $categorie,
                "statuts" => $statut));
            }
            
            // s'il n'y a pas d'erreur alors je peux envoyer les modifications
            return $app['twig']->render('basic/modification_chambre.html.twig', array("chambres" => $chambre,
                                                                                    "capacites" => $capacite,
                                                                                    "msgValidation" => "Votre chambre a bien été modifié",
                                                                                    "categories" => $categorie,
                                                                                    "statuts" => $statut));
        }

        // fonction me permettant de supprimer une chambre
        public function deleteChambreAction(Application $app, Request $request , $id_chambres)
        {

            $suppression = new GestionChambreDAO($app['db']);
            $suppressionChambre = $suppression->deleteChambre($id_chambres);
       
            return $app->redirect('/hotel/public/admin/gestion_chambres');
        }

    }