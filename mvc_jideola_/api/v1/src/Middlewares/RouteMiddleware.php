<?php

namespace Jideola\Middlewares;
use Jideola\Routes\ApiRoute;

class RouteMiddleware{

    public static function requestType(){
        $endpoint = strtolower(trim($_SERVER['ENDPOINT']));
        $routes = ApiRoute::loadRoute();

        if(isset($routes[$endpoint])){
            if(is_array($routes[$endpoint])){
                return $routes[$endpoint];
            }else{
                return [$routes[$endpoint]];
            }
        }
    }
}