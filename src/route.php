<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hotel\Controller\InfoUserController;
use Twig\Extension\AbstractExtension;

//Request::setTrustedProxies(array('127.0.0.1'));


/*** ROUTES DE LA NAV ET DE LA PAGE D'ACCUEIL***/

// route Home
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
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

// page mot de passe oublié
$app->post('/oubli_mdp', function() use($app)
{
    return $app['twig']->render('basic/oubli_mdp.html.twig', array());
})->bind('oubli_mdp');






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

$app->post('/chambre_standard', function() use($app)
{
// Post à compléter : date deb, date fin, nb personnes ,prix , categorie en hidden , capacité , nom service
// lien vers le module de paiement ( API)
});




/************************************************* */
/************ ROUTE CHAMBRE SUPERIEUR *************/
/************************************************* */


$app->get('/chambre_superieure', function() use($app)
{
    return $app['twig']->render('basic/chambre_superieure.html.twig', array());
})->bind('chambre_superieure');

$app->post('/chambre_superieure', function() use($app)
{
// Post à compléter : date deb, date fin, nb personnes ,prix , categorie en hidden , capacité , nom service
// lien vers le module de paiement ( API)
});





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




/*************************************** */
/******* ROUTES  SECTION GESTION *********/
/************************************** */
$app->get('/admin', function() use($app)
{
    return $app['twig']->render('index_admin.html.twig', array());
})->bind('admin');


/************************************************* */
/************** ROUTE GESTION MEMBRES *************/
/************************************************* */



$app->get('admin/gestion_membres', function() use($app)
{
    return $app['twig']->render('basic/gestion_membres.html.twig', array());
})->bind('gestion_membres');

$app->post('/admin/gestion_membres', function() use($app)
{
// Post à compléter : nom , prenom adresse , ville , code postal , telephone , email, statut
});


/************************************************* */
/********** ROUTE GESTION RESERVATIONS ************/
/************************************************* */


$app->get('admin/gestion_reservations', function() use($app)
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


$app->get('admin/gestion_chambres', function() use($app)
{
    return $app['twig']->render('basic/gestion_chambres.html.twig', array());
})->bind('gestion_chambres');

$app->post('/admin/gestion_chambres', function() use($app)
{
// Post à compléter : num chambre, statut , telephone , prix , categorie chambre , capacité
});


/************************************************* */
/********** ROUTE GESTION SERVICES *****************/
/************************************************* */



$app->get('admin/gestion_services', function() use($app)
{
    return $app['twig']->render('basic/gestion_services.html.twig', array());
})->bind('gestion_services');

$app->post('/admin/gestion_services', function() use($app)
{
// Post à compléter selon la base de données.
});





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
