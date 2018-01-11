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
$app->get('/les-chambres-et-tarifs.html', function () use ($app) {
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



/*** ROUTES VERS LES PAGES DE GESTION ***/

// page gestion membres
$app->get('admin/', function() use($app)
{
    return $app['twig']->render('index_admin.html.twig', array());
})->bind('admin');

// page gestion membres
$app->get('admin/gestion_membres', function() use($app)
{
    return $app['twig']->render('basic/gestion_membres.html.twig', array());
})->bind('gestion_membres');

$app->post('admin/gestion_membres', function() use($app)
{
// Post à compléter : nom , prenom adresse , ville , code postal , telephone , email, statut
});


// page gestion reservations
$app->get('admin/gestion_reservations', function() use($app)
{
    return $app['twig']->render('basic/gestion_reservations.html.twig', array());
})->bind('gestion_reservations');

$app->post('admin/gestion_reservations', function() use($app)
{
// Post à compléter : date deb , date fin , nb personne , id service , id chambre , statut chambre , nom service , id facture 
});


// page gestion chambres
$app->get('admin/gestion_chambres', function() use($app)
{
    return $app['twig']->render('basic/gestion_chambres.html.twig', array());
})->bind('gestion_chambres');

$app->post('admin/gestion_chambres', function() use($app)
{
// Post à compléter : num chambre, statut , telephone , prix , categorie chambre , capacité
});

// page gestion services
$app->get('admin/gestion_services', function() use($app)
{
    return $app['twig']->render('basic/gestion_services.html.twig', array());
})->bind('gestion_services');

$app->post('admin/gestion_services', function() use($app)
{
// Post à compléter selon la base de données.
});


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
