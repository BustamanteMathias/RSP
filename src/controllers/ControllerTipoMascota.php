<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\tipo_mascota;
use App\Utils\utils;
use \Firebase\JWT\JWT;

class ControllerTipoMascota{

    //POST /registro
    public function postTipoMascota(Request $request, Response $response, $args){

        $body = $request->getParsedBody();
        $tipoMascota = new tipo_mascota();

        $tipoMascota->tipo = $body['tipo'];

        try {
            $rta = json_encode(array("STATUS"=>$tipoMascota->save()));
            $response->getBody()->write($rta);
        } catch (\Throwable $th) {

            $rta = json_encode(array("STATUS"=>"ERROR", "Detalles"=>$th));
            $response->getBody()->write($rta);

        }
        
        return $response;
    }
}