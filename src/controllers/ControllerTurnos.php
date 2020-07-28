<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\turnos;
use App\Models\mascotas;
use App\Utils\utils;
use \Firebase\JWT\JWT;

class ControllerTurnos{

    public function postTurnos(Request $request, Response $response, $args){
        
        $body = $request->getParsedBody();
        $turno = new turnos();

        // El turno no esta disponible hasta que se verifique que hay mas de 30 minutos en turnos para ese veterinario
        $turnoOK = false;
        
        $turno->mascota_id      = $body['mascota_id'];
        $turno->veterinario_id  = $body['veterinario_id'];
        $turnoFecha             = new \DateTime($body['fecha']);
        $turno->fecha           = $turnoFecha->format('Y/m/d H:i:s');
        $turnoHora              = new \DateTime($body['fecha']);

        // Horas de cierre y apertura del local
        $localAbre                  = new \DateTime('09:00');
        $localCierra                = new \DateTime('17:00');
        $localAbre                  = $localAbre->format('H:i');
        $localCierra                = $localCierra->format('H:i');
        $clienteHora                = $turnoHora->format('H:i');
        // $turnoHora = $clienteHora->format('H:i');
        $clienteHorasIngresadas     = $turnoHora->format("H");
        $clienteMinutosIngresados   = $turnoHora->format("i");
        $clienteFechaIngresada      = $turnoFecha->format("Y-m-d");

        // Me fijo si esta cerrado el local a esa hora que pide el cliente
        if(($clienteHorasIngresadas < 9) || ($clienteHorasIngresadas >= 17)){

            $rta = json_encode(array("STATUS"=>"Error, local cerrado"));
            $response->getBody()->write( $rta);

            } else {
                $verFechaSQL = $turnoFecha->format('Y-m-d H:i:s');
                // Me traigo todos los turnos que coincidan con la fecha ingresada por el cliente, idem con el id veterinario
                $turnosRegistradosSQL = turnos::all()->where('fecha',$verFechaSQL)->where('veterinario_id',$turno->veterinario_id);
                
                $aux = json_decode($turnosRegistradosSQL,true);
                // Si no hay turnos registrados ese dia, doy la disponibilidad de turno ok
                if(empty($aux)){
                    $turnoOK = true;
                } 
                else {
                    // Recorro los turnos del SQL
                    foreach ($turnosRegistradosSQL as $indice => $turnoLeidoSQL) {
                        // Transformo la fecha del turno leido del SQL a objeto DateTime
                        $horaTurnoSQL = new \DateTime($turnoLeidoSQL['fecha']);
                        $horaTurnoSQL = $horaTurnoSQL->format("H:i");
                        // Aca uso % adelante de la H o i porque es tipo DateInterval, si es tipo DateTime lo uso sin el %
                        $horasDeDiferencia = intval($horaTurnoSQL)-intval($clienteHora);
                        $min1 = explode(':',$turnoHora->format('H:i'))[1];
                        $min2 = explode(':',$horaTurnoSQL)[1];
                        $minutosDeDiferencia = intval($min1) - intval($min2);
                        // // Veo si hay mas de 1 de diferencia
                        if($horasDeDiferencia > 0 || $horasDeDiferencia < 0){
                            // Hay mas de 1 hr de diferencia, turno disponible
                            $turnoOK = true;
                        } else {
                            // Si no hay diferencia de horas tengo que ver los minutos
                            if($minutosDeDiferencia >= 30){
                                // Si hay mas de 30 minutos el turno se vuelve disponible
                                $turnoOK = true;
                            } else {
                                // No hay 30 minutos de diferencia
                                $rta = json_encode(array("STATUS"=>"Error en solicitar turno, no disponible. Menos de 30 min del anterior turno"));
                                $response->getBody()->write( $rta);
                                $turnoOK = false;
                            }    
                        }
                    }
                }
            }

            if($turnoOK == true){
                $rta = json_encode(array("STATUS"=>$turno->save()));
                $response->getBody()->write($rta);
            }

        return $response;
    }

    public function getTurnosVeterinario(Request $request, Response $response, $args){

        $body = $request->getParsedBody();
        $mascota = new mascotas();

        $headers = getallheaders();
        
        $codVeterinario = 2;
        $codCliente     = 1;
        $existeToken    = utils::ValidarCampo($headers['token']);

        if($existeToken){
            try{

                $payload = utils::DecodeToken($headers['token']);

                if ($payload->tipo == $codVeterinario || $payload->tipo == $codCliente) {

                    if($payload->tipo == $codVeterinario){
                        //FALTA PEGARLE A LAS TABLAS
                    }
                    else{
                        //FALTA PEGARLE A LAS TABLAS
                    }

                    $existingContent = (string)$response->getBody();
                    $response = $handler->handle($request);     
                    $response->getBody()->write($existingContent);
        
                } else {
                    $response->getBody()->write('User invalido (solo veterinario)');
                }
            } 
            catch (\Throwable $th) {
                $response->getBody()->write('Error en token');
            }
        }
        
        return $response;
    }
}