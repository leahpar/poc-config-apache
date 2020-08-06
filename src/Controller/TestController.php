<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        dump(
            getenv('APP_PARAMS_DOMAIN'),
            $_SERVER['APP_PARAMS_DOMAIN'],
            $this->getParameter("app.domain"),
        );
        die();
    }

}

