<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\tipo_mascota;
use App\Utils\Utils;


class Midd_TipoMascotaExiste
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();

        $body = $request->getParsedBody();
        $mascotas = new tipo_mascota();

        $existMascota  = $mascotas->where('id', $body['tipo_mascota_id'])->exists();

        if($existMascota) {
            $existingContent = (string) $response->getBody();
            $response = $handler->handle($request);     
            $response->getBody()->write($existingContent);

        } else {
            $response->getBody()->write(json_encode(array("STATUS ERROR"=>'Tipo de mascota no existente')));
        }
        
        return $response;
    }
}
?>