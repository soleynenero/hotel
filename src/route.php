<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hotel\Controller\InfoUserController;
use Hotel\Controller\Controller;
use Hotel\Model\InfosAdminDAO;
use Hotel\Model\ReservDAO;
use Twig\Extension\AbstractExtension;

//Request::setTrustedProxies(array('127.0.0.1'));


/*** ROUTES DE LA NAV ET DE LA PAGE D'ACCUEIL***/

// route Home
$app->get('/', function () use ($app) {
    $isconnectedAnIsAdmin = Controller::isAdmin();
    // $listServices = $app['db']->fetchAll("SELECT * FROM services"); // liste des services qui seront affichés dans le formulaire de réservation
    $listServices = Hotel\Model\ReservDAO::listServices($app['db']);
    if (isset($_SESSION)) {
        if(isset($_SESSION['user']) && $_SESSION['user']['statut'] == 'standard') // si l'user est un client, on récupère ses données perso pour le préremplissage du form de réservation
            return $app['twig']->render('index.html.twig', array(
                "id_user" => $_SESSION['user']['user_id'],
                "prenom" => $_SESSION['user']['prenom'],
                "nom" => $_SESSION['user']['nom'],
                "email" => $_SESSION['user']['email'],
                "listService" => $listServices
                ));
        if ($isconnectedAnIsAdmin) {
            return $app['twig']->render('index.html.twig', array(
                "isconnectedAnIsAdmin" => $isconnectedAnIsAdmin,
                "listService" => $listServices
               ));
        }else {
            return $app['twig']->render('index.html.twig', array(
                "listService" => $listServices
            ));
        }
    }else{
        return $app['twig']->render('index.html.twig', array(
            "listService" => $listServices
        ));
    }
 
})->bind('Home');


$app->post('/', 'Hotel\Controller\ReservationControl::verifAction');

// route roomtarif
$app->get('/les-chambres-et-tarifs', function () use ($app) {
    return $app['twig']->render('basic/room_tarif.html.twig');
})->bind('roomtarif');

// route introduction
$app->get('/introduction', function () use ($app) {
    return $app['twig']->render('basic/introduction.html.twig', array());
})->bind('introduction');

// route gallery
$app->get('/gallery', function () use ($app) {
    return $app['twig']->render('basic/gallery.html.twig', array());
})->bind('gallery');

// route contacts
$app->get('/contacts', function () use ($app) {
    return $app['twig']->render('basic/contacts.html.twig', array());
})->bind('contacts');

// route mentions_legales et CGV
$app->get('/mentions_legales', function () use ($app) {
    return $app['twig']->render('basic/mentions_legales.html.twig', array());
})->bind('mentions_legales');

/** BLOC RESERVATION **/
$app->post('/', 'Hotel\Controller\ReservationControl::verifAction');

/** VALIDATION DE RESERVATION **/
$app->get('/reservation', function () use ($app) {
    return $app['twig']->render('basic/validation_reservation.html.twig');
});


/*** CONNEXION ET INSCRIPTION ***/

/************************************************* */
/************* ROUTE PAGE INSCRIPTION *************/
/************************************************* */
$app->get('/inscription', function() use($app)
{
    return $app['twig']->render('basic/inscription.html.twig', array());
// peut etre mettre un lien vers la page login si l'utilisateur a deja un compte
})->bind('inscription');
 

$app->post('/inscription','Hotel\Controller\AuthentificationController::InscriptionAction')->before($verifParamInscription)

;

/**************************** Validate token email *********** */
$app->get("/verif/{token}/", 'Hotel\Controller\AuthentificationController::verifEmailAction');

/************************************************* */
/*************** ROUTE PAGE CONNEXIONS *************/
/************************************************* */

$app->get('/connexion', function() use($app)
{
    return $app['twig']->render('basic/connexion.html.twig', array());
})->bind('connexion')->before($isConnectYes);


$app->post('/connexion', "Hotel\Controller\ConnexionController::login")
//->before($verifParamLogin)
;



/************************************************* */
/******************* ROUTE PAGE PROFIL *************/
/************************************************* */

// page mot de passe oublié  /!\ Modifié (Sathia)
$app->get('/oubli_mdp', function() use($app)
{
    return $app['twig']->render('basic/oubli_mdp.html.twig', array());
})
->bind('oubli_mdp');


// Vérification que l'adresse mail est bien valide
$app->post('/oubli_mdp','Hotel\Controller\ForgottenController::verifEmailAction')
;

// // Envoi du mot de passe : ne fonctionne pas
// $app->post('/oubli_mdp', 'Hotel\Controller\ForgottenController::sendPasswordAction')
// ;

/*** LES ROUTES DES PAGES SECONDAIRES ***/

/************************************************* */
/******************* ROUTE PAGE PROFIL *************/
/************************************************* */

// affichage info

$app->get('/profil_membre', "Hotel\Controller\InfoUserController::affichageUserAction")->bind('profil_membre')
// ->before($verifParamLogin)
;

// modification info
$app->get('/profil_membre/modificationInfos', "Hotel\Controller\InfoUserController::affichageFormAction")
->bind('modificationInfos')
// ->before($verifParamLogin)
;
$app->post('/profil_membre/modificationInfos', "Hotel\Controller\InfoUserController::modificationProfilAction");
    // Post à compléter : nom , prenom ,  adresse , ville , code postal , tel , email , modif mdp

//     // Post à compléter : nom , prenom ,  adresse , ville , code postal , tel , email , modif mdp
// ->bind('profil_membre1')
// // ->before($verifParamLogin)
// ;

// modification reservation
$app->get('/profil_membre/reservation', "Hotel\Controller\InfoUserController::affichageReservationAction")->bind('reservation')
// ->before($verifParamLogin)
;




// modification mdp 
$app->get('/profil_membre/modificationMdp', function() use($app){
    return $app['twig']->render('basic/modification_mdp.html.twig');
})->bind('modificationMdp')
// ->before($verifParamLogin)
;
$app->post('/profil_membre/modificationMdp', "Hotel\Controller\InfoUserController::modifMdpAction")//->bind('modificationMdp1')
// ->before($verifParamLogin)
;


// deconnexion
$app->get('/deconnexion', function() use($app){

    setcookie("hotel");
    session_destroy();
    return $app->redirect("/hotel/public/");

})->bind('deconnexion');



/************************************************* */
/************** ROUTE CHAMBRE STANDARD *************/
/************************************************* */



$app->get('/chambre_standard', function() use($app)
{
    return $app['twig']->render('basic/chambre_standard.html.twig', array());
})->bind('chambre_standard');

// Uniquement page de description




/************************************************* */
/************ ROUTE CHAMBRE SUPERIEUR *************/
/************************************************* */


$app->get('/chambre_superieure', function() use($app)
{
    return $app['twig']->render('basic/chambre_superieure.html.twig', array());
})->bind('chambre_superieure');

// Uniquement page de description





/************************************************* */
/************** ROUTE CHAMBRE LUXE *****************/
/************************************************* */


$app->get('/chambre_luxe', function() use($app)
{
    return $app['twig']->render('basic/chambre_luxe.html.twig', array());
})->bind('chambre_luxe');

$app->post('/chambre_luxe', function() use($app)
{
// Post à compléter : date deb, date fin, nb personnes ,prix , categorie en hidden , capacité , nom service 
// lien vers le module de paiement ( API)
});




/************************************************* */
/********* ROUTE VALIDATION DU PAIEMENT ***********/
/************************************************* */



$app->get('/validation', function() use($app)
{
// numéro facture, prix total ,  moyen de paiement , la date  
    return $app['twig']->render('basic/validation.html.twig', array());
})->bind('validation');





/************************************************* */
/************** ROUTE SERVICES DU SITE *************/
/************************************************* */



$app->get('/services', function() use($app)
{
    return $app['twig']->render('basic/services.html.twig', array());
})->bind('services');



/******************************************/
/***********PARTIE BACK DU SITE************/
/******************************************/
/******************************************/
/******************************************/
/******************************************/
/******************************************/
/******************************************/
/***********ROUTE ADMIN INDEX**************/
/******************************************/
/******************************************/


// $app->get('/admin', "Hotel\Controller\AdminIndexController::affichageInfosIndexAdmin" )->bind('admin');


$app->get('/admin', function() use($app)
{

    $isconnectedAnIsAdmin = Controller::isAdmin();
    if ($isconnectedAnIsAdmin) { // Si l'utilisateur est admin
        $IndexAdmin = new InfosAdminDAO($app['db']);
        $affichageIndexAdmin = $IndexAdmin->selectAllUser();
        $affichageRoomVac = $IndexAdmin->selectAllRoomVacancy();
        $affichageRoomNoVac = $IndexAdmin->selectAllRoomNoVacancy();
        // var_dump($_SESSION);
        return $app['twig']->render('index_admin.html.twig', array( //on vehicule les données dont on a besoin

            "IndexAdmin" => $affichageIndexAdmin,
            "AffichageRoomVac" => $affichageRoomVac,
            "AffichageRoomNoVac" => $affichageRoomNoVac,
        ));
    } else {// Si l'utilisateur n'est pas admin
        // var_dump($_SESSION);
        return $app->redirect('/hotel/public/');
    }
})->bind('admin');



/************************************************* */
/************** ROUTE GESTION MEMBRES *************/
/************************************************* */



$app->get('/admin/gestion_membres', "Hotel\Controller\GestionMembreController::affichageMembreAction")
->bind('gestion_membres');

$app->post('/admin/gestion_membres', function() use($app)
{
// Post à compléter : nom , prenom adresse , ville , code postal , telephone , email, statut
});


/************************************************* */
/********** ROUTE GESTION RESERVATIONS ************/
/************************************************* */


$app->get('/admin/gestion_reservations', function() use($app)
{
    return $app['twig']->render('basic/gestion_reservations.html.twig', array());
})->bind('gestion_reservations');

$app->post('/admin/gestion_reservations', function() use($app)
{
// Post à compléter : date deb , date fin , nb personne , id service , id chambre , statut chambre , nom service , id facture 
});



/************************************************* */
/********** ROUTE GESTION CHAMBRES ************/
/************************************************* */

// permet de voir les chambres
$app->get('/admin/gestion_chambres', "Hotel\Controller\GestionChambreController::affichageChambreAction")
->bind('gestion_chambres');

// permet d'ajouter une nlle chambre
$app->post('/admin/gestion_chambres', "Hotel\Controller\GestionChambreController::ajoutChambreAction");

// d'aller sur la page de modification des chambres
$app->get('/admin/gestion_chambres/modification/{id_chambres}', "Hotel\Controller\GestionChambreController::selectModifChambreAction")
->bind('modif_chambre');

// permet de modifier les chambres
$app->post('/admin/gestion_chambres/modification/{id_chambres}', "Hotel\Controller\GestionChambreController::updateModifChambreAction");

// permet de supprimer une chambre
$app->get('/admin/gestion_chambres/suppression/{id_chambres}', "Hotel\Controller\GestionChambreController::deleteChambreAction")
->bind('suppression_chambre');


/************************************************* */
/********** ROUTE GESTION SERVICES *****************/
/************************************************* */



$app->get('/admin/gestion_services', "Hotel\Controller\GestionServiceController::affichageServiceAction")
->bind('gestion_services');

$app->post('/admin/gestion_services', "Hotel\Controller\GestionServiceController::ajoutChambreAction");


// // d'aller sur la page de modification des chambres
$app->get('/admin/gestion_services/modification/{id_services}', "Hotel\Controller\GestionServiceController::selectModifServiceAction")
->bind('modif_service');

// permet de modifier les chambres
$app->post('/admin/gestion_services/modification/{id_services}', "Hotel\Controller\GestionServiceController::updateModifServiceAction");

// // permet de supprimer une chambre
$app->get('/admin/gestion_services/suppression/{id_services}', "Hotel\Controller\GestionServiceController::deleteServiceAction")
->bind('suppression_service');





/************************************************* */
/***************** PAGE ERREUR *********************/
/************************************************* */



$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
