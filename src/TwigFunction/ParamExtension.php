<?php

    // on fait un composer require twig/extensions puis on fait notre use
    use Twig\Extension\AbstractExtension;
    
    // creation d'extension de twig, pour avoir des fonctionnalités et variables qui seront accessibles partout grace a addGlobal
    $app->extend('twig', function($twig, $app) {
        $connect = isset($_SESSION) ? $_SESSION : false;
        // addGlobal('variable utilisé' , valeur de la variable);
        $twig->addGlobal('connect', $connect);
        return $twig;
    });