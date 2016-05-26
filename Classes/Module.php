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

    public function getAction(array $routes, $child, $action, $module) {

        if(isset($child)) {

            if(!array_key_exists($child,$routes)) {

                // Si l'action n'existe pas on bloque
                Errors::noRouteAction();
            } else {

                // Sinon on appelle le child demandée
                $call = new $routes[$child]['namespace'];

                // Si une action existe
                if (isset($action)) {

                    $call->{$routes[$child.'/'.$action]['action']}();
                } else {

                    $call->{$routes[$child]['action']}();
                }
            }

        } else {

            $instance = '\Modules\\' . $module . '\Controllers\\' . $module . 'Controller()';
            $call = new $instance;
            $call->indexAction();
        }
    }
}