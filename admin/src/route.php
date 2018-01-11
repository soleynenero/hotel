<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));


/*** ROUTES VERS LES PAGES DE GESTION ***/

// page gestion membres
$app->get('/gestion_membres', function() use($app)
{
    return $app['twig']->render('gestion_membres.html.twig', array());
})->bind('gestion_membres');

$app->post('/gestion_membres', function() use($app)
{
// Post à compléter : nom , prenom adresse , ville , code postal , telephone , email, statut
});


// page gestion reservations
$app->get('/gestion_reservations', function() use($app)
{
    return $app['twig']->render('gestion_reservations.html.twig', array());
})->bind('gestion_reservations');

$app->post('/gestion_reservations', function() use($app)
{
// Post à compléter : date deb , date fin , nb personne , id service , id chambre , statut chambre , nom service , id facture 
});


// page gestion chambres
$app->get('/gestion_chambres', function() use($app)
{
    return $app['twig']->render('gestion_chambres.html.twig', array());
})->bind('gestion_chambres');

$app->post('/gestion_chambres', function() use($app)
{
// Post à compléter : num chambre, statut , telephone , prix , categorie chambre , capacité
});

// page gestion services
$app->get('/gestion_services', function() use($app)
{
    return $app['twig']->render('gestion_services.html.twig', array());
})->bind('gestion_services');

$app->post('/gestion_services', function() use($app)
{
// Post à compléter selon la base de données.
});


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



















