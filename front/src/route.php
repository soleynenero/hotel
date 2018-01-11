<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage')
;




// route coté front

// page d'inscription
$app->get('/inscription', function() use($app)
{
// peut etre mettre un lien vers la page login si l'utilisateur a deja un compte
});

$app->post('/inscription', function() use($app)
{
// mdp , nom , prenom ,  adresse , ville , code postal , tel , email , statut en hidden
});


// page de connexion
$app->get('/connexion', function() use($app)
{

});

$app->post('/connexion', function() use($app)
{
// mdp , token en hidden ( a générer a l'ouverture de la session)
});


// page profil
$app->get('/profil_membre', function() use($app)
{

});

$app->post('/profil_membre', function() use($app)
{
// nom , prenom ,  adresse , ville , code postal , tel , email , modif mdp
});


// page de chambre standard
$app->get('/chambre_standard', function() use($app)
{

});

$app->post('/chambre_standard', function() use($app)
{
// date deb, date fin, nb personnes ,prix , categorie en hidden , capacité , nom service
// lien vers le module de paiement ( API)
});


// page de chambre supérieur
$app->get('/chambre_superieure', function() use($app)
{

});

$app->post('/chambre_superieure', function() use($app)
{
// date deb, date fin, nb personnes ,prix , categorie en hidden , capacité , nom service
// lien vers le module de paiement ( API)
});


// page de chambre luxe
$app->get('/chambre_luxe', function() use($app)
{

});

$app->post('/chambre_luxe', function() use($app)
{
// date deb, date fin, nb personnes ,prix , categorie en hidden , capacité , nom service 
// lien vers le module de paiement ( API)
});


// page de validation du paiement
$app->get('/validation', function() use($app)
{
// numéro facture, prix total ,  moyen de paiement , la date  
});


// page service
$app->get('/services', function() use($app)
{

});

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
