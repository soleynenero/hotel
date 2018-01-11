<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));


/*** ROUTES DE LA NAV ET DE LA PAGE D'ACCUEIL***/

// route Home
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})->bind('Home');

// route roomtarif
$app->get('/roomtarif', function () use ($app) {
    return $app['twig']->render('basic/room_tarif.html.twig', array());
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

// page d'inscription
$app->get('/inscription', function() use($app)
{
    return $app['twig']->render('basic/inscription.html.twig', array());
// peut etre mettre un lien vers la page login si l'utilisateur a deja un compte
})->bind('inscription');
 
$app->post('/inscription', function() use($app)
{
// Post à compléter avec les conditions de contrôle : mdp , nom , prenom ,  adresse , ville , code postal , tel , email , statut en hidden
});


// page de connexion
$app->get('/connexion', function() use($app)
{
    return $app['twig']->render('basic/connexion.html.twig', array());
})->bind('connexion');

$app->post('/connexion', function() use($app)
{
// Post à compléter : mdp , token en hidden ( a générer a l'ouverture de la session)
});

// page mot de passe oublié
$app->post('/oubli_mdp', function() use($app)
{
    return $app['twig']->render('basic/oubli_mdp.html.twig', array());
})->bind('oubli_mdp');

/*** LES ROUTES DES PAGES SECONDAIRES ***/

// page profil
$app->get('/profil_membre', function() use($app)
{
    return $app['twig']->render('basic/profil_membre.html.twig', array());
})->bind('profil_membre');

$app->post('/profil_membre', function() use($app)
{
// Post à compléter : nom , prenom ,  adresse , ville , code postal , tel , email , modif mdp
});


// page de chambre standard
$app->get('/chambre_standard', function() use($app)
{
    return $app['twig']->render('basic/chambre_standard.html.twig', array());
})->bind('chambre_standard');

$app->post('/chambre_standard', function() use($app)
{
// Post à compléter : date deb, date fin, nb personnes ,prix , categorie en hidden , capacité , nom service
// lien vers le module de paiement ( API)
});


// page de chambre supérieur
$app->get('/chambre_superieure', function() use($app)
{
    return $app['twig']->render('basic/chambre_superieure.html.twig', array());
})->bind('chambre_superieure');

$app->post('/chambre_superieure', function() use($app)
{
// Post à compléter : date deb, date fin, nb personnes ,prix , categorie en hidden , capacité , nom service
// lien vers le module de paiement ( API)
});


// page de chambre luxe
$app->get('/chambre_luxe', function() use($app)
{
    return $app['twig']->render('basic/chambre_luxe.html.twig', array());
})->bind('chambre_luxe');

$app->post('/chambre_luxe', function() use($app)
{
// Post à compléter : date deb, date fin, nb personnes ,prix , categorie en hidden , capacité , nom service 
// lien vers le module de paiement ( API)
});


// page de validation du paiement
$app->get('/validation', function() use($app)
{
// numéro facture, prix total ,  moyen de paiement , la date  
    return $app['twig']->render('basic/validation.html.twig', array());
})->bind('validation');


// page services
$app->get('/services', function() use($app)
{
    return $app['twig']->render('basic/services.html.twig', array());
})->bind('services');


// pages ERREURS
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
