<?php


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


// /!\ /!\ /!\ PAS UTILISE  /!\ /!\ /!\
// middleware verif utilisateur est connecté

// $verifConnexion = function(Request $request)
// {
//     global $app;
//     // verification si une session n'est pas ouverte cad qu'un user n'est pas connecté si c'est
//     $reponse = (!isset($_SESSION['user'])) ? false : (isset($_SESSION['user']['statut']) == "admin") ? true : false;
        
//     if(!$reponse)
//         return false;

        
// };

// /* *********function********************* */

// function verifParam

 $verifParam = function (Request $request, $verifRequest = array()): array{

    $error = false;
    $messageError = "";

    foreach($verifRequest as $key => $val) {
        if( !$request->has($val) || trim($request->get($val)) == ""){ // si la $val n'existe pas et qu'elle est vide
            $error = true;
            $messageError .= " $val, ";
            echo '<div class="alert alert-danger col-md-8 col-md-offset-2 text-center">les champs sont vides</div>'; 
        }  
    }
    return array("error" => $error, "message" => $messageError);
};

// /* *******middleware******************* */

//middleware inscription
$verifParamInscription = function (Request $request) {
    $retour = verifParam($request->request, array(
        'nom',
        'prenom',
        'email',
        'ville',
        'code_postal',
        'telephone',
        'adresse',
        'mdp'));
    
};


// middleware verifie si utilisateur a bien rentré email et password dans le formlaire de co
// $verifParamLogin = function (Request $request) {
//     global $app;
//     $retour = verifParam($request->request, array("email","mdp"));
//     if($retour["error"])
//         return new RedirectResponse('connexion');
// };


//Gestion de COOKIE et SESSION
$isConnectYes = function (Request $request, Application $app) {
    
    if( isset( $_COOKIE["hotel"] ) ){ //verification de l'existence du cookie nommé hotel
        $token = $_COOKIE["hotel"]; 
        $sql = "SELECT user_id FROM tokens WHERE token = ?"; 
        $idUser = $app['db']->fetchAssoc($sql, array((string) $token));
        if($idUser != false){
            $sql = "SELECT * FROM user WHERE user_id = ?";
            $user = $app['db']->fetchAssoc($sql, array((int) $idUser['user_id']));
            unset($user['mdp']);
            $user['token'] = $token;
            $_SESSION["user"] = $user;
            setcookie("hotel", $token, time()+3600 * 24);
            return $app->redirect("home");
        }
    }
    if( isset( $_SESSION["user"] ) )
        return $app->redirect("home");
};