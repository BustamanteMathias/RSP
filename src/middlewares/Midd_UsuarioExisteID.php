<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\usuarios;

class Midd_UsuarioExisteID
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();

        $body = $request->getParsedBody();
        $user = new usuarios();

        $existID  = $user->where('id', $body['cliente_id'])->exists();

        if($existID) {
            $existingContent = (string) $response->getBody();
            $response = $handler->handle($request);     
            $response->getBody()->write($existingContent);
        } else {
            
            $response->getBody()->write(json_encode(array("STATUS ERROR"=>'Cliente no existente')));
        }
        
        return $response;
    }
}
?>