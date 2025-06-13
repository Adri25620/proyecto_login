<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;
use Exception;

class LoginController extends ActiveRecord
{

    public function index(Router $router)
    {
        $router->render('login/index', [], 'layout/login');

    }



    
}