<?php

use Slim\Routing\RouteCollectorProxy;
use App\controllers\ControllerUsuarios;
use App\controllers\ControllerTurnos;
use App\controllers\ControllerMascota;
use App\controllers\ControllerTipoMascota;
use App\Middlewares\Midd_UsuarioExiste;
use App\Middlewares\Midd_UsuarioExisteID;
use App\Middlewares\Midd_UsuarioTipos;
use App\Middlewares\Midd_UsuarioExisteLogin;
use App\Middlewares\Midd_UsuarioValidarAdmin;
use App\Middlewares\Midd_TipoMascotaExiste;
use App\Middlewares\Midd_MascotaExisteID;
use App\Middlewares\Midd_UsuarioValidarCliente;


return function($app){
//RUTAS
$app->group('/', function (RouteCollectorProxy $group) {
    $group->post('registro[/]', ControllerUsuarios::class . ':postRegistro')
    ->add(Midd_UsuarioExiste::class)
    ->add(Midd_UsuarioTipos::class);

    $group->post('login[/]', ControllerUsuarios::class . ':postLogin')
    ->add(Midd_UsuarioExisteLogin::class);

    $group->post('tipo_mascota[/]', ControllerTipoMascota::class . ':postTipoMascota')
    ->add(Midd_UsuarioValidarAdmin::class);

    $group->post('mascotas[/]', ControllerMascota::class . ':postMascota')
    ->add(Midd_TipoMascotaExiste::class)
    ->add(Midd_UsuarioExisteID::class);
    });

$app->group('/turnos', function (RouteCollectorProxy $group) {
    $group->post('/{id_usuario}[/]', ControllerTurnos::class . ':getTurnosVeterinario');
    });

$app->post('/turnos_mascota[/]', ControllerTurnos::class . ':postTurnos')
    ->add(Midd_MascotaExisteID::class)
    ->add(Midd_UsuarioValidarCliente::Class);
}
?>