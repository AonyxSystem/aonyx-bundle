<?php
/**
 * Created by PhpStorm.
 * User: galicher
 * Date: 26/05/16
 * Time: 13:06
 */
namespace Aonyx\Classes;

use Aonyx\Classes\Errors;

class Module
{

    public function getAction(array $routes, $module) {

        if(isset($_GET['child'])) {

            if(!array_key_exists($_GET['child'],$routes)) {

                // Si l'action n'existe pas on bloque
                Errors::noRouteAction();
            } else {

                // Sinon on appelle le child demandée
                $call = new $routes[$_GET['child']]['namespace'];

                // Si une action existe
                if (isset($_GET['action'])) {

                    $call->{$routes[$_GET['child'].'/'.$_GET['action']]['action']}();
                } else {

                    $call->{$routes[$_GET['child']]['action']}();
                }
            }

        } else {

            $instance = '\Modules\\' . $module . '\Controllers\\' . $module . 'Controller()';
            $call = new $instance;
            $call->indexAction();
        }
    }
}