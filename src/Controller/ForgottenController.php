<?php

    namespace Hotel\Controller ;

    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Hotel\Model\ConnexionDAO ;
    use Silex\Controller;

    class ForgottenController 
    {
        // fonction permettant de vérfier que l'email existe bien : attention, cette fonction est également présente dans le fichier AuthentificationController... Le mettre dans le middleware (mais génère un token et modifier la BDD donc pas souhaitable, non?)

        public function verifEmailAction(Application $app, Request $request)
        {
            $errors = ""; // Pour héberger les messages d'erreur
            $msg ="";
           // 1. Récupération de l'email et Vérification de la sécurité pour l'email
            $email = htmlspecialchars(trim($request->request->get("email"))); // récupération de l'email avec le get et modification des balises html

                // vérification du format
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
                //si l'email n'est pas au bon format (verif de la syntaxe avec la fonction de la classe)
                $errors .= "Votre adresse mail n'est pas au bon format. Merci d'entrer une adresse mail valide.";
                return $app['twig']->render('basic/oubli_mdp.html.twig', array("error" => $errors));  
            }
            //2. Vérification que l'email est bien dans la BDD

                //on regarde si le mail est bien dans la bdd
            $sql = "SELECT * FROM user WHERE email = ?";
            $user = $app['db']->fetchAssoc($sql, array((string) $email));

            //s'il n'y a pas d'email correspondant, on lui demande de réessayer ou d'aller sur page d'inscription
            if($email == $user['email']){
                //rediriger vers la page connexion
                $msg .= "Un message vient de vous être envoyé à votre adresse mail avec votre mot de passe. Merci de vous connecter.";
                return $app['twig']->render('basic/oubli_mdp.html.twig', array("msg" => $msg));
            }
            
            else{
                $errors .= "Votre adresse mail n'est pas reconnue. Merci d'entrer à nouveau une adresse mail valide. 
                Si vous n'êtes pas inscrit, vous pouvez le faire en appuyant sur 'S'incrire'";
                return $app['twig']->render('basic/oubli_mdp.html.twig', array("error" => $errors));              
            } 
        }

        // fonction d'envoi de mail avec le mot de passe oublié : Ne fonctionne pas!
        // public function sendPasswordAction(Application $app, Request $request){
        //     $email = htmlspecialchars(trim($request->get("email")));
        //     $this->sendMail(
        //     array("address" => $email/*, "name" => $user['prenom']*/),
        //     array("body" => "
        //         <!doctype html> 
        //         <html>   
        //             <head>     
        //                 <meta name='viewport' content='width=device-width'>     
        //                 <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>     
                        
        //                 <title>Mot de passe perdu</title>     
        //                 <style>     
        //                 /* INLINED WITH htmlemail.io/inline */     
        //                 /* RESPONSIVE AND MOBILE FRIENDLY STYLES */     
        //                 @media only screen and (max-width: 620px) {       
        //                     table[class=body] h1 { font-size: 28px !important; margin-bottom: 10px !important;}       
        //                     table[class=body] p, table[class=body] ul, table[class=body] ol, table[class=body] td,          table[class=body] span, table[class=body] a { font-size: 16px !important; }       
        //                     table[class=body] .wrapper, table[class=body] .article { padding: 10px !important;}
        //                     table[class=body] .content { padding: 0 !important;}       
        //                     table[class=body] .container { padding: 0 !important; width: 100% !important; }       
        //                     table[class=body] .main { border-left-width: 0 !important; border-radius: 0 !important;         border-right-width: 0 !important; }       
        //                     table[class=body] .btn table { width: 100% !important;}       
        //                     table[class=body] .btn a { width: 100% !important;}       
        //                     table[class=body] .img-responsive { height: auto !important; max-width: 100% !important;         width: auto !important;}     
        //                 }

        //                 /* PRESERVE THESE STYLES IN THE HEAD */     
        //                 @media all {       
        //                     .ExternalClass {         width: 100%;       }       
        //                     .ExternalClass,             .ExternalClass p,             .ExternalClass span,             .ExternalClass font,             .ExternalClass td,             .ExternalClass div {         line-height: 100%;       }       
        //                     .apple-link a {         color: inherit !important;         font-family: inherit !important;         font-size: inherit !important;         font-weight: inherit !important;         line-height: inherit !important;         text-decoration: none !important;       }       
        //                     .btn-primary table td:hover {         background-color: #34495e !important;       }
        //                     .btn-primary a:hover {         background-color: #34495e !important;         border-color: #34495e !important;       }     
        //                 }     
        //                 </style>   
        //             </head>   
                
        //             <body class='' style='background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;'>     
        //                 <table border='0' cellpadding='0' cellspacing='0' class='body' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;'>    
        //                     <tr>         
        //                         <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>   <td class='container' style='font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;'>
        //                             <div class='content' style='box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;'>              
                                    
        //                                 <!-- START CENTERED WHITE CONTAINER -->             
        //                                 <span class='preheader' style='color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;'>This is preheader text. Some clients will show this text as a preview.</span>             
        //                                 <table class='main' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;'>   
                                        
        //                                     <!-- START MAIN CONTENT AREA -->               
        //                                     <tr>                 
        //                                         <td class='wrapper' style='font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;'>      
        //                                             <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>                     
        //                                                 <tr>                       
        //                                                     <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>                         
        //                                                         <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Bonjour!</p>                         
        //                                                         <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Vous avez oublié votre mot de passe? Vous trouverez ci-dessous votre mot de passe ainsi que le lien vers la page de connexion de notre site.</p>
                                            
        //                                                         <table border='0' cellpadding='0' cellspacing='0' class='btn btn-primary' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;'>
        //                                                             <tbody>                             
        //                                                                 <tr>                               
        //                                                                     <td align='left' style='font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;'>
        //                                                                         <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;'>                       
        //                                                                             <tbody>                
        //                                                                                 <tr>              
        //                                                                                     <td style='font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;'> 
        //                                                                                         <a href='http://localhost/hotel/public/connexion' target='_blank' style='display: inline-block; color: #ffffff; background-color: #756534; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;'>Votre mot de passe: mettre le mot de passe></a>
        //                                                                                     </td>                  
        //                                                                                 </tr>                       
        //                                                                             </tbody>                         
        //                                                                         </table>                            
        //                                                                     </td>                             
        //                                                                 </tr>                           
        //                                                             </tbody>                         
        //                                                         </table>

        //                                                         <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Si vous n'avez pas à l'origine de cette demande, nous vous conseillons de vous connecter et de changer votre mot de passe.</p>  

        //                                                         <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Au plaisir de vous revoir sur notre site! A très bientôt!</p>           
        //                                                     </td>                     
        //                                                 </tr>                   
        //                                             </table>                 
        //                                         </td>               
        //                                     </tr>              
                                        
        //                                     <!-- END MAIN CONTENT AREA -->             
        //                                 </table>              
                                            
        //                             <!-- END CENTERED WHITE CONTAINER -->           
                                    
        //                             </div>         
        //                         </td>         
        //                         <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>
        //                     </tr>     
        //                 </table>   
        //             </body> 
        //         </html>", 
        //         "subject" => "Projet Hotel: mot de passe oublié")
        //     );

        //     $errors ="";   
        //     $errors .= "Un message vient de vous être envoyé à votre adresse mail avec votre mot de passe. Merci de vous connecter.";
        //     return $app['twig']->render('basic/connection.html.twig',  array("error" => $errors));
        // }
    }
    