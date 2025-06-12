<?php

namespace Controllers;
use Model\ActiveRecord;
use MVC\Router;

class AppController extends ActiveRecord 
{
    public static function index(Router $router){
        
        $router->render('bienvenida/index', [], 'layout/layouts');
        
    }
}