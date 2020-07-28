<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\usuarios;

class Midd_UsuarioExiste
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();

        $body = $request->getParsedBody();
        $user = new usuarios();

        $existEmail  = $user->where('email', $body['email'])->exists();

        if($existEmail) {
            
            $response->getBody()->write(json_encode(array("STATUS ERROR"=>'Usuario Existente')));

        } else {
            $existingContent = (string) $response->getBody();
            $response = $handler->handle($request);     
            $response->getBody()->write($existingContent);
        }
        
        return $response;
    }
}
?>