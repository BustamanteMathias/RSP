<?php


use Slim\App;
use App\Middleware\AfterMiddleware;


return function (App $app) {

    $app->addBodyParsingMiddleware();
    //Se ejecuta despues de ir a la ruta
    $app->add(new AfterMiddleware()); 
    
};
?>