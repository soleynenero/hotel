<?php
 
    namespace Hotel\Controller;

    use Silex\Application;
    use \DateTime; // pour utiliser la classe Date de PHP dans notre classe expireToken

    class Controller {

        public function sendMail(array $user, array $message){

            global $app;
            try {
                $mail = $app["mail"];
                //Server settings (à récupéer depuis les informations de notre serveur/hébergeur)
                $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'provolone.o2switch.net';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'eleve@lyknowledge.fr';                 // SMTP username
                $mail->Password = 'zoubida22?';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // mode de sécurité
                $mail->Port = 465;                                    // TCP port to connect to
            
                //Recipients (qui est l'expéditeur)
                $mail->setFrom('eleve@lyknowledge.fr', 'Hotel3'); // l'adresse de l'expéditeur (nous). Le 2ème argument est un alias qui s'affichera dans le mail
                $mail->addAddress($user["address"], $user['name']); // l'adresse du destinataire. + alias en 2ème arg (en général le nom)
                
                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $message["subject"];
                $mail->Body    = $message["body"]; // contenu du mail en html
                // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; // contenu du mail en texte simple (très rare, en général, on n'envoit que body en html)
            
                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        public function generateToken(){
            return substr( md5( uniqid().mt_rand() ), 0, 22);

        }

        
        public function expireToken(){
            $dateNow = new DateTime();
            $dateNow->modify("+ 1 day");
            return $dateNow->format("Y-m-d H:i:s");
        }
        
        
    }