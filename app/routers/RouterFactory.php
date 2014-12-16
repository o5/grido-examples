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

        $uri = \App\Presenters\BasePresenter::getExtraPath();
        $router[] = new Route("$uri<filterRenderType>/<presenter>/<action>/<ajax>/", array(
            'filterRenderType' => 'inner',
            'presenter' => 'NetteDatabase',
            'action' => 'default',
            'ajax' => 'on',
        ));

        return $router;
    }
}
