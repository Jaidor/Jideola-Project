<?php
namespace Jideola\Routes;

class ApiRoute{
    
    public static function loadRoute()
    {
        return[
            'login'=>['POST'],
            'register'=>['POST'],
            'logout'=>['POST']
        ];
    }
}