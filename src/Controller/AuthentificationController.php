<?php
    namespace Hotel\Controller;
    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Hotel\Model\TokensDAO;
    class AuthentificationController extends Controller{ // par convention, on va nommer les classes xxxxController
       
       
        public function InscriptionAction(Application $app, Request $request)
        {
           
            
            $prenom = strip_tags(trim($request->request->get("prenom")));
            $nom = strip_tags(trim($request->request->get("nom")));
            $adresse = htmlspecialchars(trim($request->request->get("adresse")));
            $ville = strip_tags(trim($request->request->get("ville")));
            $codepostal = strip_tags(trim($request->request->get("code_postal")));
            $telephone = strip_tags(trim($request->request->get("telephone")));
            $email = htmlspecialchars(trim($request->request->get("email"))); 
            $password = strip_tags(trim($request->request->get("mdp")));

                $errors = "";

                if(iconv_strlen($prenom) < 2 || iconv_strlen( $prenom )> 15){
                 $errors .= ' Erreur de la taille de votre prenom  ;'   ;
                
                
                
                 }
                if(iconv_strlen($nom) < 2 || iconv_strlen($nom) > 15){
                 $errors .= ' Erreur de la taille de votre nom  ;';
                
               
                }
    
                if(!is_numeric($codepostal) || iconv_strlen($codepostal) != 5){
                $errors .= ' Erreur de format  du code postal  ;';
                
                
                 }
    
                 if(!is_numeric( $telephone) || iconv_strlen( $telephone) != 10){
                    $errors .= ' Erreur du  format du telephone    ;';
                   
                    
                 }          
            
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    
                    $errors .= ' Erreur du format du champs email   ; ';
                   
                }
               
                if(!empty($errors))
                {
                    return $app['twig']->render('basic/inscription.html.twig', array(
                    "error" => $errors ,
                    "prenom" => $prenom , 
                    "nom" => $nom , 
                    "email" => $email , 
                    "telephone" => $telephone , 
                    "ville" => $ville , 
                    "code_postal" => $codepostal , 
                    "adresse" => $adresse));
                } ;
               

               
                

                $app['db']->insert('user', array(
                    'prenom' => $prenom,
                    'nom' => $nom,
                    'adresse' => $adresse,
                    'ville' => $ville,
                    'code_postal' => $codepostal,
                    'telephone' => $telephone,
                    'email' =>  $email,
                    'mdp' => $password // on peut crypter avec la fonction crypt() en php et qui est indechiffrable
                ));
                
                $token = $this->generateToken();
                $id = $app['db']->lastInsertId();
                $dateExpire = $this->expireToken();
                // $dateCreated = $this->createToken();
                $tokens = new TokensDAO ($app['db'] );
                $token = $tokens->createToken($id, $dateExpire, "email");
                
                if($id)
                {
                    echo '<div class="reussiteinscription alert alert-success col-xs-12  text-center">Votre inscription à bien été prise en compte veuillez cliquez sur le lien qui vous à été envoyé sur votre messagerie afin de vous connecter</div>';
                }    
                //mise en forme à revoir!
             
            // $this->sendMail(
            //      array("address" => $email, "name" => $prenom),
            //      array("body" => "<!doctype html> <html>   <head>     <meta name='viewport' content='width=device-width'>     <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>     <title>Simple Transactional Email</title>     <style>     /* -------------------------------------         INLINED WITH htmlemail.io/inline     ------------------------------------- */     /* -------------------------------------         RESPONSIVE AND MOBILE FRIENDLY STYLES     ------------------------------------- */     @media only screen and (max-width: 620px) {       table[class=body] h1 {         font-size: 28px !important;         margin-bottom: 10px !important;       }       table[class=body] p,             table[class=body] ul,             table[class=body] ol,             table[class=body] td,             table[class=body] span,             table[class=body] a {         font-size: 16px !important;       }       table[class=body] .wrapper,             table[class=body] .article {         padding: 10px !important;       }       table[class=body] .content {         padding: 0 !important;       }       table[class=body] .container {         padding: 0 !important;         width: 100% !important;       }       table[class=body] .main {         border-left-width: 0 !important;         border-radius: 0 !important;         border-right-width: 0 !important;       }       table[class=body] .btn table {         width: 100% !important;       }       table[class=body] .btn a {         width: 100% !important;       }       table[class=body] .img-responsive {         height: auto !important;         max-width: 100% !important;         width: auto !important;       }     }     /* -------------------------------------         PRESERVE THESE STYLES IN THE HEAD     ------------------------------------- */     @media all {       .ExternalClass {         width: 100%;       }       .ExternalClass,             .ExternalClass p,             .ExternalClass span,             .ExternalClass font,             .ExternalClass td,             .ExternalClass div {         line-height: 100%;       }       .apple-link a {         color: inherit !important;         font-family: inherit !important;         font-size: inherit !important;         font-weight: inherit !important;         line-height: inherit !important;         text-decoration: none !important;       }       .btn-primary table td:hover {         background-color: #34495e !important;       }       .btn-primary a:hover {         background-color: #34495e !important;         border-color: #34495e !important;       }     }     </style>   </head>   <body class='' style='background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;'>     <table border='0' cellpadding='0' cellspacing='0' class='body' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;'>       <tr>         <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>         <td class='container' style='font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;'>           <div class='content' style='box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;'>              <!-- START CENTERED WHITE CONTAINER -->             <span class='preheader' style='color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;'>This is preheader text. Some clients will show this text as a preview.</span>             <table class='main' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;'>                <!-- START MAIN CONTENT AREA -->               <tr>                 <td class='wrapper' style='font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;'>                   <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>                     <tr>                       <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>                         <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Hi there,</p>                         <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Sometimes you just want to send a simple HTML email with a simple design and clear call to action. This is it.</p>                         <table border='0' cellpadding='0' cellspacing='0' class='btn btn-primary' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;'>                           <tbody>                             <tr>                               <td align='left' style='font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;'>                                 <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;'>                                   <tbody>                                     <tr>                                       <td style='font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;'> <a href='http://localhost/hotel/public/verif/$token/' target='_blank' style='display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;'>Call To Action</a>http://localhost/hotel/public/verif/$token/ </td>                                     </tr>                                   </tbody>                                 </table>                               </td>                             </tr>                           </tbody>                         </table>                         <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>This is a really simple email template. Its sole purpose is to get the recipient to click the button with no distractions.</p>                         <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Good luck! Hope it works.</p>                       </td>                     </tr>                   </table>                 </td>               </tr>              <!-- END MAIN CONTENT AREA -->             </table>              <!-- START FOOTER -->             <div class='footer' style='clear: both; Margin-top: 10px; text-align: center; width: 100%;'>               <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>                 <tr>                   <td class='content-block' style='font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;'>                     <span class='apple-link' style='color: #999999; font-size: 12px; text-align: center;'>Company Inc, 3 Abbey Road, San Francisco CA 94102</span>                     <br> Don't like these emails? <a href='http://i.imgur.com/CScmqnj.gif' style='text-decoration: underline; color: #999999; font-size: 12px; text-align: center;'>Unsubscribe</a>.                   </td>                 </tr>                 <tr>                   <td class='content-block powered-by' style='font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;'>                     Powered by <a href='http://htmlemail.io' style='color: #999999; font-size: 12px; text-align: center; text-decoration: none;'>HTMLemail</a>.                   </td>                 </tr>               </table>             </div>             <!-- END FOOTER -->            <!-- END CENTERED WHITE CONTAINER -->           </div>         </td>         <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>       </tr>     </table>   </body> </html>", "subject" => "New membre")
            //     );
                // /* A supprimer */
                // var_dump($rgjirei);
                // return "";
                // /* END */
            return $app['twig']->render('basic/inscription.html.twig');
            


        }


         public function verifEmailAction(Application $app, Request $request) {
             $token = strip_tags(trim($request->get("token")));
             $sql = "SELECT user_id FROM tokens WHERE token = ? AND type LIKE 'email'"; // LIKE est équivalent au = quand on chercher des strings
             $idUser = $app['db']->fetchAssoc($sql, array((string) $token));
             if(!$idUser)
                 return $app['twig']->redirect('/hotel/public/inscription');
             $sql = "UPDATE user SET statut = 'standard' WHERE user_id = ?";
             $rowAffected = $app['db']->executeUpdate($sql, array((int) $idUser["user_id"]));
             if($rowAffected == 1)
                 $app['db']->delete('token', array('token' => $token));
             return $app->redirect('/hotel/public/connexion');
         }
}