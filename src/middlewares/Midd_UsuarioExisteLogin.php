<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\usuarios;
use App\Utils\Utils;


class Midd_UsuarioExisteLogin
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();

        $body = $request->getParsedBody();
        $user = new usuarios();

        $existEmail  = $user->where('email', $body['email'])->exists();

        if($existEmail) {

            $claveDB        = $user->where('email', $body['email'])->value('clave');
            $claveEncode    = Utils::EncodePass($body['clave']);

            if($claveDB == $claveEncode){
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);     
                $response->getBody()->write($existingContent);
            }
            else{
                $response->getBody()->write(json_encode(array("STATUS ERROR"=>'Clave incorrecta')));
            }
        } else {
            $response->getBody()->write(json_encode(array("STATUS ERROR"=>'Usuario no existente')));
        }
        
        return $response;
    }
}
?>