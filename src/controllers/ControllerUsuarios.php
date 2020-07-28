<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\usuarios;
use App\Utils\utils;
use \Firebase\JWT\JWT;

class ControllerUsuarios{

    //POST /registro
    public function postRegistro(Request $request, Response $response, $args){

        $body = $request->getParsedBody();
        $usuarios = new usuarios();

        $usuarios->email    = $body['email'];
        $usuarios->clave    = Utils::EncodePass($body['clave']);        //ENCRIPTO PASS
        $usuarios->tipo     = $body['tipo'];
        $usuarios->usuario  = $body['usuario'];

        try {
            $rta = json_encode(array("STATUS"=>$usuarios->save(), "Email"=>$usuarios->email, "Pass"=>$usuarios->clave));
            $response->getBody()->write($rta);
            
        } catch (\Throwable $th) {
            $rta = json_encode(array("STATUS"=>"ERROR", "Detalles"=>$th));
            $response->getBody()->write($rta);
        }
        
        return $response;
    }

    //POST /login
    public function postLogin(Request $request, Response $response, $args){

        $body = $request->getParsedBody();
        $user = new usuarios();

        $tipo = $user->where('email', $body['email'])->value('tipo');
        $clave = $user->where('email', $body['email'])->value('clave');

        $payload = array(
            "email" => $body['email'],
            "clave" => $clave,
            "tipo" => $tipo
        );

        try {
            $token = Utils::EncodeToken($payload);

            $rta = json_encode(array("STATUS Correcto - Su token:"=>$token));
            $response->getBody()->write( $rta);

        } catch (\Throwable $th) {

            $rta = json_encode(array("STATUS"=>"Error en login"));
            $response->getBody()->write( $rta);

        }
        
        return $response;
    }
}