<?php

    namespace Hotel\Controller ;
    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    //use Hotel\Model\ConnexionDAO ; Pas besoin de cette fonction!
    use Silex\Controller; // besoin?

    class MessageController 
    {
        public function contactFormAction(Application $app, Request $request)
        {
            $errors = ""; // Pour héberger les messages d'erreur
            $msg = ""; // Permet d'héberger les autres messages
                        
            // 1. Récupération des informations du formulaire et Vérification de la sécurité
            $email = htmlspecialchars(trim($request->request->get("email"))); // récupération de l'email avec le get et modification des balises html
            $nom = strip_tags(trim($request->request->get("nom")));
            $message = htmlspecialchars(trim($request->request->get("message")));
            // print_r($email);
            // print_r($nom);
            // print_r($message);
            // die();
            // vérification du format
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            { 
                //si l'email n'est pas au bon format (verif de la syntaxe avec la fonction de la classe)
                $errors .= "Votre adresse mail n'est pas au bon format. Merci d'entrer une adresse mail valide.";
            }

            // if(!empty($message)){
            //     $errors .= "Merci de remplir l'ensemble des champs."; 
            // }
            else
            {
                $msg .= "Merci pour votre message. Nous vous répondrons dans les plus brefs délais.";
            }
  
            // print_r($errors);
            // die();

            // 2. Insertion dans la base de données
            if(!empty($errors))
            {
                return $app['twig']->render('basic/contacts.html.twig', array(
                "error" => $errors,
                "msg" => $msg,
                "nom" => $nom , 
                "email" => $email,
                "message" => $message
                ));
            }
            else
            {
                $app['db']->insert('message', array(
                    'nom' => $nom,
                    'email' =>  $email,
                    'message' => $message
                ));  
                return $app['twig']->render('basic/contacts.html.twig', array(
                "msg" => $msg
                ));
            }

         

        }
    }
    