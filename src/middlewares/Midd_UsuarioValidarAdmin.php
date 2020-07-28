<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\utils;

class Midd_UsuarioValidarAdmin
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $headers = getallheaders();
        
        $codAdmin = 3;
        $existeToken = utils::ValidarCampo($headers['token']);

        if($existeToken){
            try{

                $payload = utils::DecodeToken($headers['token']);

                if ($payload->tipo == $codAdmin) {
                    $existingContent = (string)$response->getBody();
                    $response = $handler->handle($request);     
                    $response->getBody()->write($existingContent);
        
                } else {
                    $response->getBody()->write('User invalido (solo admin)');
                }
            } 
            catch (\Throwable $th) {
                $response->getBody()->write('Error en token');
            }
        }

        return $response;
    }
}
?>