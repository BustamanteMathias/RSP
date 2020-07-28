<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\utils;

class Midd_UsuarioTipos
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $body = $request->getParsedBody();

        $tipoUsuario = $body['tipo'];

        if ($tipoUsuario == 1 || $tipoUsuario == 2 || $tipoUsuario == 3) {
            $existingContent = (string)$response->getBody();
            $response = $handler->handle($request);     
            $response->getBody()->write($existingContent);

        } else {
            $response->getBody()->write(json_encode(array("STATUS ERROR"=>'Tipo de usuario invalido')));
        }

        return $response;
    }
}
?>