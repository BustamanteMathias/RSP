<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\mascotas;
use App\Utils\Utils;


class Midd_MascotaExisteID
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();

        $body = $request->getParsedBody();
        $mascotas = new mascotas();

        $existMascota  = $mascotas->where('id', $body['mascota_id'])->exists();

        if($existMascota) {
            $existingContent = (string) $response->getBody();
            $response = $handler->handle($request);     
            $response->getBody()->write($existingContent);

        } else {
            $response->getBody()->write(json_encode(array("STATUS ERROR"=>'Mascota no existente')));
        }
        
        return $response;
    }
}
?>