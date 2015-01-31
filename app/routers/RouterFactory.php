<?php

namespace App\Routers;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

/**
 * Router factory.
 */
class RouterFactory
{
    /**
     * @return \Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList;

        $uri = self::getExtraPath();
        $router[] = new Route("$uri<filterRenderType>/<presenter>/<action>/<ajax>/", array(
            'filterRenderType' => 'inner',
            'presenter' => 'NetteDatabase',
            'action' => 'default',
            'ajax' => 'on',
        ));

        return $router;
    }

    public static function getExtraPath()
    {
        return $_SERVER['HTTP_HOST'] === 'grido.bugyik.cz'
            ? '/example/'
            : NULL;
    }
}
