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
    require __DIR__.'/../src/model/TokensDAO.php';
    require __DIR__.'/../src/Model/ConnexionDAO.php';

    require __DIR__.'/../src/controller/Controller.php';
    require __DIR__.'/../src/controller/AuthentificationController.php';
    require __DIR__.'/../src/Controller/reservController.php';
    require __DIR__.'/../src/Controller/ConnexionController.php';
    require __DIR__.'/../src/Controller/InfoUserController.php';

    require __DIR__.'/../src/route.php';

    $app['debug'] = true ;
    $app->run();

