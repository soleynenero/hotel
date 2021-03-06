<?php

    

    // ini_set('display_errors', 0); // Message d'erreur n'apparait pas


    // if(!isset($_SESSION)) 
    // { 
        session_start(); 
    // }


    require_once __DIR__.'/../vendor/autoload.php';

    

    use Silex\Application;
    $app = new Application();

    
    
    require __DIR__.'/../src/register.php';
    require __DIR__.'/../src/TwigFunction/ParamExtension.php';

    require __DIR__.'/../src/middleware.php';
    require __DIR__.'/../src/function.php';

    require __DIR__.'/../src/Model/UserDAO.php';
    require __DIR__.'/../src/Model/InfoReservationDAO.php';
    require __DIR__.'/../src/Model/ReservDAO.php';
    require __DIR__.'/../src/Model/TokensDAO.php';
    require __DIR__.'/../src/Model/ConnexionDAO.php';


    require __DIR__.'/../src/Model/GestionMembreDAO.php';
    require __DIR__.'/../src/Model/GestionChambreDAO.php';
    require __DIR__.'/../src/Model/GestionServiceDAO.php';
    require __DIR__.'/../src/Model/InfosAdminDAO.php';


    require __DIR__.'/../src/controller/Controller.php';
    require __DIR__.'/../src/controller/AuthentificationController.php';
    require __DIR__.'/../src/Controller/reservController.php';
    require __DIR__.'/../src/Controller/ConnexionController.php';
    require __DIR__.'/../src/Controller/AdminIndexController.php';
    require __DIR__.'/../src/Controller/InfoUserController.php';
    require __DIR__.'/../src/Controller/ForgottenController.php';
    require __DIR__.'/../src/Controller/MailController.php';
    require __DIR__.'/../src/Controller/MessageController.php';
    require __DIR__.'/../src/Controller/GestionMembreController.php';
    require __DIR__.'/../src/Controller/GestionChambreController.php';
    require __DIR__.'/../src/Controller/GestionServiceController.php';


    require __DIR__.'/../src/route.php';

    // $app['debug'] = true ;
    $app->run();

