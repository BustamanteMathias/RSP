<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\mascotas;
use App\Utils\utils;
use \Firebase\JWT\JWT;

class ControllerMascota{

    public function postMascota(Request $request, Response $response, $args){

        $body = $request->getParsedBody();
        $mascota = new mascotas();

        $mascota->nombre            = $body['nombre'];
        $mascota->fecha_nacimiento  = Utils::ParseDatePHPmySQL($body['fecha_nacimiento']);
        $mascota->cliente_id        = $body['cliente_id'];
        $mascota->tipo_mascota_id   = $body['tipo_mascota_id'];

        try {
            $rta = json_encode(array("STATUS"=>$mascota->save()));
            $response->getBody()->write($rta);
        } catch (\Throwable $th) {

            $rta = json_encode(array("STATUS"=>"ERROR", "Detalles"=>$th));
            $response->getBody()->write($rta);
        }
        
        return $response;
    }
}