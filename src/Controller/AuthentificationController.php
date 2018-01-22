<?php
    namespace Hotel\Controller;
    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Hotel\Model\TokensDAO;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

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
             
                $envoiMail = $this->sendMail(
                 array("address" => $email, "name" => $prenom),
                 array("body" => MailController::mailInscription($token), "subject" => "New membre")
            );
          if ($envoiMail) {
            return $app['twig']->render('basic/inscription.html.twig', array());
          }else {
            return $app['twig']->render('basic/inscription.html.twig', array(
                "errorMail" => "Probleme lors de l'envoi du mail"
            ));
          }

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